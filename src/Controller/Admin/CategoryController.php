<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Form\Admin\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route({"uk": "/admin/category/uk/{id}", "ru": "/admin/category/ru/{id}", "en": "/admin/category/en/{id}"}, name="app_admin_category", defaults={"id": 0})
     */
    public function index(
        ?Category $category,
        CategoryRepository $categoryRepository
    ): Response {
        $childCategories = $categoryRepository->findChildCategories($category);

        $tree = null;
        if ($category)
            $tree = $categoryRepository->getPath($category);

        return $this->render('admin/category/index.html.twig', [
            'category' => $category,
            'tree' => $tree,
            'childCategories' => $childCategories,
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
        CategoryRepository $categoryRepository
    ): Response {
        $tree = null;
        if ($category)
            $tree = $categoryRepository->getPath($category);

        $newCategory = new Category();
        if (empty($category)) {
            $newCategory->setTranslatableLocale("uk");
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
        if ($category)
            $tree = $categoryRepository->getPath($category);

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
}
