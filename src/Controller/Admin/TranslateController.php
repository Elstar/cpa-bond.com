<?php

namespace App\Controller\Admin;

use App\Form\Admin\TranslateFormType;
use App\Form\Model\TranslateFormModel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TranslateController extends AbstractController
{
    /**
     * @Route("/admin/translate/{locale}", name="app_admin_translate", defaults={"locale": "uk"}, requirements={"locale": "uk|ru"})
     */
    public function index(
        string $locale,
        ContainerInterface $container,
        TranslateFormModel $formModel,
        Request $request
    ): Response {
        $translatePath = $container->getParameter('translate_path');
        $baseTranslateLanguage = $container->getParameter('base_translate_language');
        $locales = $container->getParameter('locales');

        $fileMessages = $translatePath . '/messages.' . $locale . '.yaml';
        $fileValidatorMessages = $translatePath . '/validators/validators.' . $locale . '.yaml';

        $formModel->locale = $locale;
        $formModel->messages = file_get_contents($fileMessages);
        $formModel->validators = file_get_contents($fileValidatorMessages);

        $translateForm = $this->createForm(TranslateFormType::class, $formModel);
        $translateForm->handleRequest($request);

        if ($translateForm->isSubmitted() && $translateForm->isValid()) {
            /**
             * @var TranslateFormModel $translates
             */
            $translates = $translateForm->getData();

            $handle = fopen($fileMessages, "r+");
            fwrite($handle, $translates->messages);
            fclose($handle);

            $handle = fopen($fileValidatorMessages, "r+");
            fwrite($handle, $translates->validators);
            fclose($handle);

            $this->addFlash('flash_message', 'Translates successfully updated fore locale ' . $locale);
        }


        return $this->render('admin/translate/index.html.twig', [
            'baseTranslateLanguage' => $baseTranslateLanguage,
            'locale' => $locale,
            'locales' => $locales,
            'baseTranslateLanguage' => $baseTranslateLanguage,
            'translateForm' => $translateForm->createView()
        ]);
    }
}
