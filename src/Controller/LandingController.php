<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LandingController extends AbstractController
{
    /**
     * @Route(
     *     "/{_locale}",
     *     name="app_landing_home",
     *     requirements={
     *         "_locale": "en|ru"
     *     },
     *     defaults={
     *         "_locale": "uk"
     *     }
     * )
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('landing/index.html.twig', [
            'controller_name' => 'LandingController',
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }
}
