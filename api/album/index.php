<?php
session_start();
require("../../config.php");
require("sql.php");

$api = new AlbumAPI();

class AlbumAPI {
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
            else{
                die(json_encode(array("code" => 404, "data" => "unknown REQUEST METHOD")));			
            }
        }
        else {
            die(json_encode(array("code" => 404, "data" => "not logged to api<br>see /api/user/login/params")));		
        }
    }
    
    private function create($params){
        $params = json_decode($params);        
        $a_name = htmlspecialchars(trim(@$params->a_name));
        if($a_name != ""){
            $this->database->insertAlbum($a_name);
            die(json_encode(array("code"=>302, "data"=> "success")));
        }
        die(json_encode(array("code"=> 404, "data" => "wrong album name")));
        
    } 
    
    private function share($params){
        $params = json_decode($params);
        
        $a_id = -1;
        if(is_numeric(@$params->a_id)){
            $a_id = $params->a_id;
        }        
        $g_id = -1;
        if(is_numeric(@$params->g_id)){
            $g_id = $params->g_id;
        }
        $rule = htmlspecialchars(trim(@$params->rule));        
        $read = 0; $write = 0; $delete = 0;
        if($rule == "delete"){
            $delete = 1; $write = 1; $read = 1;
        } 
        else if($rule == "write"){
            $write = 1; $read = 1;
        }
        else if($rule == "read"){
            $read = 1;
        }        
        
        if($a_id > 0){
            if($read == 0){
                $this->database->deleteAlbumAccess($a_id, $g_id);
            }
            else {
                if($this->database->checkAlbumAccess($a_id,$g_id)){
                    $this->database->updateAlbumAccess($a_id, $g_id, $read, $write, $delete);
                }
                else {
                    $this->database->insertAlbumAccess($a_id, $g_id, $read, $write, $delete);
                }                
            }
            die(json_encode(array("code"=>302, "data"=> "success")));
        }
        die(json_encode(array("code"=> 302, "data" => "done")));                
    }
}
