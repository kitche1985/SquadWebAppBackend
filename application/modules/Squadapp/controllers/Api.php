<?php
   /*
    *  API Controller
    */
    class ApiController extends AbstractAPI {
       /*
        * Example 
        */
        public function SquadAction() {
            if(RequestHelper::IsGet()) {
               echo 'get:' . 'Key=' . RequestHelper::GetRequestData('Key',0);
            } else if(RequestHelper::IsPost()) {
                echo 'post:' . 'Key=' . RequestHelper::GetRequestData('Key');
            } else if(RequestHelper::IsPut()) {
                echo 'put:' . 'Key=' . RequestHelper::GetRequestData('Key',2);
            } else if(RequestHelper::IsDelete()) {
                echo 'delete:' . 'Key=' . RequestHelper::GetRequestData('Key',0);
            }
        }
    }
?>