<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Events\UserRegisteredEvent;
use App\Form\Model\UserRegistrationFormModel;
use App\Form\UserRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route({"uk": "/login", "ru": "/ru/login", "en": "/en/login"}, name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin_dashboard');
            }
            return $this->redirectToRoute('app_webmaster_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $last_username = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $last_username, 'error' => $error]);
    }

    /**
     * @Route({"uk": "/register", "ru": "/ru/register", "en": "/en/register"}, name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        AuthenticationUtils $authenticationUtils,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher
    ): Response {
        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UserRegistrationFormModel $userModel
             */
            $user_model = $form->getData();
            $user = new User();
            $user->setEmail($user_model->email)
                ->setFirstName($user_model->firstName)
                ->setPassword($passwordEncoder->hashPassword($user, $user_model->plainPassword))
                ->setActivate(0)
                ->setBalance(0)
                ->setTelegram($user_model->telegram)
                ->setViber($user_model->viber)
                ->setSkype($user_model->skype)
                ->setPayOutAccess(0)
                ->setCountRequestsPerTime(0)
                ->setApiToken(md5(uniqid('token_' . $user_model->email . rand(), true)));
            $em->persist($user);
            $em->flush();

            //$dispatcher->dispatch(new UserRegisteredEvent($user));

            return $this->render('security/register.html.twig', [
                'success' => true,
                'registrationForm' => $form->createView()
            ]);
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/register.html.twig', [
            'error' => $error,
            'success' => false,
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
