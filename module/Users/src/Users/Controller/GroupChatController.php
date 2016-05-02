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

class GroupChatController extends AbstractActionController
{
    public function indexAction()
    {
    	$view = new ViewModel();
    	return $view;
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
}
