**MingYiAn Squal App Project Backend code**

YAF is our main framework, Any API should be follow standard restful style. 

1. HTTP get method use to query or fetch data from database(resource)

2. HTTP post method use to insert or create data(resource)

3. HTTP put method use to update data(resource)

4. HTTP delete method use to delete data(resource)

For example 


class apiController extends YAF_Abstract_Controller { 

     public function SquadAction() { 
     
        if(RequestHelper::IsPost()) { 
            
            //call SquadModel's insert  inser tmethod  to create  a new squad  
            
            
        } else if(RequestHelper::IsPut()) { 
        
            //call SquadModel's  update method to delete the Squad 
            
        } else if(RequestHelper::IsDelete()) { 
        
             //call SquadModel's  Delete method to delete the Squad 
             
            
        
        } else if(RequestHelper::IsGet()) { 
        
             //call SquadModel's  Delete method to fetch the Squad by condition 
             
        }
        
        
     }
     
 
 


PDO is the database access class for our project, mysqlnd is the common mysql driver.