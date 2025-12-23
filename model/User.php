<?php
    require_once "framework/Model.php";

    class User extends Model{
        //constructeur
        public function __construct(public string $name, public string $email, public string $pseudo, public string $password, public string $role, public ?string $picture_path, public ?string $iban)
        {
        
        }
        
        //méthode
        public static function getUserByPseudo(string $pseudo) : User|false{
            $query = self::execute("SELECT * from users where users.pseudo = :pseudo", ["pseudo"=>$pseudo]);
            $data = $query->fetch();
            if($query->rowCount() ==0)
                return false;
            else
                return new User($data["full_name"],$data["email"],$data["pseudo"],$data["password"],$data["role"],$data["picture_path"],$data["iban"]);

        }
        public static function getUserByEmail(string $email) : User|false{
            $query = self::execute("SELECT * from users where users.email = :email", ["email"=>$email]);
            $data = $query->fetch();
            if($query->rowCount() ==0)
                return false;
            else
                return new User($data["full_name"],$data["email"],$data["pseudo"],$data["password"],$data["role"],$data["picture_path"],$data["iban"]);

        }
        
        public static function getAllUsers() : array {
            $query = self::execute("SELECT * FROM Users", []);
            $data = $query->fetchAll();
            $results = [];
            foreach ($data as $row) {
                $results[] = new User($row["full_name"], $row["email"], $row["pseudo"],$row["password"],$row["role"],$row["picture_path"],$row["iban"]);
            }
            return $results;
        }
        
        public static function validate_login(string $email, string $password) : array {
            $errors = [];
            $user = User::getUserByEmail($email);
            if ($user) {
                if (!self::check_password($password, $user->password)) {
                    $errors[] = "Wrong password. Please try again.";
                }
            } else {
                $errors[] = "Can't find a member with the email '$email'. Please sign up.";
            }
            return $errors;
        }

        private static function check_password(string $clear_password, string $hash) : bool {
            return password_verify($clear_password, $hash);
        }


    }
?>