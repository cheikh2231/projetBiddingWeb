<?php
require_once "framework/Controller.php";

class ControllerLogin extends Controller {
    public function index(): void {
        (new View("login"))->show([""]);
    }
    public function login():void{
        $email ='';
        $password = '';
        $errors = [];
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = User::validate_login($email);
            if(empty($errors)){
                $this->log_user(User::getUserByEmail($email));
            }
        }
        (new View("login"))->show(["email"=>$email, "password"=> $password, "errors"=> $errors]);
        
    }
    

}   