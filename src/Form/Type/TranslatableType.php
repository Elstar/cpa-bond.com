<?php

namespace App\Form\Type;

use App\Form\Event\Subscriber\Translatable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TranslatableType extends AbstractType
{
    protected EntityManagerInterface $em;
    protected ValidatorInterface $validator;
    protected array $locales;
    protected string $locale;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, array $locales, string $locale)
    {

        $this->em = $em;
        $this->validator = $validator;
        $this->locales = $locales;
        $this->locale = $locale;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (! class_exists($options['personal_translation'])) {
            throw $this->getNoPersonalTranslationException($options['personal_translation']);
        }

        $options['field'] = $options['field'] ?: $builder->getName();

        $builder->addEventSubscriber(
            new Translatable($builder->getFormFactory(), $this->em, $this->validator, $options)
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults($this->getDefaultOptions());
    }

    public function getDefaultOptions(array $options = array()): array
    {
        $options['remove_empty'] = true; // Personal Translations without content are removed
        $options['csrf_protection'] = false;
        $options['personal_translation'] = false; // Personal Translation class
        $options['locales'] = $this->locales; // the locales you wish to edit
        $options['required_locale'] = [$this->locale]; // the required locales cannot be blank
        $options['field'] = false; // the field that you wish to translate
        $options['widget'] = 'text'; // change this to another widget like 'texarea' if needed
        $options['entity_manager_removal'] = true; // auto removes the Personal Translation thru entity manager
        $options['attr'] = [];

        return $options;
    }

    public function getNoPersonalTranslationException($translation): \InvalidArgumentException
    {
        return new \InvalidArgumentException(sprintf('Unable to find personal translation class: "%s"', $translation));
    }

    public function getName(): string
    {
        return 'translatable';
    }
}