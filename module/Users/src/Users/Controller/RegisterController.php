<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Users\Form\RegisterForm;
use Users\Form\LoginForm;
use Users\Form\RegisterFilter;
use Users\Model\User;
use Users\Model\UserTable;

class RegisterController extends AbstractActionController
{
    public function indexAction()
    {
        //$form = new RegisterForm();
        $form = $this->getServiceLocator()->get('RegisterForm');
    	$view = new ViewModel(array('form'=>$form));
    	$view->setTemplate('users/index/new-user');
    	return $view;
    }
    public function registerAction()
    {
    	
    }
    public function loginAction()
    {
    	$view = new ViewModel();
    	$view->setTemplate('users/index/login');
    	return $view;
    }
    public function processAction()
    {
    	if(!$this->request->isPost()){
    		return $this->redirect()->toRoute(NULL ,array( 'controller' => 'register',	'action' => 'index'));
    	}
    	$post = $this->request->getPost();
		$form = new RegisterForm();
		$inputFilter = new RegisterFilter();
		$form->setInputFilter($inputFilter);
		$form->setData($post);
		if (!$form->isValid()) {
			$model = new ViewModel(array(
				'error' => true,
				'form' => $form,
			));
			$model->setTemplate('users/register/index');
			return $model;
		}
    	
    	$this->createUser($form->getData());
    	return $this->redirect()->toRoute(NULL , array(
			'controller' => 'register',
				'action' => 'confirm' 
		));
	}
	protected function createUser(array $data) 
	{
		/* $sm = $this->getServiceLocator ();
		$dbAdapter = $sm->get ( 'Zend\Db\Adapter\Adapter' );
		$resultSetPrototype = new \Zend\Db\ResultSet\ResultSet ();
		$resultSetPrototype->setArrayObjectPrototype ( new \Users\Model\User () );
		$tableGateway = new \Zend\Db\TableGateway\TableGateway ( 'user', $dbAdapter, null, $resultSetPrototype ); */
		
		$user = new User ();
		$user->exchangeArray($data);
		$user->setPassword($data['password']);
    	//$userTable = new UserTable($tableGateway);
		$userTable = $this->getServiceLocator()->get('UserTable');
    	$userTable->saveUser($user);
    	return true;
    }
    public function confirmAction(){
    	$form = new LoginForm();
    	$view = new ViewModel(array('form'=>$form));
    	$view->setTemplate('users/login/index');
    	return $view;
    }
}
