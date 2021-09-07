<?php

namespace App\Controller\Webmaster;

use App\Entity\Postback;
use App\Entity\Stream;
use App\Entity\User;
use App\Form\Webmaster\PostbackFormType;
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
     * @Route("/webmaster/profile", name="app_webmaster_profile")
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $postBacks = $user->getPostbacks();
        if (!$postBacks)
            $postBacks = new Postback();
        $postbackForm = $this->createForm(PostbackFormType::class, $postBacks);

        $postbackForm->handleRequest($request);

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
            'postbackForm' => $postbackForm->createView()
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
