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
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
        
            $errors = User::validate_login($email, $password);
        
            if (empty($errors)) {
                $this->is_validate_login($email);
            } else {
                (new View("login"))->show(["email" => $email, "errors" => $errors]);
            }
        }else{
            (new View("login"))->show(["errors" =>[]]);
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
        if(User::getUserByEmail($userEmail))
            $this->is_validate_login($userEmail);
         $errors["logForDebug"] = "Email has been changed";
         (new View("login"))->show([ "errors" => $errors]);
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
         if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = trim($_POST['email'] ?? '');
            $fullname = trim($_POST['fullname'] ?? '');
            $pseudo = trim($_POST['pseudo'] ?? '');
            $iban = trim($_POST['iban'] ?? '');

            $errors = $userConnected->validate_edit($email,$fullname,$pseudo,$iban);
            if(empty($errors)){
                $userConnected->setEmail($email);
                $userConnected->setName($fullname);
                $userConnected->setPseudo($pseudo);
                if ($iban) $userConnected->setIban($iban);
                $userConnected->persist();
                self::profile();
            }else{
                (new View("editProfile"))->show(["user"=>$userConnected,"errors"=>$errors]);
            }

         }else{
            (new View("editProfile"))->show(["user"=>$userConnected,"errors"=>[]]);
         }


    }
    //fonction edit password
    public function edit_password():void{
        $userConnected = $this->get_user_or_redirect();
         if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $currentPassword =  $_POST['currentPassword'] ?? '';
            $newPassword = $_POST['newPassword'] ?? '';
            $confirmNewPassword = $_POST['confirmNewPassword'] ?? '';
            $errors = $userConnected->validate_password($currentPassword, $newPassword, $confirmNewPassword);
            if(empty($errors)){
                $userConnected->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
                $userConnected->persist();
                self::profile();
            }else{
                (new View("editPassword"))->show(["errors"=>$errors]);
            }
         }else{
            (new View("editPassword"))->show(["errors"=>[]]);
         }
    }
    //fonction edit profile picture
    public function edit_profile_picture() : void {
        $userConnected = $this->get_user_or_redirect();
        $errors = [];
        if(isset($_FILES['image']) && $_FILES['image']['error']=== UPLOAD_ERR_OK && $_SERVER['REQUEST_METHOD'] === 'POST'){
            $errors = $userConnected->validate_photoDeProfil($_FILES['image']);
            if(empty($errors)){
                $userConnected->change_or_add_profile_picture($_FILES['image']);
                self::profile();
            }else{
                
                (new View("editProfilePicture"))->show(["user"=>$userConnected,"errors"=>$errors]);
            }

        }else{
            (new View("editProfilePicture"))->show(["user"=>$userConnected,"errors"=>[]]);
        }

    }
    //fonction delete profile picture
    public function delete_profile_picture() : void{
        $userConnected = $this->get_user_or_redirect();
        $userConnected->delete_profile_picture();
        (new View("editProfilePicture"))->show(["user"=>$userConnected,"errors"=>[]]);

    }
}