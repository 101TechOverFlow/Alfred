<?php

class Database {
    var $sql;
    
    function __construct()
    {
       $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
       $this->sql = new PDO( SQL_DNS, SQL_USER, SQL_PASS, $options );      
    }
    
    public function getUserById( $u_id ){
        $q = "SELECT * FROM `users` WHERE `u_id` = :u_id LIMIT 1;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":u_id",$u_id);
        $query->execute(); 
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function insertGroup( $g_name, $u_id , $g_editable=1 ){
        $q = "INSERT INTO `users_groups` (`g_id`, `g_name`, `g_nb`, `g_editable`,`u_id`) VALUES (NULL, :gname, '0', :gedit, :uid);";          
        $query = $this->sql->prepare($q);        
        $query->bindParam(":gname",$g_name);        
        $query->bindParam(":gedit",$g_editable);    
        $query->bindParam(":uid",$u_id);
        $query->execute();        
    }

    

}
