<?php

namespace Wiki\IndexBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('title' , 'text' , array('label' => 'Titre',
										   'attr' => array('placeholder' => 'Sans titre')))
			->add('content' , 'textarea' , array('label' => 'Contenu',
												 'attr' => array('rows' => 20 , 'data-toggle' => 'markdown')))
		;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Wiki\IndexBundle\Entity\Article',
		));
	}

	public function getName()
	{
		return 'wiki_article_type';
	}
}
