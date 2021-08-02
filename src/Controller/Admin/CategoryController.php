<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\Admin\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Translatable\Entity\Translation;

class CategoryController extends AbstractController
{
    /**
     * @Route({"uk": "/admin/category/uk/{id}", "ru": "/admin/category/ru/{id}", "en": "/admin/category/en/{id}"}, name="app_admin_category", defaults={"id": 0})
     */
    public function index(
        ?Category $category,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $em
    ): Response {
        $childCategories = $categoryRepository->findChildCategories($category);

        return $this->render('admin/category/index.html.twig', [
            'category' => $category,
            'childCategories' => $childCategories
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
        EntityManagerInterface $em
    ): Response {
        $newCategory = new Category();
        if (!empty($category)) {
            $newCategory->setParentId($category->getId());
        } else {
            $newCategory->setParentId(0);
        }

        $categoryForm = $this->createForm(CategoryFormType::class, $newCategory);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            /**
             * @var Category $newCategory
             */
            $newCategory = $categoryForm->getData();
            $em->persist($newCategory);
            $em->flush();
        }
        return $this->render('admin/category/create.html.twig', [
            'category' => $category,
            'categoryForm' => $categoryForm->createView()
        ]);
    }
}
