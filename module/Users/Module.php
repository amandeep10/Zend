<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Users;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Users\Form\RegisterForm;
use Users\Form\LoginForm;
use Users\Form\RegisterFilter;
use Users\Model\User;
use Users\Model\UserTable;
use Users\Model\UploadTable;
use Users\Model\Upload;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;



class Module
{
  
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    public function getServiceConfig() {
		return array (
				'abstract_factories' => array (),
				'aliases' => array (),
				'factories' => array (
						// DB
						'AuthService' => function($sm){
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter,'user','email','password','MD5(?)');
							$authService = new AuthenticationService();
							$authService->setAdapter($dbTableAuthAdapter);
							return $authService;
								
						},
						'UserTable' => function ($sm) {
							$tableGateway = $sm->get ( 'UserTableGateway' );
							$table = new UserTable ( $tableGateway );
							return $table;
						},
						'UserTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype (new User());
							
							return new TableGateway ( 'user', $dbAdapter, null, $resultSetPrototype );
						},
						
						'UploadTable' => function ($sm) {
							$tableGateway = $sm->get ( 'UploadTableGateway' );
							$uploadSharingtableGateway = $sm->get ( 'UploadSharingTableGateway' );
							$table = new UploadTable ( $tableGateway, $uploadSharingtableGateway);
							return $table;
						},
						'UploadTableGateway' => function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							$resultSetPrototype = new ResultSet ();
							$resultSetPrototype->setArrayObjectPrototype ( new Upload () );
							
							return new TableGateway ( 'uploads', $dbAdapter, null, $resultSetPrototype );
						},
						'UploadSharingTableGateway'=> function ($sm) {
							$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
							return new TableGateway('uploads_sharing',$dbAdapter);
						},
						// FORMS
						'LoginForm' => function ($sm) {
							$form = new \Users\Form\LoginForm ();
							$form->setInputFilter ( $sm->get ( 'LoginFilter' ) );
							return $form;
						},
						'RegisterForm' => function ($sm) {
							$form = new \Users\Form\RegisterForm ();
						//	$form->setInputFilter ( $sm->get ( 'RegisterFilter' ) );
							return $form;
						},
						// FILTERS
						'LoginFilter' => function ($sm) {
							return new \Users\Form\LoginFilter ();
						},
						'RegisterFilter' => function ($sm) {
							return new \Users\Form\RegisterFilter ();
						} 
				),
				'invokables' => array (),
				'services' => array (),
				'shared' => array(),
    					);
    }
}
