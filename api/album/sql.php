<?php
require("../Database.core.php");

class SQL extends Database{
    
    public function insertAlbum( $a_name ){
        $time = time();
        $q = "INSERT INTO `album` (`a_id`, `a_name`, `a_timestamp`, `u_id`) VALUES (NULL, :aname, :time, :uid);";          
        $query = $this->sql->prepare($q);        
        $query->bindParam(":aname",$a_name);        
        $query->bindParam(":time",$time);
        $query->bindParam(":uid",$_SESSION["id"]);        
        $query->execute();        
    }
    
    public function insertAlbumAccess( $a_id, $g_id, $read, $write, $delete ){
        $q = "INSERT INTO `album_access` (`a_id`, `g_id`, `aa_read`, `aa_write`, `aa_delete`) VALUES (:aid, :gid, :read, :write, :delete);";          
        $query = $this->sql->prepare($q);        
        $query->bindParam(":aid",$a_id);
        $query->bindParam(":gid",$g_id);        
        $query->bindParam(":read",$read);        
        $query->bindParam(":write",$write);        
        $query->bindParam(":delete",$delete);
        $query->execute();        
    }
    
    public function deleteAlbumAccess($a_id,$g_id){
        $q = "DELETE FROM `album_access` WHERE `album_access`.`a_id` = :aid AND `album_access`.`g_id`= :gid";
        $query = $this->sql->prepare($q);        
        $query->bindParam(":gid",$g_id); 
        $query->bindParam(":aid",$a_id);        
        $query->execute(); 
    }
    
    public function updateAlbumAccess( $a_id, $g_id, $read, $write, $delete ){
        $q = "UPDATE `album_access` SET `aa_read` = :read, `aa_write` = :write, `aa_delete` = :delete WHERE `album_access`.`g_id` = :gid AND `album_access`.`a_id` = :aid;";
        $query = $this->sql->prepare($q);        
        $query->bindParam(":aid",$a_id);
        $query->bindParam(":gid",$g_id);        
        $query->bindParam(":read",$read);        
        $query->bindParam(":write",$write);        
        $query->bindParam(":delete",$delete);
        $query->execute();
    }
    
    public function checkAlbumAccess($a_id, $g_id){
        $query = $this->sql->prepare("SELECT * FROM `album_access` WHERE `a_id` = :aid AND `g_id` = :gid;");
        $query->bindParam(':aid', $a_id);
        $query->bindParam(':gid', $g_id);
        $query->execute();
        $nb = $query->rowCount();
        if($nb == 1){
            return true;        
        }
        return false;
    }
}
