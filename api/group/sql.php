<?php


require("../Database.core.php");

class SQL extends Database{
    
    public function insertUserInGroup( $u_id, $g_id ){
        $q = "INSERT INTO `users_mebers` (`u_id`, `g_name`) VALUES (:uid, :gid);";          
        $query = $this->sql->prepare($q);        
        $query->bindParam(":uid",$u_id);
        $query->bindParam(":gid",$g_id);       
        $query->execute();        
    }
    
    public function removeUserInGroup( $u_id, $g_id ){
        $q = "DELETE FROM `users_mebers` WHERE `u_id`=:uid AND `g_ig`=:gid;";          
        $query = $this->sql->prepare($q);        
        $query->bindParam(":uid",$u_id);
        $query->bindParam(":gid",$g_id);       
        $query->execute();        
    }
    
    public function updateGroupName( $g_id, $g_name ){
        $q = "UPDATE `users_groups` SET `g_name` = :gname WHERE `g_id` = :gid;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":gid",$g_id);
        $query->bindParam(":gname",$g_name);
        $query->execute();
    }
    
    public function getGroups(){
        $q = "SELECT * FROM `users_groups` ORDER BY `users_groups`.`g_name` ASC LIMIT 10;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":trash",$trash);
        $query->bindParam(":uid",$_SESSION["id"]);
        $query->execute(); 
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function searchGroups($keyword){
        $key = "%".$keyword."%";
        $q = "SELECT * FROM `users_groups` WHERE `g_name` LIKE :keyword ORDER BY `users_groups`.`g_name` ASC LIMIT 10;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":keyword",$key);
        $query->execute(); 
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function insertGroup( $g_name, $g_editable=0 ){
        $q = "INSERT INTO `users_groups` (`g_id`, `g_name`, `g_nb`, `g_editable`) VALUES (NULL, :gname, '0', :gedit);";          
        $query = $this->sql->prepare($q);        
        $query->bindParam(":gname",$g_name);        
        $query->bindParam(":gedit",$g_editable);         
        $query->execute();        
    }
    
    public function updateGroupNb( $g_id , $method="add"){
        if($method=="add"){
            $q = "UPDATE `users_groups` SET `g_nb` = `g_nb` + 1 WHERE `g_id` = :gid;";
        }
        else {
            $q = "UPDATE `users_groups` SET `g_nb` = `g_nb` - 1 WHERE `g_id` = :gid AND `g_nb` > 0;";        
        }
        $query = $this->sql->prepare($q);
        $query->bindParam(":gid",$g_id);        
        $query->bindParam(":nb",$nb);
        $query->execute();
    }
    
}