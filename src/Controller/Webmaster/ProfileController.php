<?php

namespace App\Controller\Webmaster;

use App\Entity\PaymentSystem;
use App\Entity\PayOutMethods;
use App\Entity\Postback;
use App\Entity\Stream;
use App\Entity\User;
use App\Form\Webmaster\PostbackFormType;
use App\Repository\PaymentSystemRepository;
use App\Repository\PayOutMethodsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route({"uk": "/webmaster/profile", "ru": "/ru/webmaster/profile", "en": "/en/webmaster/profile"}, name="app_webmaster_profile")
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        PayOutMethodsRepository $payOutMethodsRepository
    ): Response {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $postBacks = $user->getPostback();
        if (!$postBacks) {
            $postBacks = new Postback();
        }
        $postbackForm = $this->createForm(PostbackFormType::class, $postBacks);

        $postbackForm->handleRequest($request);

        $payoutFirstMethod = $payOutMethodsRepository->findOneBy(['active' => 1], ['id' => 'ASC']);

        if ($postbackForm->isSubmitted() && $postbackForm->isValid()) {
            /**
             * @var Postback $postBack
             */
            $postBack = $postbackForm->getData();

            $postBack->setUser($this->getUser());

            $em->persist($postBack);
            $em->flush();
            $this->addFlash('flash_message', 'Postback edited successfully');
        }

        return $this->render('webmaster/profile/index.html.twig', [
            'postbackForm' => $postbackForm->createView(),
            'payoutFirstMethod' => $payoutFirstMethod
        ]);
    }

    /**
     * @Route(
     *     {"uk": "/webmaster/profile/payment-system/{id}", "ru": "/ru/webmaster/profile/payment-system/{id}", "en": "/en/webmaster/profile/payment-system/{id}"},
     *     name="app_webmaster_profile_payment"
     * )
     */
    public function paymentSystem(
        PayOutMethods $payOutMethod,
        PayOutMethodsRepository $payOutMethodsRepository,
        PaymentSystemRepository $paymentSystemRepository,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $paymentSystems = $payOutMethodsRepository->findAll();


        $paymentUser = $paymentSystemRepository->findOneBy([
            'user' => $user->getId(),
            'payoutMethod' => $payOutMethod->getId()
        ]);
        $paymentUserData = null;
        if (!empty($paymentUser)) {
            $paymentUserData = $paymentUser->getDetails();
        }
        if ($request->getMethod() == 'POST') {
            $data = $request->request->all();

            if (empty($paymentUser))
                $paymentUser = new PaymentSystem();
            $paymentUser
                ->setUser($this->getUser())
                ->setPayoutMethod($payOutMethod)
                ->setDetails(serialize($data))
            ;

            $em->persist($paymentUser);
            $em->flush();
            $paymentUserData = $paymentUser->getDetails();
            $this->addFlash('flash_message', 'Payment system edited successfully');
        }

        return $this->render('webmaster/profile/payment.html.twig', [
            'paymentSystems' => $paymentSystems,
            'payOutMethod' => $payOutMethod,
            'paymentUserData' => $paymentUserData
        ]);
    }

    /**
     * @Route({"uk": "/profile/order", "ru": "/ru/profile/order", "en": "/en/profile/order"}, name="app_webmaster_download_order_file")
     */
    public function downloadOrderFile()
    {
        $file = __DIR__ . '/../../../public/files/order.php';

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        } else {
            throw new FileException('File order.php not found');
        }
    }
}
