<?php

namespace App\Controller\Webmaster;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/webmaster/profile", name="webmaster_profile")
     */
    public function index(): Response
    {
        return $this->render('webmaster/profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    /**
     * @Route({"uk": "/profile/order", "ru": "/ru/profile/order", "en": "/en/profile/order"}, name="app_webmaster_download_order_file")
     */
    public function downloadOrderFile()
    {
        $file =  __DIR__. '/../../../public/files/order.php';

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
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
