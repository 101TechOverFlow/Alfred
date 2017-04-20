<?php
session_start();
require("../../config.php");
require("sql.php");
require("uploadHandler.php");

$api = new FileAPI();

class FileAPI{
    var $database;    
    function __construct(){
        $this->database = new SQL();
        if(is_numeric(@$_SESSION["id"])){
            if($_SERVER['REQUEST_METHOD'] == "GET"){			
                $action = @$_GET["a"];
                $params = @$_GET["p"];
                if(method_exists($this, $action)){
                    $this->$action($params);
                }
                else{
                    die(json_encode(array("code" => 404, "data" => "unknown method in files modules")));			
                }
            }
            else if($_SERVER['REQUEST_METHOD'] == "POST"){
                $action = @$_GET["a"];                    
                $files = @$_FILES;
                if($action =="upload"){
                    $uploadHandler = new uploadHandler($files);
                }
            }
            else{
                die(json_encode(array("code" => 404, "data" => "unknown REQUEST METHOD")));			
            }
        }
        else {
            die(json_encode(array("code" => 404, "data" => "not logged to api<br>see /api/user/login/params")));		
        }
    }
    
    private function getInfo( $params ){
        $params = json_decode($params);
        $f_id = -1;
        if(is_numeric(@$params->f_id)){
                $f_id = $params->f_id;
        }
        if($f_id > 0){
            if($this->database->checkAccess($_SESSION["id"],$f_id,"read")){
                $results = $this->database->getFile($f_id);
                $fp_data = $this->database->getFilePath( $f_id );
                foreach ($fp_data as $path) {
                    $results["f_path"][] = $path["s_path"];
                }
                die(json_encode(array("code"=> 302, "data" => $results)));
            }
            else{
                die(json_encode(array("code"=> 403, "data" => "not authorized")));
            }
        }
        else {
            die(json_encode(array("code"=> 404, "data" => "unknown file")));
        }
    }
    
    private function searchFiles($params){
        $params = json_decode($params);
        $trash = 0;
        if(@$params->trash == 1){
            $trash = 1;
        }
        $keywords = @$params->query; // /!\ need to aply filter on array
        if($keywords != null){
            $results = $this->database->searchFiles( $keywords , $trash );		
        }
        else {
            $results = $this->database->getFiles( $trash );
        }
        foreach ($results as $k => $file) {
            $fp_data = $this->database->getFilePath( $file["f_id"] );
            $u_data = $this->database->getUserById($file["u_id"]);
            $results[$k]["u_name"] = $u_data["u_name"];
            $t_data = $this->database->getFileTags( $file["f_id"]);
            $results[$k]["t_name"] = $t_data;
            foreach ($fp_data as $path) {
                $results[$k]["f_path"][] = $path["s_path"];
            }		
        }	
        die(json_encode(array("code"=>302, "data"=> $results)));
    }
    
    private function searchTags($params){		
        $params = json_decode($params);
        $trash = 0;
        if(@$params->trash == 1){
            $trash = 1;
        }
        $keywords = @$params->query; //Need to add security filter on user input /!\
        if($keywords != null){
            $results = $this->database->searchTags( $keywords , $trash );       
        }
        else{ // if no keywords searched
            $results = $this->database->getTags( $trash );
        }				
        die(json_encode(array("code"=>302, "data"=> $results)));
    }
    
    private function removeTag( $params ){
        $params = json_decode($params);
        $t_id = -1;
        if(is_numeric(@$params->t_id)){
            $t_id = @$params->t_id;
        }
        $f_id = -1;
        if(is_numeric(@$params->f_id)){
            $f_id = $params->f_id;
        }

        if($t_id > 0 && $f_id > 0){
            if($this->database->checkAccess($_SESSION["id"],$f_id,"write")){			
                $this->database->removeFileTag($f_id, $t_id);
                die(json_encode(array("code"=> 302, "data" => $t_id." removed from file ".$f_id)));
            }
            else{
                die(json_encode(array("code"=> 403, "data" => "not authorized")));
            }

        } else {
            die(json_encode(array("code"=> 404, "data" => "unknown file or tag")));
        }
    }
    
    private function addTag( $params ){
        $params = json_decode($params);
        $tag = htmlspecialchars(trim(@$params->tag));
        $f_id = -1;
        if(is_numeric(@$params->f_id)){
            $f_id = $params->f_id;
        }

        if($tag != "" && $f_id > 0){
            if($this->database->checkAccess($_SESSION["id"],$f_id,"write")){			
                if($this->database->checkTag( $tag )){ // checking if tag exist            
                    if(!$this->database->checkFileTag($f_id ,$tag)){ // checking if file already has tag                
                        $this->database->insertFileTag( $f_id , $tag ); // add tag to the file                
                    }
                    die(json_encode(array("code"=> 302, "data" => "done")));
                }
                else {
                    $this->database->insertTag( $tag ); // create the tag
                    $this->database->insertFileTag( $f_id , $tag ); // add tag to the file
                    die(json_encode(array("code"=> 302, "data" => "done")));	            
                }
            }
            else{
                die(json_encode(array("code"=> 403, "data" => "not authorized")));
            }
        } else {
            die(json_encode(array("code"=> 404, "data" => "unknown file or tag")));
        }
    }
    
    private function rename($params){
        $params = json_decode($params);
        $f_id = -1;
        if(is_numeric(@$params->f_id)){
            $f_id = $params->f_id;
        }
        $f_name = htmlspecialchars(trim(@$params->f_name));
        if($f_id > 0 && $f_name != ""){
            if($this->database->checkAccess($_SESSION["id"],$f_id,"write")){	        
                $this->database->renameFile($f_id , $f_name);
                die(json_encode(array("code"=> 302, "data" => "done")));
            }
            else{
                die(json_encode(array("code"=> 403, "data" => "not authorized")));
            }
        }
        else{
            die(json_encode(array("code"=> 404, "data" => "unknown file or wrong name")));
        }
    }
    
    private function download( $params ){
        $params = json_decode($params);
        $f_id = -1;
        if(is_numeric(@$params->f_id)){
            $f_id = $params->f_id;
        }
        if($f_id > 0){
            if($this->database->checkAccess($_SESSION["id"],$f_id,"read")){     				
                $fData = $this->database->getFile( $f_id );
                $fStorage = $this->database->getFilePath( $f_id );
                $downloaded = false;
                foreach ($fStorage as $path) {
                    $f_path=$path["s_path"].$fData["f_filename"];
                    if(file_exists($f_path)){
                        header('Content-Disposition: attachment; filename="'.$fData['f_name'].".".$fData['f_extension'].'"');
                        header('Content-Length: '.$fData["f_size"]);
                        header('Content-Type: application/octet-stream');
                        flush();
                        $file = fopen($f_path, 'r');
                        $len = 10*1024;
                        echo $f_path;
                        while (!feof($file)) {
                            print fread( $file, $len );
                            flush();
                        }
                        fclose($file);						
                        echo "<script>window.close();</script>";			            
                        $downloaded = true;
                        break;
                    }
                }
                if(!$downloaded){
                    die(json_encode(array("code"=> 404, "data" => "enable to locate the file")));
                }
            }
            else{
                die(json_encode(array("code"=> 403, "data" => "not authorized")));
            }

        }
        else{
            die(json_encode(array("code"=> 404, "data" => "unknown file")));
        }
    }



    private function delete( $params ){
        $params = json_decode($params);
        $f_id = -1;
        if(is_numeric(@$params->f_id)){
            $f_id = $params->f_id;
        }

        if($f_id > 0){
            if($this->database->checkAccess($_SESSION["id"],$f_id,"delete")){			
                $this->database->deleteFile($f_id);
                die(json_encode(array("code"=> 302, "data" => "done")));
            }
            else{
                die(json_encode(array("code"=> 403, "data" => "not authorized")));
            }
        }
        else{
            die(json_encode(array("code"=> 404, "data" => "unknown file")));
        }
    }

    private function restore( $params ){
        $params = json_decode($params);
        $f_id = -1;
        if(is_numeric(@$params->f_id)){
            $f_id = $params->f_id;
        }

        if($f_id > 0){
            if($this->database->checkAccess($_SESSION["id"],$f_id,"delete")){			
                $this->database->restoreFile($f_id);
                die(json_encode(array("code"=> 302, "data" => "done")));
            }
            else{
                die(json_encode(array("code"=> 403, "data" => "not authorized")));
            }
        }
        else{
            die(json_encode(array("code"=> 404, "data" => "unknown file")));
        }
    }


    private function destroy( $params ){
        $params = json_decode($params);
        $f_id = -1;
        if(is_numeric(@$params->f_id)){
            $f_id = $params->f_id;
        }
        if($f_id > 0){
            if($this->database->checkAccess($_SESSION["id"],$f_id,"delete")){						
                $fData = $this->database->getFile( $f_id );
                if($fData["f_trash"] == 1){
                    $fPath = $this->database->getFilePath( $f_id );		
                    $this->database->destroyFile($f_id);
                    foreach ($fPath as $path) {
                        $this->database->updateStorageUsage($path["s_id"], - $fData["f_size"]);
                        @unlink($path["s_path"].$fData["f_filename"]);
                        if(in_array($fData["f_mime"],$_CLOUD["files"]["pictures"])){
                            @unlink($path["s_path"].THUMBNAIL_PREFIX.$fData["f_filename"]);					
                        }
                    }
                    $this->database->updateUserDiskUsage( - $fData["f_size"]);
                    die(json_encode(array("code"=> 302, "data" => "done")));
                }
                else {
                    die(json_encode(array("code"=> 404, "data" => "file is not in trash")));
                }
            }
            else{
                die(json_encode(array("code"=> 403, "data" => "not authorized")));
            }
        }
        else{
            die(json_encode(array("code"=> 404, "data" => "unknown file")));
        }
    }
    
}

?>