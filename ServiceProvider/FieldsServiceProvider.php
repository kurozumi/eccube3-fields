<?php

/*
 * This file is part of the Fields
 *
 * Copyright (C) 2017 項目管理
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Fields\ServiceProvider;

use Eccube\Application;
use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Plugin\Fields\Form\Type\FieldsConfigType;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;
use Symfony\Component\Yaml\Yaml;
use Plugin\Fields\Form\Type\FieldType;

class FieldsServiceProvider implements ServiceProviderInterface
{

    public function register(BaseApplication $app)
    {
        // プラグイン用設定画面
        $app->match('/'.$app['config']['admin_route'].'/plugin/Fields/config', 'Plugin\Fields\Controller\ConfigController::index')->bind('plugin_Fields_config');

        // 独自コントローラ
        $app->match('/plugin/[lower_code]/hello', 'Plugin\Fields\Controller\FieldsController::index')->bind('plugin_Fields_hello');

        // 一覧・登録・修正
        $app->match('/'.$app['config']['admin_route'].'/plugin/field/{id}', '\\Plugin\\Fields\\Controller\\Admin\\FieldController::index')
            ->value('id', null)->assert('id', '\d+|')
            ->bind('admin_plugin_field_index');
        
        // 削除
        $app->delete('/'.$app['config']['admin_route'].'/plugin/field/{id}/delete', '\\Plugin\\Fields\\Controller\\Admin\\FieldController::delete')
            ->value('id', null)->assert('id', '\d+|')
            ->bind('admin_plugin_field_delete');

        $app->post('/'.$app['config']['admin_route'].'/plugin/field/rank/move', '\\Plugin\\Fields\\Controller\\Admin\\FieldController::moveRank')
            ->bind('admin_plugin_field_move_rank');
        
        // Form
        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new FieldsConfigType();

            return $types;
        }));
        
        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new FieldType($app);

            return $types;
        }));

        // Repository
        $app['eccube.plugin.fields.repository.field'] = $app->share(function () use ($app) {
            return $app['orm.em']->getRepository('Plugin\Fields\Entity\Field');
        });
        
        $app['eccube.plugin.fields.repository.customer_field'] = $app->share(function () use ($app) {
            return $app['orm.em']->getRepository('Plugin\Fields\Entity\CustomerField');
        });

        // Service

        // メニュー登録
        $app['config'] = $app->share($app->extend('config', function ($config) {
            $addNavi['id'] = 'field';
            $addNavi['name'] = '項目管理';
            $addNavi['url'] = 'admin_plugin_field_index';

            $nav = $config['nav'];
            foreach ($nav as $key => $val) {
                if ('customer' == $val['id']) {
                    $nav[$key]['child'][] = $addNavi;
                }
            }

            $config['nav'] = $nav;

            return $config;
        }));
        
        // メッセージ登録
        $file = __DIR__ . '/../Resource/locale/message.' . $app['locale'] . '.yml';
        $app['translator']->addResource('yaml', $file, $app['locale']);

        // load config
        // プラグイン独自の定数はconfig.ymlの「const」パラメータに対して定義し、$app['[lower_code]config']['定数名']で利用可能
        // if (isset($app['config']['Fields']['const'])) {
        //     $config = $app['config'];
        //     $app['[lower_code]config'] = $app->share(function () use ($config) {
        //         return $config['Fields']['const'];
        //     });
        // }

        // ログファイル設定
        $app['monolog.logger.[lower_code]'] = $app->share(function ($app) {

            $logger = new $app['monolog.logger.class']('[lower_code]');

            $filename = $app['config']['root_dir'].'/app/log/[lower_code].log';
            $RotateHandler = new RotatingFileHandler($filename, $app['config']['log']['max_files'], Logger::INFO);
            $RotateHandler->setFilenameFormat(
                '[lower_code]_{date}',
                'Y-m-d'
            );

            $logger->pushHandler(
                new FingersCrossedHandler(
                    $RotateHandler,
                    new ErrorLevelActivationStrategy(Logger::ERROR),
                    0,
                    true,
                    true,
                    Logger::INFO
                )
            );

            return $logger;
        });

    }

    public function boot(BaseApplication $app)
    {
    }

}
