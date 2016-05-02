<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AlbumController  extends AbstractActionController {
    
    protected $albumTable;
    public function getAlbumTable(){
        if(!$this->albumTable){
            $sm=$this->getServiceLocator();
            $this->albumTable=$sm->get('Album\Model\AlbumTable');    
        }
        return $this->albumTable;
    }
    public function indexAction(){
        $params=array(1,23,34,5);
        $this->getEventManager()->trigger('testEvent', $this, $params);
        return new ViewModel(array(
            'albums' => $this->getAlbumTable()->fetchAll(),
        ));
    }
    public function tesstEvent($baz, $bat = null)
    {
        $params = compact('baz', 'bat');
        $this->getEventManager()->trigger(__FUNCTION__, $this, $params);
        pr($params);die('herer');
    }
}