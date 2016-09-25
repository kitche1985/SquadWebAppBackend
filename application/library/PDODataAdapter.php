<?php
   /*
    *  数据库访问类
    */
    class PDODataAdapter {
    // Database Link object
    private $conn = null;
    private $isTransaction;
    public $errorInfo;
    // Construct function 
   
    public function __construct($dbconfig) {
        $host = $dbconfig['hostname'];      
        $dbname = $dbconfig['dbname'];
        $user = $dbconfig['username'];
        $pwd = $dbconfig['password'];
        $dsn = "mysql:dbname=$dbname;host=$host";
        $params = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");
        $this->conn = new PDO($dsn, $user, $pwd,$params);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
   /*
    *  开始一个事务
    */
    public function BeginTransaction() {
        $this->isTransaction = true;
        $ret = $this->conn->beginTransaction();
    }

   /*
    *  提交一个事务
    */
    public function Commit() {
        $this->conn->commit();
        $this->isTransaction = false;
    }
    
   /*
    *  回滚一个事务
    */
    public function RollBack() {
        $this->conn->rollBack();
        $this->isTransaction = false;
    }
    
    
    
    // Execute query and return an array. 
    public function ExecuteQuery($sqlstr) {
        return $this->conn->query($sqlstr)->fetchAll();
    }
    
    /*
     *  执行Prepare SQL，返回成功失败
     */
    public function ExecutePrepare($sqlstr,$param) {
        $sth = $this->conn->prepare($sqlstr);
        if (!is_array($param)) {
             $param = array($param);
        }
        $status = $sth->execute($param);
        if (!$status) {
            if($this->isTransaction) {
                $this->RollBack();
                $this->errorInfo = print_r($sth->errorInfo(),true);
                return false;
            } else {
                $this->errorInfo = print_r($sth->errorInfo(),true);
                return false;
            }
        } else {
            return true;
        }
    }
    

    /*
     *  执行Prepare SQL，返回Last Insert ID
     */
    public function ExecutePrepareReturnLastID($sqlstr,$param) {
        $sth = $this->conn->prepare($sqlstr);
        if (!is_array($param)) {
             $param = array($param);
        }
        $status = $sth->execute($param);
        if (!$status) {
            if($this->isTransaction) {
                $this->RollBack();
                $this->errorInfo = print_r($sth->errorInfo(),true);
                return -1;
            } else {
                $this->errorInfo = print_r($sth->errorInfo(),true);
                return -1;
            }
        } else {
            return $this->conn->lastInsertId();
        }
    }
    
    /*
     *  执行Prepare SQL，返回Array
     */
    public function ExecutePrepareQuery($sqlstr,$param =array()) {
        $sth = $this->conn->prepare($sqlstr);
        if (!is_array($param)) {
             $param = array($param);
        }
        
        $status = $sth->execute($param);
        if($status) {
             return $sth->fetchAll(PDO::FETCH_ASSOC);
        } 
        else 
        {
             $this->errorInfo = print_r($sth->errorInfo(),true);
             return -1;
        }
    }
    
    /*
     *  执行Prepare SQL，返回对象
     */
    public function ExecutePrepareQueryReturnObject($sqlstr,$classname,$param) {
         $sth = $this->conn->prepare($sqlstr);
         if (!is_array($param)) {
             $param = array($param);
         }
         
         $status = $sth->execute($param);
         $result = array();
         if($status) {
             while ($row = $sth->fetchObject($classname)) {
                 $result[] = $row;
             }
             
             return $result;
         } else {
             $this->errorInfo = print_r($sth->errorInfo(),true);
             return false;
         }
    }

    /*
     *  执行Prepare SQL，返回对象
     */
    public function ExecutePrepareQueryReturnRs($sqlstr,$param =array()) {
        $sth = $this->conn->prepare($sqlstr);
        if (!is_array($param)) {
             $param = array($param);
        }
        $result = array();
        $status = $sth->execute($param);
        if($status) {
            do {
              $result[] = $sth->fetchAll(PDO::FETCH_ASSOC);
            } while ($sth->nextRowset());
            return $result;
        } else {
            $this->errorInfo = print_r($sth->errorInfo(),true);
            
            return FALSE;
        }
        
       
    }
    // Insert 
    public function ExecuteInsert($table,array $params,$isAuto = TRUE) {
       $cols = $vals = array();
        foreach ($params as $col => $val) {
                $cols[] = $col;
                $vals[] = '?';
        }
        $sql = 'INSERT INTO `' . $table . '`'
                . '(`' . implode('`, `', $cols) . '`)'
                . 'VALUES (' . implode(', ', $vals) . ');';
        $sth = $this->ExecutePrepare($sql, array_values($params));
        if($isAuto) {
            if ($sth) {
                return $this->conn->lastInsertId();
            } else {
                exit();
                return 0;
            }
        } else {
            if ($sth) {
                return true;   
            } else {
                return FALSE;
            }
        }
        
    }
    
   /*
    * 获取Last Last Insert ID
    */
    public function GetLastInsertID() {
        return $this->conn->lastInsertId();
    }
   
    // Update
    public function ExecuteUpdate($table, array $bind, array $condition) {
        $sets = array();
        $vals = array();
        foreach ($bind as $col => $val) {
                $sets[] = "`$col`= ?";
                $vals[] = $val;
        }
        $where ='';
        foreach ($condition as $key => $val)
        {
                $where.="$key = ? and ";
                $vals[] = $val;
        }
        
        $where = substr($where, 0, strlen($where)-4);
        $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets)
                . (($where) ? ' WHERE ' . $where : '');
        return $this->ExecutePrepare($sql, $vals);
    }
    
    // Delete
    public function ExecuteDelete($table, $where) {
        if (empty($where)) {
                return false;
        }
        $sql = 'DELETE FROM `' . $table .'`';
        $condition='';
        if (!empty($where) && !is_null($where) || $where!=array())
        {
                foreach ($where as $key=>$val)
                {
                        $condition.="`$key` = ? and ";
                        $data[] = $val;
                }
        }
        $where = substr($condition,0,strlen($condition)-4);
        $sql.= ' WHERE '.$where;
        return $this->ExecutePrepare($sql,$data);
    }
    
    
    public function Fetch($table,$field=array(),$where = array(),$limit=0,$offset=0,$join=null,$groupby=null,$orderby=null) {
        $data = array();
        $condition="";
        if (empty($field)||count($field)==0)
                $field='*';
        else if ($field =='num')
                $field = 'count(*)';
        else{
                if (!is_array($field))
                    $field = array($field);
                $field = implode(",",$field);
        }
        $sql = "SELECT $field FROM $table ";
        if (!empty($join)&&count($join)!=0) {
                $sql.=" JOIN $join[0] ON $join[1] ";
        }  
        if (!empty($where) && !is_null($where) || $where!=array()){
            foreach ($where as $key=>$val) {
                    $condition.="$key=? and ";
                    $data[] = $val;
            }
            $where = substr($condition, 0, strlen($condition)-4);
            $sql.='WHERE '.$where;
        }
        
        if (!empty($groupby) && $groupby != null)
                $sql.=' group by '.$groupby;
        if (!empty($orderby) && $orderby != null)
                $sql.=' order by OrderID '.$orderby;
        if ($limit !=0 || $offset !=0)
                $sql.=' LIMIT '.$offset.','.$limit; 
        return $this->ExecutePrepareQuery($sql,$data);
    }

 }
?>