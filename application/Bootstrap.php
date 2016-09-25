<?php
    /*
     * 启动管理类
     */
    class Bootstrap extends Yaf_Bootstrap_Abstract{
        /*
         * 初始化View
         */
        public function _initView(Yaf_Dispatcher $dispatcher){
            Yaf_Dispatcher::getInstance()->disableView();//关闭其自动渲染
        }
        
        /*
         * 初始化Config
         */
        public function _initConfig() {
            $config = Yaf_Application::app()->getConfig();
            Yaf_Registry::set("config", $config);
            
        }
        
        public function _initDefaultName(Yaf_Dispatcher $dispatcher) {
            //$dispatcher->setDefaultModule("Admin")->setDefaultController("Test")->setDefaultAction("Test");
        }
        
        /*
         * 初始化Session
         */
        public function _initSession($dispatcher) {
            session_start();
        }
    }
?>