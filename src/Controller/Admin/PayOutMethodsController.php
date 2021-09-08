<?php

namespace App\Controller\Admin;

use App\Entity\PayOutMethods;
use App\Form\Admin\PayOutMethodsFormType;
use App\Repository\PayOutMethodsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PayOutMethodsController extends AbstractController
{
    /**
     * @Route("/admin/pay-out-methods", name="app_admin_pay_out_methods")
     */
    public function index(PayOutMethodsRepository $payOutMethodsRepository): Response
    {
        $payoutMethods = $payOutMethodsRepository->findAll();
        return $this->render('admin/pay_out_methods/index.html.twig', [
            'payoutMethods' => $payoutMethods
        ]);
    }

    /**
     * @Route("/admin/pay-out-methods/create", name="app_admin_pay_out_methods_create")
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $payoutMethodsForm = $this->createForm(PayOutMethodsFormType::class);

        if ($this->handleFormRequest($payoutMethodsForm, $em, $request)) {
            $this->addFlash('flash_message', 'Payout method added successfully');
        }

        return $this->render('admin/pay_out_methods/create.html.twig', [
            'payOutMethods' => null,
            'payoutMethodsForm' => $payoutMethodsForm->createView()
        ]);
    }

    /**
     * @Route("/admin/pay-out-methods/{id}", name="app_admin_pay_out_methods_edit")
     */
    public function edit(PayOutMethods $payOutMethods, EntityManagerInterface $em, Request $request): Response
    {
        $payoutMethodsForm = $this->createForm(PayOutMethodsFormType::class, $payOutMethods);

        if ($this->handleFormRequest($payoutMethodsForm, $em, $request)) {
            $this->addFlash('flash_message', 'Payout method edited successfully');
        }

        return $this->render('admin/pay_out_methods/create.html.twig', [
            'payoutMethodsForm' => $payoutMethodsForm->createView(),
            'payOutMethods' => $payOutMethods
        ]);
    }

    private function handleFormRequest(FormInterface $form, EntityManagerInterface $em, Request $request): ?PayOutMethods
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var PayOutMethods $payoutMethod
             */
            $payoutMethod = $form->getData();

            $em->persist($payoutMethod);
            $em->flush();

            return $payoutMethod;
        }
        return null;
    }
}
