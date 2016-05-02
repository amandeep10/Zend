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
use Users\Form\RegisterFilter;
use Zend\Debug\Debug;

class UserManagerController extends AbstractActionController
{
    public function indexAction()
    {
    	$userTable = $this->getServiceLocator()->get('UserTable');
    	Debug::dump();
    	$viewModel = new ViewModel(array('users' => $userTable->fetchAll()));
    	return $viewModel;
    }
    public function editAction()
    {
    	$userTable = $this->getServiceLocator()->get('UserTable');
    	$user = $userTable->getUser($this->params()->fromRoute('id'));
    	$form = $this->getServiceLocator()->get('RegisterForm');
    	$form->bind($user);
    	$viewModel = new ViewModel(array(
    			'form' => $form,
    			'user_id' => $this->params ()->fromRoute ( 'id' ) 
		) );
		return $viewModel;
	}
	public function processEditAction()
	{
		$post = $this->request->getPost ();
		$userTable = $this->getServiceLocator ()->get ( 'UserTable' );
		// Load User entity
		$user = $userTable->getUser ( $post->id );
		// Bind User entity to Form
		
		$form = $this->getServiceLocator ()->get ( 'RegisterForm' );
		$form->bind ( $user );
		$form->setData ( $post );
		if (! $form->isValid ()) {
			$model = new ViewModel ( array (
					'error' => true,
					'form' => $form 
			) );
			$model->setTemplate ( 'users/user-manager/edit' );
			return $model;
		}
		// Save user
		$this->getServiceLocator ()->get ( 'UserTable' )->saveUser ( $user );
		return $this->redirect ()->toRoute ( NULL, array (
				'controller' => 'user-manager',
				'action' => 'index' 
		) );
    }
    public function deleteAction()
    {
    	$this->getServiceLocator ()->get ( 'UserTable' )->deleteUser ( $this->params ()->fromRoute ( 'id' ) );
    	return $this->redirect ()->toRoute ( NULL, array ('controller' => 'user-manager','action' => 'index'	) );
    }
    
}
