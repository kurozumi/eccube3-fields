<?php

/*
 * This file is part of the Fields
 *
 * Copyright (C) 2017 項目管理
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Fields\Controller;

use Eccube\Application;
use Symfony\Component\HttpFoundation\Request;

class FieldsController
{

    /**
     * Fields画面
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Application $app, Request $request)
    {

        // add code...

        return $app->render('Fields/Resource/template/index.twig', array(
            // add parameter...
        ));
    }

}
