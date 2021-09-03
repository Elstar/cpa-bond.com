<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Landing;
use App\Entity\PreLanding;
use App\Entity\PreLandingPage;
use App\Form\Admin\CategoryFormType;
use App\Form\Admin\PreLandingFormType;
use App\Form\Admin\LandingFormType;
use App\Form\Admin\PreLandingPageFormType;
use App\Repository\CategoryRepository;
use App\Repository\LandingRepository;
use App\Repository\PreLandingPageRepository;
use App\Repository\PreLandingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route({"uk": "/admin/category/uk/{id}", "ru": "/admin/category/ru/{id}", "en": "/admin/category/en/{id}"}, name="app_admin_category", defaults={"id": 0})
     */
    public function index(
        ?Category $category,
        CategoryRepository $categoryRepository,
        PreLandingRepository $preLandingRepository,
        LandingRepository $landingRepository,
        PreLandingPageRepository $preLandingPageRepository
    ): Response {
        $childCategories = $categoryRepository->findChildCategories($category);

        $tree = null;
        if ($category) {
            $tree = $categoryRepository->getPath($category);
        }

        $preLandings = $preLandingRepository->findBy(['category' => $category]);
        $landings = $landingRepository->findBy(['category' => $category]);
        $preLandingPages = $preLandingPageRepository->findBy(['category' => $category]);

        return $this->render('admin/category/index.html.twig', [
            'category' => $category,
            'tree' => $tree,
            'childCategories' => $childCategories,
            'preLandings' => $preLandings,
            'landings' => $landings,
            'preLandingPages' => $preLandingPages,
            'surf' => 1
        ]);
    }

    /**
     * @Route(
     *     {"uk": "/admin/category/uk/{id}/create", "ru": "/admin/category/ru/{id}/create", "en": "/admin/category/en/{id}/create"},
     *     name="app_admin_category_create",
     *     defaults={"id": 0}
     *     )
     */
    public function create(
        ?Category $category,
        Request $request,
        EntityManagerInterface $em,
        CategoryRepository $categoryRepository,
        ContainerInterface $container
    ): Response {
        $tree = null;
        if ($category) {
            $tree = $categoryRepository->getPath($category);
        }

        $newCategory = new Category();
        if (empty($category)) {
            $newCategory->setTranslatableLocale($container->getParameter('locale'));
        } else {
            $newCategory->setParent($category);
        }

        $categoryForm = $this->createForm(CategoryFormType::class, $newCategory);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            /**
             * @var Category $newCategory
             */
            $newCategory = $categoryForm->getData();
            $newCategory->setParent($category);
            $em->persist($newCategory);
            $em->flush();
        }

        return $this->render('admin/category/create.html.twig', [
            'category' => $category,
            'tree' => $tree,
            'categoryForm' => $categoryForm->createView(),
            'surf' => 0
        ]);
    }

    /**
     * @Route(
     *     {"uk": "/admin/category/uk/{id}/edit", "ru": "/admin/category/ru/{id}/edit", "en": "/admin/category/en/{id}/edit"},
     *     name="app_admin_category_edit"
     *     )
     */
    public function edit(
        Category $category,
        Request $request,
        EntityManagerInterface $em,
        CategoryRepository $categoryRepository
    ): Response {
        $tree = null;
        if ($category) {
            $tree = $categoryRepository->getPath($category);
        }

        if (!$category->getLocale()) {
            $category->setTranslatableLocale($request->getLocale());
        }

        $categoryForm = $this->createForm(CategoryFormType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            /**
             * @var Category $category
             */

            $category = $categoryForm->getData();
            $category->setTranslatableLocale($category->getLocale());
            $em->persist($category);
            $em->flush();
        }

        return $this->render('admin/category/create.html.twig', [
            'category' => $category,
            'tree' => $tree,
            'categoryForm' => $categoryForm->createView(),
            'surf' => 0
        ]);
    }

    /**
     *
     * @Route(
     *     {"uk": "/admin/category/uk/{id}/create_platform/{platform}", "ru": "/admin/category/ru/{id}/create_platform/{platform}", "en": "/admin/category/en/{id}/create_platform/{platform}"},
     *     name="app_admin_category_platform_create",
     *     requirements={
     *          "platform": "pre_landing|landing|pre_landing_page"
     *     }
     * )
     */
    public function createPlatform(
        Category $category,
        string $platform,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $tree = $categoryRepository->getPath($category);

        if ($platform == 'pre_landing') {
            $platform = new PreLanding();
        } elseif ($platform == 'landing') {
            $platform = new Landing();
        } elseif ($platform == 'pre_landing_page') {
            $platform = new PreLandingPage();
        }

        $platformType = str_replace("Entity", "Form\Admin", get_class($platform));

        $platform->setCategory($category);
        $platformForm = $this->createForm($platformType . 'FormType', $platform);

        $platformType = explode('\\', $platformType);
        $platformType = (string)$platformType[array_key_last($platformType)];

        $platformForm->handleRequest($request);
        if ($platformForm->isSubmitted() && $platformForm->isValid()) {

            $platform = $platformForm->getData();
            $platform->setCr(0);

            $em->persist($platform);
            $em->flush();

            $this->addFlash('flash_message', "{$platformType} added successfully");
        }

        return $this->render('admin/category/platform.create.html.twig', [
            'category' => $category,
            'tree' => $tree,
            'platformForm' => $platformForm->createView(),
            'surf' => 0,
            'platformType' => $platformType
        ]);
    }

    /**
     *
     * @Route(
     *     {"uk": "/admin/category/uk/{id}/create_platform/{platform}/{platformId}", "ru": "/admin/category/ru/{id}/create_platform/{platform}", "en": "/admin/category/en/{id}/create_platform/{platform}"},
     *     name="app_admin_category_platform_edit",
     *     requirements={
     *          "platform": "pre_landing|landing|pre_landing_page"
     *     }
     * )
     */
    public function editPlatform(
        Category $category,
        string $platform,
        int $platformId,
        CategoryRepository $categoryRepository,
        PreLandingRepository $preLandingRepository,
        LandingRepository $landingRepository,
        PreLandingPageRepository $preLandingPageRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $tree = $categoryRepository->getPath($category);

        if ($platform == 'pre_landing') {
            $platform = $preLandingRepository->findOneBy(['id' => $platformId]);
        } elseif ($platform == 'landing') {
            $platform = $landingRepository->findOneBy(['id' => $platformId]);
        } elseif ($platform == 'pre_landing_page') {
            $platform = $preLandingPageRepository->findOneBy(['id' => $platformId]);
        }

        $platformType = str_replace("Entity", "Form\Admin", get_class($platform));

        $platformForm = $this->createForm($platformType . 'FormType', $platform);

        $platformType = explode('\\', $platformType);
        $platformType = (string)$platformType[array_key_last($platformType)];

        $platformForm->handleRequest($request);
        if ($platformForm->isSubmitted() && $platformForm->isValid()) {

            $platform = $platformForm->getData();
            $platform->setCr(0);

            $em->persist($platform);
            $em->flush();

            $this->addFlash('flash_message', "{$platformType} edited successfully");
        }

        return $this->render('admin/category/platform.create.html.twig', [
            'category' => $category,
            'tree' => $tree,
            'platformForm' => $platformForm->createView(),
            'surf' => 0,
            'platformType' => $platformType
        ]);
    }
}
