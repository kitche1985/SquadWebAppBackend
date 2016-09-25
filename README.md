**MingYiAn Squal App Project Backend code**

YAF is our main framework, Any API should be follow standard restful style. 

1. HTTP get method use to query or fetch data from database(resource)

2. HTTP post method use to insert or create data(resource)

3. HTTP put method use to update data(resource)

4. HTTP delete method use to delete data(resource)

For example 


class apiController extends YAF_Abstract_Controller { 
     public function SquadAction() {
        if(Requst.IsPost()) {
            //call method insert a new squad to database
        } else if(Requst.IsPut()) {
            //call method update Squad
        }
     }
}


PDO is the database access class for our project, mysqlnd is the common mysql driver.