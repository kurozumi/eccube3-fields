<?php

/*
 * This file is part of the Maker plugin
 *
 * Copyright (C) 2016 LOCKON CO.,LTD. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Fields\Controller\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use Eccube\Application;
use Eccube\Controller\AbstractController;
use Plugin\Fields\Entity\Field;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FieldController.
 */
class FieldController extends AbstractController
{

    /**
     * List, add, edit maker.
     *
     * @param Application $app
     * @param Request     $request
     * @param null        $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Application $app, Request $request, $id = null)
    {
        $Field = new Field();
            
        if($id) {
            $Field = $app['eccube.plugin.fields.repository.field']->find($id);            
        }
        
        if (!$Field) {
            throw new NotFoundHttpException();
        }

        $form = $app['form.factory']
                ->createBuilder('admin_field', $Field)
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $status = $app['eccube.plugin.fields.repository.field']->save($Field);

            if ($status) {
                $app->addSuccess('admin.plugin.field.save.complete', 'admin');

                return $app->redirect($app->url('admin_plugin_field_index'));
            } else {
                $app->addError('admin.plugin.field.save.error', 'admin');
            }
        }

        $arrFields = $app['eccube.plugin.fields.repository.field']->findBy(array(), array('rank' => 'DESC'));

        return $app->render('Fields/Resource/template/admin/Customer/field.twig', array(
                    'form' => $form->createView(),
                    'arrFields' => $arrFields
        ));
    }

    /**
     * Delete Field.
     *
     * @param Application $app
     * @param Request     $request
     * @param int         $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Application $app, Request $request, $id = null)
    {
        // Valid token
        $this->isTokenValid($app);

        // Check request
        if (!'POST' === $request->getMethod()) {
            throw new BadRequestHttpException();
        }

        // Id valid
        if (!$id) {
            $app->addError('admin.plugin.field.not_found', 'admin');

            return $app->redirect($app->url('admin_plugin_field_index'));
        }

        $Field = $app['eccube.plugin.fields.repository.field']->find($id);

        if (!$Field) {
            throw new NotFoundHttpException();
        }

        $status = $app['eccube.plugin.fields.repository.field']->delete($Field);

        if ($status === true) {
            $app->addSuccess('admin.plugin.field.delete.complete', 'admin');
        } else {
            $app->addError('admin.plugin.field.delete.error', 'admin');
        }

        return $app->redirect($app->url('admin_plugin_field_index'));
    }

    /**
     * Move rank with ajax.
     *
     * @param Application $app
     * @param Request     $request
     *
     * @return bool
     */
    public function moveRank(Application $app, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $arrRank = $request->request->all();
            $arrMoved = $app['eccube.plugin.fields.repository.field']->moveMakerRank($arrRank);
        }

        return true;
    }

}
