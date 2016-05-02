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
use Users\Form\UploadForm;
use Users\Model\Upload;

class UploadManagerController extends AbstractActionController
{
	protected $storage;
	protected $authservice;
	
    public function indexAction()
    {
       /*  $form = new UploadForm();
    	$view = new ViewModel ( array (
				'form' => $form 
		) );
		return $view; */
		
		$uploadTable = $this->getServiceLocator ()->get( 'UploadTable' );
		$userTable = $this->getServiceLocator ()->get('UserTable' );
		// Get User Info from Session
		$userEmail = $this->getAuthService ()->getStorage ()->read ();
		$userEmail="aman@aman.com";
		$user = $userTable->getUserByEmail ( $userEmail );
		$form = new UploadForm();
		$viewModel = new ViewModel ( array (
				'myUploads' => $uploadTable->getUploadsByUserId($user->id),
    	));
    	return $viewModel;
    }
    public function uploadAction(){
    	$form = new UploadForm();
    	$view = new ViewModel ( array (
    			'form' => $form
    	) );
    	return $view;
    }
   
    public function getAuthService()
    {
    	if (! $this->authservice) {
    		$this->authservice = $this->getServiceLocator()->get('AuthService');
    	}
    	return $this->authservice;
    }
    public function getFileUploadLocation()
    {
    	// Fetch Configuration from Module Config
    	$config = $this->getServiceLocator()->get('config');
    	return $config['module_config']['upload_location'];
    }
    public function sendOfflineMessageAction()
    {
    
    }
    public function messageListAction()
    {
    
    }
    public function refreshAction()
    {
    
    }
    public function processUploadAction(){
    	//print_r( $this->request->getPost ());die;
    	$form = new UploadForm();
		$uploadFile = $this->params ()->fromFiles( 'fileupload' );
		$form->setData ($this->request->getPost () );
		if ($form->isValid ()) {
			// Fetch Configuration from Module Config
			$uploadPath = $this->getFileUploadLocation ();
			//echo $uploadPath;die;
			// Save Uploaded file
			$adapter = new \Zend\File\Transfer\Adapter\Http ();
			$adapter->setDestination ( $uploadPath );
			if ($adapter->receive ( $uploadFile ['name'] )) {
				// File upload sucessfull
				$exchange_data = array ();
				$exchange_data ['label'] = $this->request->getPost ()->get ( 'label' );
				$exchange_data ['filename'] = $uploadFile ['name'];
				$exchange_data ['user_id'] = $user->id;
				$upload= new Upload();
				$upload->exchangeArray ( $exchange_data );
				$uploadTable = $this->getServiceLocator ()->get ( 'UploadTable' );
				$uploadTable->saveUpload ( $upload );
				return $this->redirect ()->toRoute ( 'users/upload-manager', array (
						'action' => 'index' 
				) );
			}
		}
    }
}
