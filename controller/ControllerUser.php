<?php
require_once 'framework/View.php';
require_once "framework/Controller.php";
require_once "model/User.php";

class ControllerUser extends Controller {

    public function index() : void {
        
        if ($this->user_logged()) {
            $this->redirect("item", "index");
        } else {
            (new View("login"))->show(["errors" => []]);
        }
    }
    // méthode login
    public function login(): void {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $errors = User::validate_login($email, $password);
        
        if (empty($errors)) {
            $this->is_validate_login($email);
        } else {
            
            (new View("login"))->show([
                "email" => $email, 
                "errors" => $errors
            ]);
        }
    }  
    // fonction si login valide (évite la répétition de code)
    public function is_validate_login(String $emailUser): void{
        $user = User::getUserByEmail($emailUser);
        $this->log_user($user);
        $this->redirect("item", "index"); 
    }
    //fonction pour log en Debug
    public function log_for_debug():void{
        $userEmail = $_GET['param1']."@epfc.eu";
       $this->is_validate_login($userEmail);
    }
    //fonction logout
    public function logout() : void {
      parent::logout();
    } 

    //fonction signup
    public function signup():void{
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
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
        }else{
               (new View("signup"))->show(["errors"=>[]]);
        }  
        
    }  
    //fonction profile
    public function profile() : void {
        $userConnected = $this->get_user_or_redirect();
        (new View("profile"))->show(["user"=>$userConnected]);


    }
    //fonction editProfile
    public function edit_profile():void{
        $userConnected = $this->get_user_or_redirect();
        (new View("editProfile"))->show(["user"=>$userConnected]);

    }


}