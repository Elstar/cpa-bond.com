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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */

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
        $this->geoIp = new Reader($_SERVER['PWD'] . '/src/geoIp/GeoIP2-Country.mmdb');
        $this->leadRepository = $leadRepository;
    }

    /**
     * @Route("/api/v1/new-lead", name="api_order")
     */
    public function create(Request $request, StreamRepository $streamRepository, EntityManagerInterface $em): Response
    {
        $dataError = [];
        $dataSuccess = [];
        $status = 200;
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if ($request->getMethod() != 'POST') {
            $dataError = [
                'message' => 'You need POST method for order create '
            ];
            $status = Response::HTTP_METHOD_NOT_ALLOWED;
        }

        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $ip = IP::factory($data['ip']);
        } else {
            $dataError = [
                'message' => 'You need use application/json Content-Type for order create'
            ];
            $status = Response::HTTP_CONFLICT;
        }
        //$this->geoIp->country('178.159.227.45')->country->isoCode UA

        if (!empty($data)) {
            if (empty($data['stream_id'])) {
                $dataError = [
                    'message' => 'Empty stream_id',
                ];
                $status = Response::HTTP_BAD_REQUEST;
            } else {
                /**
                 * @var Stream $stream;
                 */
                $stream = $streamRepository->findOneBy(['id' => $data['stream_id']]);

                if (empty($stream) || $stream->getUser()->getId() != $user->getId()) {
                    $dataError = [
                        'message' => 'Wrong stream_id'
                    ];
                }
                if (
                    empty($data['geo'])
                    || $stream->getGeo()->getName() != $data['geo']
                    //|| $this->geoIp->country($ip->getDotAddress())->country->isoCode != $stream->getGeo()->getName()
                ) {
                    $dataError = [
                        'message' => 'Wrong or empty geo'
                    ];
                }
                if (empty($data['phone'])) {
                    $dataError = [
                        'message' => 'Phone is empty'
                    ];
                }
                $hash = md5($stream->getOffer()->getId() . '-' . $data['phone']);
                if ($this->leadRepository->getLastLeadsFromOneIpCount($ip, $stream) > 2) {
                    $dataError = [
                        'message' => 'To much orders from one IP'
                    ];
                }
                if ($this->leadRepository->getLasLeadsByOfferAndPhoneCount($hash) > 0) {
                    $dataError = [
                        'message' => 'Duplicated lead in system'
                    ];
                }
                if (empty($dataError)) {
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
                    ;
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
                    $em->persist($lead);
                    $em->flush();
                    $dataSuccess = [
                        'lead_id' => $lead->getId(),
                        'status' => 'ok'
                    ];
                } else {
                    $status = Response::HTTP_BAD_REQUEST;
                }
            }
        } else {
            $dataError = [
                'message' => 'Empty data'
            ];
            $status = Response::HTTP_BAD_REQUEST;
        }

        if (!empty($dataError)) {
            $dataError['status'] = 'error';
            return new JsonResponse($dataError, $status);
        }

        return $this->json($dataSuccess, $status, []);
    }
}
