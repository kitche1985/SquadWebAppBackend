<?php
     /*
     *  API基本抽象类
     */
     abstract class AbstractAPI extends Yaf_Controller_Abstract {
        
        public function init() {
            Yaf_Dispatcher::getInstance()->disableView();
        }
        
        // Set UNIX file folder path
        protected function SetRealPath($MainPath,$folder) {
            $path = substr($MainPath , 0,2);
            return $this->FileServerRoot.$folder.'/'.$path.'/'.$MainPath;
        }

        // Set nginx http file folder path
        protected function SetPath($MainPath,$folder) {
            if(strpos($MainPath, "/"))
            {
                return $MainPath;
            }
            $path = substr($MainPath , 0,2);
            return $this->FileServerPath.$folder.'/'.$path.'/'.$MainPath;
        }


       /*
        * 返回JSON
        * isSuccessed：成功和失败
        * data：数据
        * err: 错误信息
        */
        protected function Response($isSuccessed,$data,$err) {
            if($isSuccessed) {
                $ret = new stdClass();
                $ret->IsSuccess = $isSuccessed;
                $ret->Data = $data;
                $ret->ErrorMessage = '';
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
                header("Cache-Control: no-store, must-revalidate"); 
                header("Pragma: no-cache"); 
                header('Access-Control-Allow-Origin:*');
                header('Access-Control-Allow-Headers:X-Requested-With,accept, origin, content-type');
                echo json_encode($ret);
            } else {
                $ret = new stdClass();
                $ret->IsSuccess = $isSuccessed;
                $ret->Data = null;
                $ret->ErrorMessage = $err;
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
                header("Cache-Control: no-store, must-revalidate"); 
                header("Pragma: no-cache"); 
                header('Access-Control-Allow-Origin:*');
                header('Access-Control-Allow-Headers:X-Requested-With,accept, origin, content-type');
                echo json_encode($ret);
            }
        }
        
        static function myErrorHandler($errno, $errstr, $errfile, $errline) {
            switch ($errno) {
                case YAF_ERR_NOTFOUND_CONTROLLER:
                case YAF_ERR_NOTFOUND_MODULE:
                case YAF_ERR_NOTFOUND_ACTION:
                 header("Not Found");
                 break;
    
            default:
                echo "Unknown error type:[$errfile] at line [$errline]: [$errno],Message: $errstr<br />\n";
                break;
            }
    
            return true;
        }
     }
?>