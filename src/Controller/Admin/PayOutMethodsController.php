<?php

namespace App\Controller\Admin;

use App\Entity\PaymentDetail;
use App\Entity\PayOutMethods;
use App\Form\Admin\PaymentDetailFormType;
use App\Form\Admin\PayOutMethodsFormType;
use App\Repository\PaymentDetailRepository;
use App\Repository\PayOutMethodsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    /**
     * @Route(
     *     {"uk": "/admin/pay-out-methods/{id}/uk/details", "ru": "/admin/pay-out-methods/{id}/ru/details", "en": "/admin/pay-out-methods/{id}/en/details"},
     *     name="app_admin_pay_out_methods_details"
     * )
     */
    public function details(PayOutMethods $payOutMethods, EntityManagerInterface $em, Request $request, ContainerInterface $container): Response
    {
        $paymentDetail = new PaymentDetail();
        $paymentDetail->setPayOutMethods($payOutMethods);
        $paymentDetailForm = $this->createForm(PaymentDetailFormType::class, $paymentDetail);

        $paymentDetailForm->handleRequest($request);
        if ($paymentDetailForm->isSubmitted() && $paymentDetailForm->isValid()) {
            /**
             * @var PaymentDetail $paymentDetail
             */
            $paymentDetail = $paymentDetailForm->getData();
            $paymentDetail->setTranslatableLocale($container->getParameter('locale'));

            $em->persist($paymentDetail);
            $em->flush();

            $this->addFlash('flash_message', 'Payment detail property added successfully');
            return $this->redirectToRoute('app_admin_pay_out_methods_details', ['id' => $payOutMethods->getId()]);
        }

        return $this->render('admin/pay_out_methods/details.html.twig', [
            'paymentDetailForm' => $paymentDetailForm->createView(),
            'payOutMethods' => $payOutMethods
        ]);
    }

    /**
     * @Route(
     *     {"uk": "/admin/pay-out-methods/{id}/uk/details/edit", "ru": "/admin/pay-out-methods/{id}/ru/details/edit", "en": "/admin/pay-out-methods/{id}/en/details/edit"},
     *     name="app_admin_pay_out_methods_details_edit"
     * )
     */
    public function detailEdit(PaymentDetail $paymentDetail, EntityManagerInterface $em, Request $request): Response
    {
        if (!$paymentDetail->getLocale())
            $paymentDetail->setLocale($request->getLocale());
        $paymentDetailForm = $this->createForm(PaymentDetailFormType::class, $paymentDetail);

        $paymentDetailForm->handleRequest($request);

        if ($paymentDetailForm->isSubmitted() && $paymentDetailForm->isValid()) {
            /**
             * @var PaymentDetail $paymentDetail
             */
            $paymentDetail = $paymentDetailForm->getData();
            $paymentDetail->setTranslatableLocale($paymentDetail->getLocale());

            $em->persist($paymentDetail);
            $em->flush();

            $this->addFlash('flash_message', 'Payment detail property added successfully');
        }

        return $this->render('admin/pay_out_methods/details.edit.html.twig', [
            'paymentDetailForm' => $paymentDetailForm->createView(),
            'payOutMethods' => $paymentDetail->getPayOutMethods()
        ]);
    }

    /**
     * @Route("/admin/pay-out-methods/details/{id}/delete", name="app_admin_pay_out_methods_details_delete")
     */
    public function detailDelete(PaymentDetail $paymentDetail, PaymentDetailRepository $repository): Response
    {
        if (!empty($paymentDetail)) {
            if ($repository->delete($paymentDetail)) {
                $this->addFlash('flash_message', 'Payment detail property deleted successfully');
            }
        } else {
            $this->addFlash('flash_message_error', 'Payment detail property already deleted');
        }
    }

    private function handleFormRequest(
        FormInterface $form,
        EntityManagerInterface $em,
        Request $request
    ): ?PayOutMethods {
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
