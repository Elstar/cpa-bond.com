<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route({"uk": "/admin", "ru": "/ru/admin", "en": "/en/admin"}, name="app_admin_dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [

        ]);
    }
}
