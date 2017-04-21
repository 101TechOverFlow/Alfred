<?php
session_start();
require("../../config.php");
require("sql.php");

$api = new GroupAPI();

class GroupAPI{
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
    
    private function addUser($params){
        $params = json_decode($params);
        $u_id = -1;
        if(is_numeric(@$params->u_id)){
                $u_id = $params->u_id;
        }
        $g_id = -1;
        if(is_numeric(@$params->g_id)){
                $g_id = $params->g_id;
        }        
        if($g_id > 0 && $u_id){
            if(!$this->database->checkUserMember($g_id, $_SESSION["id"])){
                $this->database->insertUserInGroup($_SESSION["id"], $g_id);
                $this->database->updateGroupNb( $g_id , "add");
                die(json_encode(array("code"=>302, "data"=> "success")));
            }
            else {
                die(json_encode(array("code"=> 404, "data" => "user is already in group")));
            }
        }
        else{
            die(json_encode(array("code"=> 404, "data" => "unknown user or group")));
        }
    }
    
    private function removeUser($params){
        $params = json_decode($params);
        $u_id = -1;
        if(is_numeric(@$params->u_id)){
                $u_id = $params->u_id;
        }
        $g_id = -1;
        if(is_numeric(@$params->g_id)){
                $g_id = $params->g_id;
        }        
        if($g_id > 0 && $u_id){
            $this->database->removeUserInGroup($_SESSION["id"], $g_id);
            $this->database->updateGroupNb( $g_id , "remove");
            die(json_encode(array("code"=>302, "data"=> "success")));
        }
        else{
            die(json_encode(array("code"=> 404, "data" => "unknown user or group")));
        }
    }
    
    private function rename($params){
        $params = json_decode($params);        
        $g_id = -1;
        if(is_numeric(@$params->g_id)){
                $g_id = $params->g_id;
        } 
        $g_name = htmlspecialchars(trim(@$params->g_name));
        if($g_name != "" && $g_id > 0){
            $this->database->updateGroupName($g_id, $g_name);
            die(json_encode(array("code"=>302, "data"=> "success")));
        }
        else {
            die(json_encode(array("code"=> 404, "data" => "wrong group name or group id")));
        }
    }
    
    private function search($params){
        $params = json_decode($params);
        $keyword = htmlspecialchars(trim(@$params->query));
        if($keyword != ""){
            $results = $this->database->searchGroups( $keyword );		
        }
        else {
            $results = $this->database->getGroups();
        }        
        die(json_encode(array("code"=>302, "data"=> $results)));
    }
    
    private function create($params){
        $params = json_decode($params);        
        $g_name = htmlspecialchars(trim(@$params->g_name));
        if($g_name != ""){
            $this->database->insertGroup($g_name);
            die(json_encode(array("code"=>302, "data"=> "success")));
        }
        die(json_encode(array("code"=> 404, "data" => "wrong group name")));
        
    }    
}

?>