<?php

namespace App\Controller\Admin;

use App\Entity\Currency;
use App\Entity\Geo;
use App\Form\Admin\GeoFormType;
use App\Repository\GeoRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeoController extends AbstractController
{
    /**
     * @Route("/admin/geo", name="app_admin_geo")
     */
    public function index(GeoRepository $repository): Response
    {
        $geos = $repository->findAll();
        return $this->render('admin/geo/index.html.twig', [
            'geos' => $geos
        ]);
    }

    /**
     * @Route("/admin/geo/create", name="app_admin_geo_create")
     */
    public function create(EntityManagerInterface $em, Request $request, FileUploader $fileSystem): Response
    {
        $geoForm = $this->createForm(GeoFormType::class, new Geo());
        if ($geo = $this->handleFormRequest($geoForm, $em, $request, $fileSystem)) {
            $this->addFlash('flash_message', 'Geo added successfully');
        }

        $geo = $geo ?? null;

        return $this->render('admin/geo/create.html.twig', [
            'geoForm' => $geoForm->createView(),
            'geo' => $geo
        ]);
    }

    /**
     * @Route("/admin/geo/edit/{id}", name="app_admin_geo_edit")
     */
    public function edit(Geo $geo, Request $request, EntityManagerInterface $em, FileUploader $fileSystem): Response
    {
        $geoForm = $this->createForm(GeoFormType::class, $geo);

        if ($this->handleFormRequest($geoForm, $em, $request, $fileSystem)) {
            $this->addFlash('flash_message', 'Geo edited successfully');
        }

        return $this->render('admin/geo/create.html.twig', [
            'geoForm' => $geoForm->createView(),
            'geo' => $geo
        ]);
    }

    private function handleFormRequest(FormInterface $form, EntityManagerInterface $em, Request $request, FileUploader $fileSystem): ?Geo
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Geo $geo
             */
            $geo = $form->getData();
            /**
             * @var UploadedFile $image
             */
            $image = $form->get('image')->getData();

            if ($image) {
                $geo->setImageFilename($fileSystem->uploadFile($image));
            }

            $em->persist($geo);
            $em->flush();

            return $geo;
        }
        return null;
    }
}
