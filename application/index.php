<?php
    define ('ROOT_PATH',dirname(__FILE__));
    define ("APPLICATION_PATH", dirname(__FILE__));
    $application = new Yaf_Application(ROOT_PATH . "/../conf/application.ini");
    $response = $application
        ->bootstrap() /* init custom view in bootstrap */
        ->run();
?>