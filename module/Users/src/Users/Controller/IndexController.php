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

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    public function registerAction()
    {
    	$form = new RegisterForm();
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
    	if($this->request->isPost()){
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
				$model->setTemplate('users/index/new-user');
				return $model;
			}
    	}else{
    		return $this->redirect()->toRoute(NULL ,
			array( 'controller' => 'index',
			'action' => 'index'
			));
    	}
    	return $this->redirect()->toRoute(NULL , array(
			'controller' => 'index',
			'action' => 'confirm'
		));
    }
    public function confirmAction(){
    	$view = new ViewModel();
    	$view->setTemplate('users/index/login');
    	return $view;
    }
}
