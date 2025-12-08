<?php
    class User extends Model{
        //constructeur
        public function __construct(public string $name, public string $email, public string $pseudo, public string $password, public string $role, public ?string $picture_path, public ?string $iban)
        {
        
        }
        
        //méthode
        public static function getUserByPseudo(string $pseudo) : User|false{
            $query = self::execute("SELECT * from user where user.pseudo = :pseudo", ["pseudo"=>$pseudo]);
            $data = $query->fetch();
            if($query->rowCount() ==0)
                return false;
            else
                return new User($data["full_name"],$data["email"],$data["pseudo"],$data["password"],$data["role"],$data["picture_path"],$data["iban"]);

        }
        
        


    }
?>