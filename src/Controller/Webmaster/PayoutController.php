<?php

namespace App\Controller\Webmaster;

use App\Entity\BalanceOperations;
use App\Entity\Payout;
use App\Entity\User;
use App\Form\Webmaster\PayoutFormType;
use App\Repository\PayoutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PayoutController extends AbstractController
{
    /**
     * @Route({"uk": "/uk/webmaster/payout", "ru": "/ru/webmaster/payout", "en": "/en/webmaster/payout"}, name="app_webmaster_payout")
     */
    public function index(PayoutRepository $repository): Response
    {
        $payouts = $repository->findAll();

        return $this->render('webmaster/payout/index.html.twig', [
            'payouts' => $payouts
        ]);
    }

    /**
     * @Route(
     *     {"uk": "/uk/webmaster/payout/create", "ru": "/ru/webmaster/payout/create", "en": "/en/webmaster/payout/create"},
     *     name="app_webmaster_payout_create"
     * )
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $payout = new Payout();
        $payout->setUser($user);
        $payoutForm = $this->createForm(PayoutFormType::class, $payout);

        $payoutForm->handleRequest($request);

        if ($payoutForm->isSubmitted() && $payoutForm->isValid()) {
            /**
             * @var Payout $payout
             */
            $payout = $payoutForm->getData();
            $payout->setStatus(0);
            $payout->setFinalSum();

            $balanceOperations = new BalanceOperations();
            $balanceOperations
                ->setUser($user)
                ->setSum($payout->getSum() * -1)
                ->setOperationType(1)
            ;

            $user->decreaseBalance($payout->getSum());

            $em->persist($payout);
            $em->persist($balanceOperations);
            $em->persist($user);
            $em->flush();

            $this->addFlash('flash_message', 'Payout request sent successfully');
            return $this->redirectToRoute('app_webmaster_payout');
        }

        return $this->render('webmaster/payout/create.html.twig', [
            'payoutForm' => $payoutForm->createView()
        ]);
    }
}
