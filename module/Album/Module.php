<?php
namespace Album;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;


use Zend\EventManager\EventInterface as Event;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Zend\Db\TableGateway\TableGateway;


class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    
    /*
     * init method is called before bootstrap method. The init() method is called for every module implementing this feature, on every page request, and 
     * should only be used for performing lightweight tasks such as registering event listeners.
     * 
     */
    public function init(ModuleManager $moduleManager)
    {
        // Remember to keep the init() method as lightweight as possible
        $events = $moduleManager->getEventManager();
        $events->attach('loadModules.post', array($this,'modulesLoaded')); // this function is called after all modules has been loaded
    }
    public function modulesLoaded(Event $e)
    {
        // This method is called once all modules are loaded.
        $moduleManager = $e->getTarget();
        $loadedModules = $moduleManager->getLoadedModules();
        // To get the configuration from another module named 'FooModule'
       // $config = $moduleManager->getModule('FooModule')->getConfig();
    }
    /*
    * The bootstrap event is triggered after the “loadModule.post” event, once $application->bootstrap() is called.  
    * your onBootstrap() method will be called once the getConfig() and getAutoloaderConfig() for all modules have been run and 
    * the essential MVC objects for the DI, routing, dispatching and view have all been created.
    */
   /*  public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
    } */
    public function onBootstrap(Event $e)
    {
        //'Album\Controller\IndexController',
        $eventManager = $e->getTarget()->getEventManager();
        $em             = $e->getTarget()->getServiceManager();
        
        $eventManager->attach('route', function($e) {
            echo 'executed on route process';
        });
        $eventManager->attach('testEvent',function($e){
            echo "trigger has fired";
            $event  = $e->getName();
            $target = get_class($e->getTarget());
            $params = json_encode($e->getParams());
            
            pr($params);echo 'adsdd';die('dsaadas');
        });
    }
    public function getConfig()
    {
            return include __DIR__ . '/config/module.config.php';
    }
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
    public function getServiceConfig(){
        return array(
            'factories' => array(
                'Album\Model\AlbumTable' => function($sm){
                    $tableGateway = $sm->get('AlbumTableGateway');
                    $table= new AlbumTable($tableGateway);
                    return $table;
                },
                'AlbumTableGateway' => function ($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype= new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Album());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
                }
            )      
        );
    }
}