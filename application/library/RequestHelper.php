<?php
   /*
    *  Requst 帮助类
    */
    class RequestHelper {
        /*
         * 获取请求数据
         * key：名字
         * type:0 get 1 post 2 put
         */
        public static function GetRequestData($key,$type=1) {
            $request = Yaf_Dispatcher::getInstance()->getRequest();
            if($type == 1) {
                return $request->getPost($key);
            } else if($type == 0) {
                return $request->getParam($key);
            } else if($type == 2) {
                $inputs = file_get_contents('php://input');
                $result = array();
                parse_str($inputs, $result);
                return $result[$key];
            }
        }
		
       /*
        * 是否是 Option 方法
        */
        public static function IsOption(){
        	return Yaf_Dispatcher::getInstance()->getRequest()->isOptions();
        }

       /*
        * 是否是 POST 方法
        */
		public static function IsPost(){
        	return Yaf_Dispatcher::getInstance()->getRequest()->isPost();
        }

        /*
        * 是否是 POST 方法
        */
		public static function IsPut(){
        	return Yaf_Dispatcher::getInstance()->getRequest()->isPut();
        }

       /*
        * 是否是 Get 方法
        */
		public static function IsGet(){
        	return Yaf_Dispatcher::getInstance()->getRequest()->isGet();
        }

       /*
        * 是否是 Delete 方法
        */   
		public static function IsDelete(){
            $isPost = Yaf_Dispatcher::getInstance()->getRequest()->isPost();
            $isGet = Yaf_Dispatcher::getInstance()->getRequest()->isGet();
            $isPut = Yaf_Dispatcher::getInstance()->getRequest()->isPut();

        	return !$isGet && !$isPut && !$isPost;
            //return Yaf_Dispatcher::getInstance()->getRequest()->isDelete();
        }



        /*
         * 获取Http custom head
         */
        public static function GetRequestHeader($key) {
          $heads = getallheaders();
          $ret = '';
          foreach ($heads as $name => $value) {
              if($name == $key) {
                  $ret = $value;
                  break;
              }
          } 
          
          return $ret;;
        }
    }
?>