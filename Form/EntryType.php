<?php

namespace Brother\ContactUsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntryType extends AbstractType
{
    /**
     * @var string
     */
    private $entryClass;

    /**
     * Constructor
     *
     * @param string $entryClass
     */
    public function __construct($entryClass)
    {
        $this->entryClass = $entryClass;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Имя'))
            ->add('email', null, array('label' => 'E-mail'))
            ->add('phone', null, array('label' => 'Телефон'))
            ->add('message', null, array('label' => 'Сообщение'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array('data_class' => $this->entryClass));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'brother_contact_us_entry';
    }
}
