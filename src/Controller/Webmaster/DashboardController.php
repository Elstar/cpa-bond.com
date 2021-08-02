<?php

namespace App\Controller\Webmaster;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController
 * @IsGranted("ROLE_USER")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route({"uk": "/dashboard", "ru": "/ru/dashboard", "en": "/en/dashboard"}, name="app_webmaster_dashboard")
     */
    public function index(): Response
    {
        return $this->render('webmaster/index.html.twig');
    }
}
