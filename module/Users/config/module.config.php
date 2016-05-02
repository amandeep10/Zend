<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonUser for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Users;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Users\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /user/:controller/:action
            'users' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/users',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Users\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                		'register' => array(
                				'type'    => 'Segment',
                				'options' => array(
                						'route'    => '/register[/:action]',
                						'constraints' => array(
                								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                						),
                						'defaults' => array(
                								'controller'    => 'Users\Controller\Register',
                								'action'        => 'index',
                						),
                				),
                		),
                		'login' => array(
                				'type'    => 'Segment',
                				'options' => array(
                						'route'    => '/login[/:action]',
                						'constraints' => array(
                								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                						),
                						'defaults' => array(
                								'controller'    => 'Users\Controller\Login',
                								'action'        => 'index',
                						),
                				),
                		),
                		'user-manager' => array(
                				'type'    => 'Segment',
                				'options' => array(
                						'route'    => '/user-manager[/:action[/:id]]',
                						'constraints' => array(
                								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                						),
                						'defaults' => array(
                								'controller'    => 'Users\Controller\UserManager',
                								'action'        => 'index',
                								'id'            => '[a-zA-Z0-9_-]*',
                						),
                				),
                		),
                		'group-chat' => array(
                				'type'    => 'Segment',
                				'options' => array(
                						'route'    => '/group-chat[/:action [/:id]]',
                						'constraints' => array(
                								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                						),
                						'defaults' => array(
                								'controller'    => 'Users\Controller\GroupChat',
                								'action'        => 'index',
                								'id'            => '[a-zA-Z0-9_-]*',
                						),
                				),
                		),
                		'upload-manager' => array(
                				'type'    => 'Segment',
                				'options' => array(
                						'route'    => '/upload-manager[/:action]',
                						'constraints' => array(
                								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                						),
                						'defaults' => array(
                								'controller'    => 'Users\Controller\UploadManager',
                								'action'        => 'index',
                						),
                				),
                		),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\Index' => Controller\IndexController::class,
        	'Users\Controller\Register' => Controller\RegisterController::class,
        	'Users\Controller\GroupChat' => Controller\GroupChatController::class,
        	'Users\Controller\UserManager' => Controller\UserManagerController::class,
        	'Users\Controller\UploadManager' => Controller\UploadManagerController::class,
        	'Users\Controller\Login' => Controller\LoginController::class,
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'users/index/index' => __DIR__ . '/../view/users/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
	'module_config' =>array('upload_location' => __DIR__.'/../../../data/uploads'),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
)
;
