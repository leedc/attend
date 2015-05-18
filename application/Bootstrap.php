<?php

class Bootstrap extends Yaf_Bootstrap_Abstract{

    public function _initConfig() {
        $config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set("config", $config);
    }

    public function _initDefaultName(Yaf_Dispatcher $dispatcher) {
        $dispatcher->setDefaultModule("Index")->setDefaultController("Index")->setDefaultAction("index");
    }

    public function  _initPlugin ( Yaf_Dispatcher $dispatcher ) {
        //register a plugin
        $dispatcher -> registerPlugin (new  UserPlugin ());
    }

    public function _initRoute(Yaf_Dispatcher $dispatcher) {
        //init route
        $router = Yaf_Dispatcher::getInstance()->getRouter();
        $router->addConfig(Yaf_Registry::get("config")->routes);
    }
    public function _initSession($dispatcher)
    {
        //Open Session
        Yaf_Session::getInstance()->start();
    }
    public function _initLoad($dispatcher)
    {
        $loader = Yaf_Loader::getInstance();
        $loader->registerLocalNamespace(array('com'));
    }

}