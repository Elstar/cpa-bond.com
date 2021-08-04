<?php

namespace App\Validator;

use App\Repository\CategoryRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;

class UniqueCategoryValidator extends ConstraintValidator
{
    private CategoryRepository $categoryRepository;
    private TranslatorInterface $translator;


    /**
     * UniqueCategoryValidator constructor.
     */
    public function __construct(CategoryRepository $categoryRepository, TranslatorInterface $translator)
    {
        $this->categoryRepository = $categoryRepository;
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint UniqueCategory */

        if (null === $value || '' === $value) {
            return;
        }


        $parent = $this->context->getRoot()->get('parent')->getData();

        if ($this->categoryRepository->findCategoryByNameAndParentId($value, $parent)) {
            $message = $this->translator->trans($constraint->message);
            $this->context->buildViolation($message)
                ->addViolation();
        }
    }
}
