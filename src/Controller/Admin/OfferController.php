<?php

namespace App\Controller\Admin;

use App\Entity\Geo;
use App\Entity\Offer;
use App\Form\Admin\OfferFormType;
use App\Repository\OfferRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class OfferController extends AbstractController
{
    /**
     * @Route({"uk": "/admin/offer/uk", "ru": "/admin/offer/ru", "en": "/admin/offer/en"}, name="app_admin_offer")
     */
    public function index(OfferRepository $repository): Response
    {
        return $this->render('admin/offer/index.html.twig', [
            'offers' => $repository->findAll()
        ]);
    }

    /**
     * @Route(
     *     {"uk": "/admin/offer/create", "ru": "/admin/offer/ru/create", "en": "/admin/offer/en/create"},
     *     name="app_admin_offer_create"
     *     )
     */
    public function create(ContainerInterface $container, EntityManagerInterface $em, Request $request, FileUploader $offerFileSystem): Response
    {
        $offer = new Offer();
        $offer->setTranslatableLocale($container->getParameter('locale'));

        $offerForm = $this->createForm(OfferFormType::class, $offer);

        if ($this->handleFormRequest($offerForm, $em, $request, $offerFileSystem)) {
            $this->addFlash('flash_message', 'Offer added successfully');
            return $this->redirectToRoute('app_webmaster_stream');
        }

        return $this->render('admin/offer/create.html.twig', [
            'offerForm' => $offerForm->createView(),
            'surf' => 0
        ]);
    }

    /**
     * @Route(
     *     {"uk": "/admin/offer/uk/{id}/edit", "ru": "/admin/offer/ru/{id}/edit", "en": "/admin/offer/en/{id}/edit"},
     *     name="app_admin_offer_edit"
     *     )
     */
    public function edit(Offer $offer, EntityManagerInterface $em, Request $request, FileUploader $offerFileSystem): Response
    {
        if (!$offer->getLocale()) {
            $offer->setTranslatableLocale($request->getLocale());
        }

        $offerForm = $this->createForm(OfferFormType::class, $offer);

        if ($this->handleFormRequest($offerForm, $em, $request, $offerFileSystem)) {
            $this->addFlash('flash_message', 'Offer edited successfully');
        }

        return $this->render('admin/offer/create.html.twig', [
            'offer' => $offer,
            'offerForm' => $offerForm->createView()
        ]);
    }

    private function handleFormRequest(FormInterface $form, EntityManagerInterface $em, Request $request, FileUploader $offerFileSystem): ?Offer
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Offer $offer
             */
            $offer = $form->getData();
            if ($offer->getLocale()) {
                $offer->setTranslatableLocale($offer->getLocale());
            }
            /**
             * @var UploadedFile $image
             */
            $image = $form->get('image')->getData();

            if ($image) {
                $offer->setImageFilename($offerFileSystem->uploadFile($image));
            }

            $em->persist($offer);
            $em->flush();

            return $offer;
        }
        return null;
    }
}
