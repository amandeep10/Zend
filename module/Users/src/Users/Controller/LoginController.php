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
use Users\Form\LoginForm;
use Users\Form\LoginFilter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Mvc\View\Console\ViewManager;

class LoginController extends AbstractActionController {
	protected $storage;
	protected $authservice;
	
	public function getAuthService() {
		if (! $this->authservice) {
			$dbAdapter = $this->getServiceLocator ()->get('Zend\Db\Adapter\Adapter');
			$dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'user', 'email', 'password', 'MD5(?)');
			$authService = new AuthenticationService();
			$authService->setAdapter($dbTableAuthAdapter);
			$this->authservice = $authService;
		}
		return $this->authservice;
	}
	public function indexAction()
    {
        //$form = new LoginForm();
        $form = $this->getServiceLocator()->get('LoginForm');
    	$view = new ViewModel(array('form'=>$form));
    	$view->setTemplate('users/login/login');
    	return $view;
    }
    public function registerAction()
    {
    	//$form = new RegisterForm();
    	$form = $this->getServiceLocator()->get('RegisterForm');
    	$view = new ViewModel(array('form'=>$form));
    	$view->setTemplate('users/index/new-user');
    	return $view;
    	
    }
    public function loginAction()
    {
    	$view = new ViewModel();
    	$view->setTemplate('users/index/login');
    	return $view;
    }
    public function processAction()
    {
    	$this->getAuthService()->getAdapter()
    							->setIdentity($this->request->getPost('email'))
    							->setCredential($this->request->getPost('password'));
		$result = $this->getAuthService ()->authenticate ();
		if ($result->isValid ()) {
    		$this->getAuthService()->getStorage()->write($this->request->getPost('email'));
			return $this->redirect ()->toRoute ( NULL, array (
					'controller' => 'login',
					'action' => 'confirm' 
			) );
		}else{
			$model =new ViewModel(array('error' => true, 'form' => $form));
			return $model->setTemplate('users/login/index');
		}
    }
    public function confirmAction(){
		$user_email = $this->getAuthService ()->getStorage ()->read ();
		$viewModel = new ViewModel ( array (
				'user_email' => $user_email 
		) );
		return $viewModel;
    }
}
