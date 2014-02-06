<?php

namespace Wiki\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface; 
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class EditFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('username');
        $builder->remove('email');
        $builder->remove('current_password');
    }

    public function getName()
    {
        return 'wiki_edit_profile';
    }
}
