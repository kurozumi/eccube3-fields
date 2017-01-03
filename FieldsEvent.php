<?php

/*
 * This file is part of the Fields
 *
 * Copyright (C) 2017 項目管理
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Fields;

use Eccube\Application;
use Eccube\Event\EventArgs;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Plugin\Fields\Entity\Field;
use Plugin\Fields\Entity\CustomerField;

class FieldsEvent
{

    /** @var  \Eccube\Application $app */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function onCustomerEditIndexInitialize(EventArgs $event)
    {
        $Customer = $event->getArgument('Customer');

        if ($Customer->getId()) {
            $Fields = $this->app['eccube.plugin.fields.repository.field']->findCustomerFields($Customer);
        } else {
            $Fields = $this->app['eccube.plugin.fields.repository.field']->findAll();
        }

        $builder = $event->getArgument('builder');

        foreach ($Fields as $Field)
        {
            $data = isset($Field["text"]) ? $Field["text"] : "";
            $Field = isset($Field[0]) ? $Field[0] : $Field;

            $builder->add($Field->getFieldName(), 'text', array(
                'required' => false,
                'label' => $Field->getName(),
                'mapped' => false,
                'data' => $data
            ));
        }
    }

    public function onCustomerEditIndexComplete(EventArgs $event)
    {
        $form = $event->getArgument('form');

        $Customer = $event->getArgument('Customer');

        $Fields = $this->app['eccube.plugin.fields.repository.field']->findAll();

        if ($Fields) {
            foreach ($Fields as $Field)
            {
                $CustomerField = $this->app['eccube.plugin.fields.repository.customer_field']->findOneBy(array(
                    "customer_id" => $Customer,
                    "field_id" => $Field
                ));

                if (!$CustomerField)
                    $CustomerField = new CustomerField();

                // エンティティを更新
                $CustomerField
                        ->setCustomer($Customer)
                        ->setField($Field)
                        ->setText($form[$Field->getFieldName()]->getData());

                // DB更新
                $this->app['orm.em']->persist($CustomerField);
            }
            $this->app['orm.em']->flush();
        }
    }

}
