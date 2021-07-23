<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;

class UniqueUserValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * UniqueUserValidator constructor.
     */
    public function __construct(UserRepository $userRepository, TranslatorInterface $translator)
    {
        $this->userRepository = $userRepository;
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint UniqueUser */

        if (null === $value || '' === $value) {
            return;
        }

        if (! $this->userRepository->findOneBy(['email' => $value])) {
            return;
        }

        $message = $this->translator->trans($constraint->message);
        $this->context->buildViolation($message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
