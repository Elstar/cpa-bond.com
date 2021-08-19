<?php

namespace App\Controller\Webmaster;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class DashboardController
 * @IsGranted("ROLE_USER")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route({"uk": "uk/webmaster/dashboard", "ru": "/ru/webmaster/dashboard", "en": "/en/webmaster/dashboard"}, name="app_webmaster_dashboard")
     */
    public function index(Security $security): Response
    {

        if ($security->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->generateUrl('app_admin_dashboard'));
        }

        return $this->render('webmaster/index.html.twig');
    }
}
