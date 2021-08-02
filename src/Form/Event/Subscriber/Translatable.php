<?php

namespace App\Form\Event\Subscriber;

use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Translatable implements EventSubscriberInterface
{

    protected FormFactoryInterface $factory;
    protected EntityManagerInterface $em;
    protected ValidatorInterface $validator;
    protected array $options;

    public function __construct(
        FormFactoryInterface $factory,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        array $options
    ) {
        $this->factory = $factory;
        $this->em = $em;
        $this->validator = $validator;
        $this->options = $options;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::POST_SUBMIT => 'postSubmit',
            FormEvents::SUBMIT => 'submit'
        ];
    }

    private function bindTranslations($data)
    {
        // Small helper function to extract all Personal Translation
        // from the Entity for the field we are interested in
        // and combines it with the fields
        $collection = [];
        $available_translations = [];
        foreach ($data as $translation) {
            /* @var $translation AbstractPersonalTranslation */
            if (is_object($translation) &&
                strtolower($translation->getField()) == strtolower($this->options['field'])
            ) {
                $available_translations[strtolower($translation->getLocale())] = $translation;
            }
        }

        foreach ($this->getFieldNames() as $locale => $field_name) {
            if (isset($available_translations[strtolower($locale)])) {
                $translation = $available_translations[strtolower($locale)];
            } else {
                $translation = $this->createPersonalTranslation($locale, $this->options['field'], null);
            }

            $collection[] = [
                'locale' => $locale,
                'fieldName' => $field_name,
                'translation' => $translation,
            ];
        }

        return $collection;
    }

    private function getFieldNames(): array
    {
        //helper function to generate all field names in format:
        // '<locale>' => '<field>:<locale>'
        $collection = [];
        foreach ($this->options['locales'] as $locale) {
            $collection[$locale] = $this->options['field'] . ':' . $locale;
        }
        return $collection;
    }

    private function createPersonalTranslation($locale, $field, $content)
    {
        // creates a new Personal Translation
        $class_name = $this->options['personal_translation'];
        return new $class_name($locale, $field, $content);
    }

    public function submit(FormEvent $event)
    {
        // Validates the submitted form
        $form = $event->getForm();

        foreach ($this->getFieldNames() as $locale => $field_name) {
            $content = $form->get($field_name)->getData();
            if (null === $content && in_array($locale, $this->options['required_locale'])) {
                $form->addError($this->getCannotBeBlankException($this->options['field'], $locale));
            } else {
                $errors = $this->validator->validate(
                    $this->createPersonalTranslation($locale, $field_name, $content),
                    [sprintf('%s:%s', $this->options['field'], $locale)]
                );
                foreach ($errors as $error) {
                    $form->addError(new FormError($error->getMessage()));
                }
            }
        }
    }

    public function getCannotBeBlankException($field, $locale): FormError
    {
        return new FormError(sprintf('Field "%s" for locale "%s" cannot be blank', $field, $locale));
    }

    public function postSubmit(FormEvent $event)
    {
        // if the form passed the validattion then set the corresponding Personal Translations
        $form = $event->getForm();
        $data = $form->getData();
        $entity = $form->getParent()->getData();
        if (empty($entity)) {
            $class_name = str_replace('Translation','',$this->options['personal_translation']);
            $entity = new $class_name();
        }
        foreach ($this->bindTranslations($data) as $binded) {
            $content = $form->get($binded['fieldName'])->getData();
            /* @var $translation AbstractPersonalTranslation */
            $translation = $binded['translation'];
            // set the submitted content
            $translation->setContent($content);
            // test if its new
            if ($translation->getId()) {
                //Delete the Personal Translation if its empty
                if (null === $content && $this->options['remove_empty']) {
                    $data->removeElement($translation);
                    if ($this->options['entity_manager_removal']) {
                        $this->em->remove($translation);
                    }
                }
            } elseif (null !== $content) {
                // add it to entity
                $entity->addTranslation($translation);
                if (!$data->contains($translation)) {
                    $data->add($translation);
                }
            }
        }

        // remove string elements from "translations", we need only objects
        foreach ($data as $rec) {
            if (!is_object($rec)) {
                $data->removeElement($rec);
            }
        }
    }

    public function preSetData(FormEvent $event)
    {
        // Builds the custom 'form' based on the provided locales
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. We're only concerned with when
        // setData is called with an actual Entity object in it (whether new,
        // or fetched with Doctrine). This if statement let's us skip right
        // over the null condition.
        if (null === $data) {
            foreach ($this->options['locales'] as $locale) {

                $class_name = $this->options['personal_translation'];

                $translation = new $class_name($locale, $this->options['field'], null);

                $form->add(
                    $this->factory->createNamed(
                        $this->options['field'].':'.$locale,
                        $this->options['widget'],
                        $translation->getContent(),
                        [
                            'auto_initialize' => false,
                            'label' => $locale,
                            'required' => in_array($locale, $this->options['required_locale']),
                            'property_path' => null,
                            'attr' => $this->options['attr']
                        ]
                    )
                );
            }
            return;
        }

        foreach ($this->bindTranslations($data) as $binded) {
            /* @var $translation AbstractPersonalTranslation */
            $translation = $binded['translation'];

            $form->add($this->factory->createNamed(
                $binded['fieldName'],
                $this->options['widget'],
                $translation->getContent(),
                [
                    'auto_initialize' => false,
                    'label' => $binded['locale'],
                    'required' => in_array($binded['locale'], $this->options['required_locale']),
                    'property_path' => null,
                    'attr' => $this->options['attr']
                ]
            ));
        }
    }
}