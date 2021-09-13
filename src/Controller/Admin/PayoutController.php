<?php

namespace App\Controller\Admin;

use App\Entity\BalanceOperations;
use App\Entity\Payout;
use App\Repository\PayoutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PayoutController extends AbstractController
{
    /**
     * @Route("/admin/payout/{status}", name="app_admin_payout", defaults={"status": 0})
     */
    public function index(
        int $status,
        PayoutRepository $payoutRepository,
        Request $request,
        PaginatorInterface $paginator,
        ContainerInterface $container,
        EntityManagerInterface $em
    ): Response {

        if ($request->getMethod() == 'POST') {
            $data = $request->request->all();

            $payout = $payoutRepository->findOneBy(['id' => $data['id']]);
            $payout->setStatus($data['status']);
            if ($data['reason']) {
                $payout->setReason($data['reason']);
            }

            if ($data['status'] == 2) {
                $balanceOperations = new BalanceOperations();
                $user = $payout->getUser();
                $balanceOperations
                    ->setUser($user)
                    ->setSum($payout->getSum())
                    ->setOperationType(1)
                ;

                $user->increaseBalance($payout->getSum());

                $em->persist($balanceOperations);
                $em->persist($user);
            }

            $em->persist($payout);
            $em->flush();

            $this->addFlash('flash_message', 'Payout successfully changed status');
        }

        $pagination = $paginator->paginate(
            $payoutRepository->findBy(['status' => $status]),
            $request->query->getInt('page', 1),
            $container->getParameter('count_payouts_in_page')
        );

        return $this->render('admin/payout/index.html.twig', [
            'status' => $status,
            'pagination' => $pagination
        ]);
    }
}
