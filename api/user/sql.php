<?php

require("../Database.core.php");

class SQL extends Database{
    
    
    /**
     * Allow to check user credentials
     * 
     * @param type $u_name
     * @param type $u_password
     * @return boolean : false if name/password are incorrect, user data if name/password are correct
     */
    public function checkCredentials($u_name,$u_password){
        $query = $this->sql->prepare("SELECT * FROM `users` WHERE `u_password` = :pass AND `u_name` = :uname LIMIT 1;");
        $query->bindParam(':pass', $u_password);
        $query->bindParam(':uname', $u_name);
        $query->execute();
        $nb = $query->rowCount();
        if($nb == 1){
            return $query->fetch(PDO::FETCH_ASSOC);        
        }
        return false;
    }
    
    public function getFileRepartition(){
        $q = "SELECT `files`.`f_mime`,COUNT(*) as `f_nb` ,SUM(`files`.`f_size`) as `f_size`,`files`.`f_trash` FROM `files` WHERE `files`.`u_id` = :uid GROUP BY `files`.`f_mime`, `files`.`f_trash`";
        $query = $this->sql->prepare($q);
        $query->bindParam(":uid",$_SESSION["id"]);
        $query->execute(); 
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function getUserGroups(){
        $q = "SELECT `users_groups`.* FROM `users_groups`,`users_members` WHERE `users_members`.`g_id` = `users_groups`.`g_id` AND `users_members`.`u_id`=:uid";
        $query = $this->sql->prepare($q);
        $query->bindParam(":uid",$_SESSION["id"]);
        $query->execute(); 
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function insertUser($u_name,$u_password,$u_mail,$u_size){
        $time = time();
        $q = "INSERT INTO `users` (`u_id`, `u_name`, `u_password`, `u_mail`, `u_timestamp`, `u_size_total`, `u_size_used`) "
                . "VALUES (NULL, :uname, :upass, :umail, :time, :usize, '0');";
        $query = $this->sql->prepare($q);
        $query->bindParam(":time",$time);
        $query->bindParam(":uname",$u_name);
        $query->bindParam(":upass",$u_password);
        $query->bindParam(":umail",$u_mail);
        $query->bindParam(":usize",$u_size);
        $query->execute(); 
        return $this->sql->lastInsertId();
    }
}
