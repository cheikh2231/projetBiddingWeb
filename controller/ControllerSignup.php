<?php
require_once "framework/Controller.php";
require_once "model/User.php";
require_once 'framework/View.php';
require_once "controller/ControllerLogin.php";

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
            (new View("signup"))->show(["success" => "Compte créé avec succès ! Vous allez être redirigé vers la connexion.","errors" => []]);

        }else{
            (new View("signup"))->show(["errors"=>$errors,"email"=>$email,"fullname"=>$fullname,"pseudo"=>$pseudo]);
        }

    }



}