<?php

namespace App\Controller\Admin;

use App\Entity\Postback;
use App\Entity\User;
use App\Form\Admin\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="app_admin_user")
     * @IsGranted("ROLE_MANAGER")
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/user/index.html.twig', [
            "users" => $users
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="app_admin_user_detail")
     * @IsGranted("ROLE_ADMIN")
     */
    public function userDetail(User $user, Request $request, EntityManagerInterface $em): Response
    {

        $userForm = $this->createForm(UserFormType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user = $userForm->getData();

            $em->persist($user);
            $em->flush();

            $this->addFlash('flash_message', 'User successfully edited');
        }

        return $this->render('admin/user/user.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    /**
     * @Route("/admin/user/changeActivate/{id}", name="app_admin_user_change_activate")
     * @IsGranted("ROLE_MANAGER")
     */
    public function changeUserActivate(User $user, EntityManagerInterface $em): Response
    {
        if ($user->getActivate()) {
            $user->setActivate(0);
            $this->addFlash('flash_message', $user->getEmail() . ' successfully deactivated');
        } else {
            $user->setActivate(1);
            $this->addFlash('flash_message', $user->getEmail() . ' successfully activated');
        }

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_admin_user');
    }

    /**
     * @Route("/admin/user/changePayoutAccess/{id}", name="app_admin_user_change_payout_access")
     * @IsGranted("ROLE_MANAGER")
     */
    public function changeUserPayoutAccess(User $user, EntityManagerInterface $em): Response
    {
        if ($user->getPayOutAccess()) {
            $user->setPayOutAccess(0);
            $this->addFlash('flash_message', $user->getEmail() . ' successfully payouts deactivated');
        } else {
            $user->setPayOutAccess(1);
            $this->addFlash('flash_message', $user->getEmail() . ' successfully payouts activated');
        }

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_admin_user');
    }
}
