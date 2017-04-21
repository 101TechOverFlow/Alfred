<?php


require("../Database.core.php");

class SQL extends Database{
    
    public function getBestStorages( $size ){
        $q = "SELECT * FROM `storages` WHERE `s_size_total` > `s_size_used` + :size ORDER BY (`storages`.`s_size_used`+0.00/`storages`.`s_size_total`+0.00) ASC LIMIT 2;";
        $query = $this->sql->prepare($q);          
        $query->bindParam(":size",$size);        
        $query->execute(); 
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function getFile( $f_id ){
        $q = "SELECT * FROM `files` WHERE `f_id` = :f_id LIMIT 1;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":f_id",$f_id);
        $query->execute(); 
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function getFilePath( $f_id ){
        $q = "SELECT `files_path`.*,`storages`.* FROM `storages`,`files_path` WHERE `files_path`.`f_id` = :f_id AND `files_path`.`s_id`=`storages`.`s_id`;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":f_id",$f_id);
        $query->execute(); 
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function checkAccess($u_id, $f_id, $access){
        if($access == "read"){
            $q = "SELECT `files_access`.* FROM `users_groups`,`files_access`,`files` WHERE (`files_access`.`f_id` = :fid AND `files_access`.`g_id` = `users_groups`.`g_id` AND `users_groups`.`u_id` = :uid AND `files_access`.`i_read`='1') OR `files`.`u_id`=:uid LIMIT 1;";        
        } else if($access == "write"){
            $q = "SELECT `files_access`.* FROM `users_groups`,`files_access`,`files` WHERE (`files_access`.`f_id` = :fid AND `files_access`.`g_id` = `users_groups`.`g_id` AND `users_groups`.`u_id` = :uid AND `files_access`.`i_write`='1') OR `files`.`u_id`=:uid LIMIT 1;";        
        }else if( $access == "delete"){
            $q = "SELECT `files_access`.* FROM `users_groups`,`files_access`,`files` WHERE (`files_access`.`f_id` = :fid AND `files_access`.`g_id` = `users_groups`.`g_id` AND `users_groups`.`u_id` = :uid AND `files_access`.`i_delete`='1') OR `files`.`u_id`=:uid LIMIT 1;";        
        }
        else {
            return false;
        }
        
        $query = $this->sql->prepare($q);        
        $query->bindParam(":uid",$u_id); 
        $query->bindParam(":fid",$f_id);       
        $query->execute(); 
        $result = $query->rowCount();
        if($result == 1){
            return true;
        }
        return false;
    }
    
    public function searchFiles( $searched, $trash=0){
        $list = join("','",$searched);        
        $nb = count($searched);
        $q = "SELECT DISTINCT `files`.* FROM `users_members`,`files`,`files_access` WHERE ((`files`.`u_id`=:uid OR (`files_access`.`f_id` = `files`.`f_id` AND `files_access`.`g_id` = `users_members`.`g_id` AND `users_members`.`u_id` = :uid AND `files_access`.`i_read`='1'))  AND `files`.`f_trash`=:trash) AND :nb = (SELECT count(*) FROM `tags` INNER JOIN `files_tags` ON `files_tags`.`t_id` = `tags`.`t_id` WHERE `files_tags`.`f_id` = `files`.`f_id` AND `tags`.`t_name` IN ('$list')) ORDER BY `files`.`f_name` ASC LIMIT 100;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":searchedTags",$list);
        $query->bindParam(":trash",$trash);
        $query->bindParam(":uid",$_SESSION["id"]);
        $query->bindParam(":nb",$nb);
        $query->execute(); 
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function getFiles($trash=0){
        $q = "SELECT DISTINCT `files`.* FROM `users_members`,`files`,`files_access` WHERE ((`files`.`u_id`=:uid OR (`files_access`.`f_id` = `files`.`f_id` AND `files_access`.`g_id` = `users_members`.`g_id` AND `users_members`.`u_id` = :uid AND `files_access`.`i_read`='1'))  AND `files`.`f_trash`=:trash) ORDER BY `files`.`f_name` ASC LIMIT 100;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":trash",$trash);
        $query->bindParam(":uid",$_SESSION["id"]);
        $query->execute(); 
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function getFileTags( $f_id ){
        $q = "SELECT `tags`.`t_name`, `tags`.`t_id` FROM `tags`, `files_tags` WHERE `files_tags`.`f_id` = :fid AND `tags`.`t_id` = `files_tags`.`t_id`";
        $query = $this->sql->prepare($q);
        $query->bindParam(":fid",$f_id);
        $query->execute(); 
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function getTags( $trash=0 ){      
        $q = "SELECT DISTINCT `tags`.`t_name` FROM `files`,`files_tags`,`tags`,`files_access` WHERE (`tags`.`t_id`=`files_tags`.`t_id` AND `files`.`u_id`='1' AND `files`.`f_trash` = :trash AND `files_tags`.`f_id`=`files`.`f_id`) OR (`files_access`.`i_read`='1' AND `files_access`.`u_id`='1' AND `files_tags`.`f_id`=`files_access`.`f_id` AND `tags`.`t_id`=`files_tags`.`t_id` AND `files_tags`.`f_id`= `files`.`f_id` AND `files`.`f_trash` = :trash ) ORDER BY `t_usage` ASC LIMIT 50;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":uid",$_SESSION["id"]);        
        $query->bindParam(":trash",$trash);
        $query->execute(); 
        $result = $query->fetchAll(PDO::FETCH_NUM);
        return $result;
    }
    
    public function removeFileTag($fid, $tid){
        $q = "DELETE `files_tags` FROM `files`,`files_tags`,`files_access` WHERE `files_tags`.`f_id` = :fid AND `files_tags`.`t_id`= :tid";
        $query = $this->sql->prepare($q);        
        $query->bindParam(":tid",$tid); 
        $query->bindParam(":fid",$fid);        
        $query->execute(); 
    }

    public function insertTag( $tag ){
        $q = "INSERT INTO `tags` (`t_id`, `t_name`, `t_usage`, `t_search`) "
                . "VALUES (NULL, :tag, '0', '0');";          
        $query = $this->sql->prepare($q);        
        $query->bindParam(":tag",$tag);        
        $query->execute();        
    }

    public function insertFile($fname,$fextension,$fmime,$fsize,$fmd5,$ffilename){
        $q = "INSERT INTO `files` (`f_id`, `f_name`, `f_extension`, `f_mime`, `f_size`, `f_md5`, `f_filename`, `f_timestamp`, `f_picture`, `f_music`, `f_movie`, `f_book`, `f_trash`, `u_id`) 
        VALUES (NULL, :fname, :fextension, :fmime, :fsize, :fmd5, :ffilename, :ftimestamp, 0, 0, 0, 0, 0, :uid);";

        $query = $this->sql->prepare($q);
        $query->bindParam(":fname",$fname);
        $query->bindParam(":fextension",$fextension);
        $query->bindParam(":fmime",$fmime);
        $query->bindParam(":fsize",$fsize);
        $query->bindParam(":fmd5",$fmd5);
        $query->bindParam(":ffilename",$ffilename);
        $time = time();
        $query->bindParam(":ftimestamp",$time);
        $query->bindParam(":uid",$_SESSION["id"]);
        $query->execute();

        return $this->sql->lastInsertId();
    }

    public function insertFileTag( $fid , $tag ){
        $q = "INSERT INTO `files_tags` (`f_id`, `t_id`, `ft_timestamp`) "
                . "VALUES (:fid, (SELECT `tags`.`t_id` FROM `tags` WHERE `tags`.`t_name` = :tag LIMIT 1), :time);";  
        
        $query = $this->sql->prepare($q);
        $query->bindParam(":fid",$fid);
        $query->bindParam(":tag",$tag);
        $time = time();
        $query->bindParam(":time",$time);
        $query->execute();        
    }

    public function checkFileTag( $fid , $tag ){
        $q = "SELECT * FROM `files_tags`, `tags` WHERE `files_tags`.`f_id` = :fid AND `tags`.`t_name` = :tag AND `files_tags`.`t_id` = `tags`.`t_id` LIMIT 1;";          
        $query = $this->sql->prepare($q);        
        $query->bindParam(":tag",$tag); 
        $query->bindParam(":fid",$fid);       
        $query->execute(); 
        $result = $query->rowCount();
        if($result == 1){
            return true;
        }
        return false;
    }

    public function checkTag( $tag ){
        $q = "SELECT `tags`.`t_name` FROM `tags` WHERE `t_name` = :tag LIMIT 1;";          
        $query = $this->sql->prepare($q);        
        $query->bindParam(":tag",$tag);        
        $query->execute(); 
        $result = $query->rowCount();
        if($result == 1){
            return true;
        }
        return false;
    }

    public function renameFile($id, $fName){
        $q = "UPDATE `files` SET `f_name` = :name WHERE `f_id` = :id;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":id",$id);
        $query->bindParam(":name",$fName);
        $query->execute();
    }

    public function storeFile($fid,$sid){
        $q = "INSERT INTO `files_path` (`f_id`, `s_id`, `fp_timestamp`) VALUES (:fid, :sid, :time);";
        $query = $this->sql->prepare($q);
        $query->bindParam(":fid",$fid);
        $query->bindParam(":sid",$sid);
        $time = time();
        $query->bindParam(":time",$time);
        $query->execute();
    }
    
    public function deleteFile($fid){
        $q = "UPDATE `files` SET `f_trash` = '1' WHERE `f_id` = :fid;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":fid",$fid);
        $query->execute();
    }

    public function restoreFile($id){
        $q = "UPDATE `files` SET `f_trash` = '0' WHERE `f_id` = :id;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":id",$id);
        $query->execute();
    }

    public function destroyFile($fid){
        $q = "DELETE FROM `files_tags` WHERE `files_tags`.`f_id` = :fid;DELETE FROM `files_path` WHERE `files_path`.`f_id` = :fid; DELETE FROM `files_access` WHERE `files_access`.`f_id` = :fid; DELETE FROM `files` WHERE `files`.`f_id` = :fid; ";
        $query = $this->sql->prepare($q);        
        $query->bindParam(":fid",$fid);        
        $query->execute(); 
    }
    
    public function updateStorageUsage( $s_id, $size ){
        $q = "UPDATE `storages` SET `s_size_used` = s_size_used + :size WHERE `s_id` = :sid;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":sid",$s_id);
        $query->bindParam(":size",$size);
        $query->execute();
    }

    public function updateUserDiskUsage( $size ){
        $q = "UPDATE `users` SET `u_size_used` = u_size_used + :size WHERE `u_id` = :uid;";
        $query = $this->sql->prepare($q);
        $query->bindParam(":uid",$_SESSION["id"]);
        $query->bindParam(":size",$size);
        $query->execute();
    }
    
    public function insertGroupAccess( $f_id, $g_id, $read, $write, $delete ){
        $q = "INSERT INTO `files_access` (`f_id`, `g_id`, `i_read`, `i_write`, `i_delete`) VALUES (:fid, :gid, :read, :write, :delete);";          
        $query = $this->sql->prepare($q);        
        $query->bindParam(":fid",$f_id);
        $query->bindParam(":gid",$g_id);        
        $query->bindParam(":read",$read);        
        $query->bindParam(":write",$write);        
        $query->bindParam(":delete",$delete);
        $query->execute();        
    }
    
    public function checkGroupAccess($g_id,$f_id){
        $query = $this->sql->prepare("SELECT * FROM `files_access` WHERE `f_id` = :fid AND `g_id` = :gid;");
        $query->bindParam(':fid', $f_id);
        $query->bindParam(':gid', $g_id);
        $query->execute();
        $nb = $query->rowCount();
        if($nb == 1){
            return true;        
        }
        return false;
    }
    
}
