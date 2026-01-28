<?php
require_once "framework/Controller.php";
require_once "model/User.php";
require_once 'framework/View.php';

class ControllerSignup extends Controller {

    public function index(): void{
       if($this->user_logged())
            $this->redirect("item", "index");
        else
            (new View("signup"))->show(["errors"=>[]]);
    }

    //méthode signup
    public function signup():void{
        $email = trim($_POST['email'] ?? '');
        $fullname = trim($_POST['fullname'] ?? '');
        $pseudo = trim($_POST['pseudo'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';
        
        $errors = User::validate_signup($email,$fullname,$pseudo,$password,$confirmPassword);
        if(empty($errors)){
            //creation user
            $newUser = new User(null,$fullname,$email,$pseudo,password_hash($password, PASSWORD_BCRYPT),"user",null,null);
            $newUser->persist();
            $this->log_user($newUser);
        }else{
            (new View("signup"))->show(["errors"=>$errors,"email"=>$email,"fullname"=>$fullname,"pseudo"=>$pseudo]);
        }

    }



}