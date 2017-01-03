<?php
/*
 * This file is part of the Maker plugin
 *
 * Copyright (C) 2016 LOCKON CO.,LTD. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Fields\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class FieldType.
 */
class FieldType extends AbstractType
{
    private $app;

    /**
     * ItemsType constructor.
     *
     * @param \Silex\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Build config type form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => '項目',
                'required' => true,
                'constraints' => array(
                    new Assert\NotBlank(array('message' => $this->app->trans('admin.plugin.field.blank.error'))),
                ),
            ))
            ->add('id', 'hidden', array());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_field';
    }
}
