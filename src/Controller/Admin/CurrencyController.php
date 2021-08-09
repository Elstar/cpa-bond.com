<?php

namespace App\Controller\Admin;

use App\Entity\Currency;
use App\Form\Admin\CurrencyFormType;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class CurrencyController extends AbstractController
{
    /**
     * @Route("/admin/currency", name="app_admin_currency")
     */
    public function index(CurrencyRepository $repository): Response
    {
        $currencies = $repository->findAll();

        return $this->render('admin/currency/index.html.twig', [
            'currencies' => $currencies,
        ]);
    }

    /**
     * @Route("/admin/currency/create", name="app_admin_currency_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {

        $currencyForm = $this->createForm(CurrencyFormType::class, new Currency());

        if ($this->handleFormRequest($currencyForm, $em, $request)) {
            $this->addFlash('flash_message', 'Currency added successfully');
        }

        return $this->render('admin/currency/create.html.twig', [
            'currency' => null,
            'currencyForm' => $currencyForm->createView()
        ]);
    }

    /**
     * @Route("/admin/currency/{id}", name="app_admin_currency_edit")
     */
    public function edit(Currency $currency, EntityManagerInterface $em, Request $request): Response
    {
        $currencyForm = $this->createForm(CurrencyFormType::class, $currency);

        if ($this->handleFormRequest($currencyForm, $em, $request)) {
            $this->addFlash('flash_message', 'Currency edited successfully');
        }

        return $this->render('admin/currency/create.html.twig', [
            'currency' => $currency,
            'currencyForm' => $currencyForm->createView()
        ]);
    }

    private function handleFormRequest(FormInterface $form, EntityManagerInterface $em, Request $request): ?Currency
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Currency $currency
             */
            $currency = $form->getData();

            $em->persist($currency);
            $em->flush();

            return $currency;
        }
        return null;
    }
}
