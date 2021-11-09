<?php

namespace App\Controller\Api;

use App\Entity\Lead;
use App\Entity\Stream;
use App\Entity\User;
use App\Repository\LeadRepository;
use App\Repository\StreamRepository;
use Darsyn\IP\Version\Multi as IP;
use Doctrine\ORM\EntityManagerInterface;
use GeoIp2\Database\Reader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LeadController extends AbstractController
{
    private Reader $geoIp;
    private LeadRepository $leadRepository;

    /**
     * LeadController constructor.
     * @throws \MaxMind\Db\Reader\InvalidDatabaseException
     */
    public function __construct(LeadRepository $leadRepository)
    {
        $this->geoIp = new Reader(__DIR__ . '/../../geoIp/GeoIP2-Country.mmdb');
        $this->leadRepository = $leadRepository;
    }

    /**
     * @Route("/api/v1/new-lead", name="api_order")
     * @IsGranted("ROLE_USER")
     */
    public function create(
        Request $request,
        StreamRepository $streamRepository,
        EntityManagerInterface $em,
        ContainerInterface $container
    ): Response {
        $data_error = [];
        $data_success = [];
        $status = 200;

        /**
         * @var User $user
         */
        $user = $this->getUser();

        //Check requests limit (per minute)
        if ($user->getCountRequestsPerTime() > $container->getParameter('api_count_requests_per_time_limit')) {
            $data_error = [
                'message' => 'You have request limit, try bit later'
            ];
            return new JsonResponse($data_error, Response::HTTP_LOCKED);
        }

        if (empty($data_error) && $request->getMethod() != 'POST') {
            $data_error = [
                'message' => 'You need POST method for order create'
            ];
            $status = Response::HTTP_CONFLICT;
        }

        if (empty($data_error) && 0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $ip = IP::factory($data['ip']);
        } elseif (empty($data_error)) {
            $data_error = [
                'message' => 'You need use application/json Content-Type for order create'
            ];
            $status = Response::HTTP_CONFLICT;
        }
        //$this->geoIp->country('178.159.227.45')->country->isoCode UA

        if (!empty($data)) {
            if (empty($data['stream_id'])) {
                $data_error = [
                    'message' => 'Empty stream_id',
                ];
                $status = Response::HTTP_BAD_REQUEST;
            } else {
                /**
                 * @var Stream $stream ;
                 */

                $stream = $streamRepository->findOneBy(['uniqueId' => trim($data['stream_id'])]);

                if (empty($stream) || $stream->getUser()->getId() != $user->getId()) {
                    $data_error = [
                        'message' => 'Wrong stream_id'
                    ];
                }
                if (
                    empty($data['geo'])
                    || ($stream && $stream->getGeo()->getName() != $data['geo'])
                    //|| $this->geoIp->country($ip->getDotAddress())->country->isoCode != $stream->getGeo()->getName()
                ) {
                    $data_error = [
                        'message' => 'Wrong or empty geo'
                    ];
                }
                if (empty($data['phone'])) {
                    $data_error = [
                        'message' => 'Phone is empty'
                    ];
                }
                if ($stream) {
                    $hash = md5($stream->getOffer()->getId() . '-' . $data['phone']);
                    if ($this->leadRepository->getLastLeadsFromOneIpCount($ip, $stream) > 10) {
                        $data_error = [
                            'message' => 'To much orders from one IP'
                        ];
                    }
                    if ($this->leadRepository->getLastLeadsByOfferAndPhoneCount($hash) > 10) {
                        $data_error = [
                            'message' => 'Duplicated lead in system'
                        ];
                    }
                }
                if (empty($data_error)) {
                    $lead = new Lead();
                    $lead
                        ->setStream($stream)
                        ->setGeo($stream->getGeo())
                        ->setOffer($stream->getOffer())
                        ->setPhone($data['phone'])
                        ->setIp($ip)
                        ->setHash($hash)
                        ->setStatus(0)
                        ->setPayStatus(0)
                        ->setUniqueId()
                        ->setGatewayStatus(0)
                        ->setUser($user);
                    if (!empty($data['ua'])) {
                        $lead->setUa($data['ua']);
                    }
                    if (!empty($data['utm_medium'])) {
                        $lead->setUtmMedium($data['utm_medium']);
                    }
                    if (!empty($data['utm_campaign'])) {
                        $lead->setUtmCampaign($data['utm_campaign']);
                    }
                    if (!empty($data['utm_content'])) {
                        $lead->setUtmContent($data['utm_content']);
                    }
                    if (!empty($data['utm_term'])) {
                        $lead->setUtmTerm($data['utm_term']);
                    }
                    if (!empty($data['name'])) {
                        $lead->setFirstName($data['name']);
                    }
                    if (!empty($data['sum'])) {
                        $lead->setSum((int)$data['sum']);
                    }
                    if (!empty($data['referer'])) {
                        $lead->setReferer($data['referer']);
                    }
                    $lead->setFullRequestData(serialize($data));

                    $user->setCountRequestsPerTime($user->getCountRequestsPerTime() + 1);

                    $em->persist($lead);
                    $em->persist($user);
                    $em->flush();
                    $data_success = [
                        'lead_id' => $lead->getUniqueId(),
                        'status' => 'ok'
                    ];
                } else {
                    $status = Response::HTTP_BAD_REQUEST;
                }
            }
        } elseif (empty($data_error)) {
            $data_error = [
                'message' => 'Empty data'
            ];
            $status = Response::HTTP_BAD_REQUEST;
        }

        if (!empty($data_error)) {
            $data_error['status'] = 'error';
            return new JsonResponse($data_error, $status);
        }

        return $this->json($data_success, $status, []);
    }

    /**
     * @Route("/api/v1/get-lead/{uniqueId}", name="api_order_get")
     * @IsGranted("ROLE_USER")
     */
    public function getLead(?Lead $lead, Request $request, EntityManagerInterface $em): Response
    {
        $status = 200;
        $data_error = [];
        $data_success = [];

        /**
         * @var User $user
         */
        $user = $this->getUser();

        if ($request->getMethod() != 'GET') {
            $data_error = [
                'message' => 'You need GET method for get lead'
            ];
            $status = Response::HTTP_METHOD_NOT_ALLOWED;
        }

        if (empty($lead)) {
            $data_error = [
                'message' => 'Empty data'
            ];
            $status = Response::HTTP_BAD_REQUEST;
        } else {
            if ($user->getId() != $lead->getUser()->getId()) {
                $data_error = [
                    'message' => 'This is not your lead'
                ];
                $status = Response::HTTP_CONFLICT;
            }
            if (empty($data_error)) {
                $ip = $lead->getIp();
                /**
                 * @var IP $ip
                 */
                $data_success = [
                    'id' => $lead->getUniqueId(),
                    'stream_id' => $lead->getStream()->getUniqueId(),
                    'geo' => $lead->getGeo()->getName(),
                    'ip' => $ip->getDotAddress(),
                    'ua' => $lead->getUa(),
                    'name' => $lead->getFirstName() ?? '',
                    'phone' => $lead->getPhone(),
                    'status' => $lead->getStatus(),
                    'status_comment' => $lead->getStatusComment(),
                    'offer_id' => $lead->getOffer()->getId(),
                    'utm_medium' => $lead->getUtmMedium(),
                    'utm_campaign' => $lead->getUtmCampaign(),
                    'utm_content' => $lead->getUtmContent(),
                    'utm_term' => $lead->getUtmTerm(),
                    'referer' => $lead->getReferer(),
                    'created_at' => $lead->getCreatedAt()
                ];
            }
        }
        if (!empty($data_error)) {
            $data_error['status'] = 'error';
            return new JsonResponse($data_error, $status);
        }
        return $this->json($data_success, $status, []);
    }
}
