<?php
session_start();
require("../../config.php");
require("sql.php");

$api = new UserAPI();

class UserAPI{
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
    
    /**
     * Allow user to connect to the API
     * 
     * @param type $params->u_name : user name  
     * @param type $params->u_password : user password
     */
    private function login($params){
        $params = json_decode($params);
        $u_name = htmlspecialchars(trim(@$params->u_name));        
        $u_password = htmlspecialchars(trim(@$params->u_password));

        $uData = $this->database->checkCredentials($u_name,$u_password);
        if($uData != false){
                $_SESSION["id"] = $uData["id"];
                die(json_encode(array("code" => 302, "data" => "sucess")));
                
        }else{
            die(json_encode(array("code" => 303, "data" => "wrong credentials")));		       
        }        
    }
    
    /**
     * Allow user to deconnect from the API
     */
    private function logout(){
        $_SESSION = array();
        session_destroy();
	die(json_encode(array("code" => 302, "data" => "sucess")));
    }
    
    /**
     * Allow user to change his password
     * 
     * @param type $params->old_pass  : old user password 
     * @param type $params->new_pass1 : new user password
     * @param type $params->new_pass2 : new user password repeated
     */
    private function changePassword($params){
        $params = json_decode($params);
        // /!\ needs to applys filters /!\
        $old_pass = htmlspecialchars(trim(@$params->old_pass));
        $new_pass1 = htmlspecialchars(trim(@$params->new_pass1));
        $new_pass2 = htmlspecialchars(trim(@$params->new_pass2));

        $udata = $this->database->getUserById($_SESSION["id"]);

        if($udata["u_password"] == $old_pass){
            if($old_pass != "" && $new_pass1 == $new_pass2){
                $this->database->updateUserPassword($new_pass1);
                die(json_encode(array("code"=> 302,"data"=>"success")));
            }
        }
        die(json_encode(array("code"=> 303,"data"=>"Error")));
    }
    
    /**
     * Allow to parse all information of one user
     */
    private function overview(){
        $f_data = $this->database->getFileRepartition();
        $data["file"]["files"] = 0;        
        $data["file"]["trash"] = 0;
        $data["file"]["image"] = 0;
        $data["file"]["video"] = 0;
        $data["file"]["music"] = 0;
        $data["file"]["other"] = 0;
        foreach($f_data as $f){
            if($f["f_trash"] == 1){
                $data["file"]["trash"] += $f["f_size"];
            }
            else {
                $data["file"]["files"] += $f["f_size"];
            }            
            if(strpos($f["f_mime"], 'image') !== false ){
                $data["file"]["image"] += $f["f_size"];
            }
            else if(strpos($f["f_mime"], 'video') !== false ){
                $data["file"]["video"] += $f["f_size"];
            }
            else if(strpos($f["f_mime"], 'audio') !== false || strpos($f["f_mime"], 'music') !== false){
                $data["file"]["music"] += $f["f_size"];
            }
            else{
                $data["file"]["other"] += $f["f_size"];
            }
        }
        $data["user"] = $this->database->getUserById($_SESSION["id"]);	
        die(json_encode(array("code"=> 302, "data" => $data)));
    }
    
    private function getGroups(){
        $g_data = $this->database->getUserGroups();
        die(json_encode(array("code"=> 302, "data" => $g_data)));
    }
    
    private function register($params){
        $params = json_decode($params);        
        $username = htmlspecialchars(trim(@$params->username));
        $password = htmlspecialchars(trim(@$params->password));
        $mail = htmlspecialchars(trim(@$params->mail));
        
        if($username != "" && $password != "" && $mail != ""){
            $u_id = $this->database->insertUser($username, $password, $mail, U_SIZE);
            $this->database->insertGroup($username,$u_id , 0);
            die(json_encode(array("code"=> 302, "data" => "success")));
        }
        else {
            die(json_encode(array("code"=> 303, "data" => "Error")));
        }
        
    }
}

?>