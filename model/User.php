<?php
require_once "framework/Model.php";
require_once "utils/AppTime.php";
require_once "model/Item.php";
class User extends Model {
    
    private ?int $id;
    private string $name; 
    private string $email; 
    private string $pseudo; 
    private string $password; 
    private string $role; 
    private ?string $picture_path; 
    private ?string $iban;

    public function __construct(
        ?int $id,
        string $name, 
        string $email, 
        string $pseudo, 
        string $password, 
        string $role, 
        ?string $picture_path, 
        ?string $iban
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->pseudo = $pseudo;
        $this->password = $password;
        $this->role = $role;
        $this->picture_path = $picture_path;
        $this->iban = $iban;
    }

   
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPseudo(): string { return $this->pseudo; }
    public function getPassword(): string { return $this->password; }
    public function getRole(): string { return $this->role; }
    public function getPicturePath(): ?string { return $this->picture_path; }
    public function getIban(): ?string { return $this->iban; }


    public function setName(string $name): void { $this->name = $name; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setPseudo(string $pseudo): void { $this->pseudo = $pseudo; }
    public function setPassword(string $password): void { $this->password = $password; }
    public function setRole(string $role): void { $this->role = $role; }
    public function setPicturePath(?string $path): void { $this->picture_path = $path; }
    public function setIban(?string $iban): void { $this->iban = $iban; }



    private static function mapToUser(array $data): User {
        return new User(
            $data["id"], 
            $data["full_name"], 
            $data["email"], 
            $data["pseudo"], 
            $data["password"], 
            $data["role"], 
            $data["picture_path"], 
            $data["iban"]
        );
    }

    public static function getUserByPseudo(string $pseudo): User|false {
        $query = self::execute("SELECT * FROM users WHERE pseudo = :pseudo", ["pseudo" => $pseudo]);
        $data = $query->fetch();
        return $data ? self::mapToUser($data) : false;
    }

    public static function getUserByEmail(string $email): User|false {
        $query = self::execute("SELECT * FROM users WHERE email = :email", ["email" => $email]);
        $data = $query->fetch();
        return $data ? self::mapToUser($data) : false;
    }

    public static function getUserById(int $id): User|false {
        $query = self::execute("SELECT * FROM users WHERE id = :id", ["id" => $id]);
        $data = $query->fetch();
        return $data ? self::mapToUser($data) : false;
    }
                
    public static function getAllUsers(): array {
        $query = self::execute("SELECT * FROM users", []);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = self::mapToUser($row);
        }
        return $results;
    }
    

    //methode validation LOGIN
    public static function validate_login(string $email, string $password): array {
        $errors = [];
        $user = self::getUserByEmail($email);
        if ($user) {
            if (!password_verify($password, $user->getPassword())) {
                $errors[] = "Wrong password. Please try again.";
            }
        } else {
            $errors[] = "Can't find a member with the email '$email'. Please sign up.";
        }
        return $errors;
    }
    //methode validation SIGNUP
    public static function validate_signup(String $email, String $fullname, String $pseudo, String $password, String $confirmPassword) : array{
        $errors = [];
        //verification tout les champs remplis
        if(empty($email)||empty($fullname)||empty($pseudo)||empty($password)||empty($confirmPassword)){
            $errors[] = "All fields are required.";    
        }else{
            //email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                $errors[]= "invalid email format.";
            else if(self::getUserByEmail($email))
                $errors[] = "Email already taken.";
            //fullname
            if(strlen($fullname) <= 3)
                $errors[] = "Full name must be more than 3 letters.";
            //pseudo
            if(self::getUserByPseudo($pseudo))
                $errors[] = "Pseudo already taken.";
            else if((strlen($pseudo) < 3 || strlen($pseudo) > 30))
                $errors[] = "Lenght of the pseudo invalid";
            //mdp
            $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}$/';
            if(!preg_match($pattern, $password))
                $errors[] = "The password must be at least 8 characters long and include 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.";
            //confirmPassword
            if($confirmPassword !== $password)
                $errors[] = "Confirm password doesn't match.";
        }
        return $errors;
    } 
    //methode ajout User dans la database
    public function persist(): void{
        self::execute("INSERT INTO Users(id,full_name,email,pseudo,password,role,picture_path,iban) VALUES(:id,:full_name,:email,:pseudo,:password,:role,:picture_path,:iban)", 
        ["id"=>$this->id,"full_name"=>$this->name,"email"=>$this->email,"pseudo"=>$this->pseudo,"password"=>$this->password,"role"=>$this->role,"picture_path"=>$this->picture_path,"iban"=>$this->iban]);
        //mettre l'id à jour sinon il vaut null. lastInsertId() est une méthode de Model.
        $this->id = self::lastInsertId();
    }




public function myItemsAtives(): array {
    $now = AppTime::get_current_datetime();
    $sql = "SELECT * FROM v_items_status 
            WHERE owner = :user_id AND end_at > :now AND buy_now_reached = 0 
            ORDER BY end_at DESC"; 
    return $this->fetchItems($sql, ['now' => $now, 'user_id' => $this->id]);
}

public function getUnsoldItems(): array {
    $now = AppTime::get_current_datetime();
    $sql = "SELECT * FROM v_items_status 
            WHERE owner = :user_id AND end_at < :now AND buy_now_reached = 0 AND has_bids = 0 
            ORDER BY end_at DESC";
    return $this->fetchItems($sql, ['now' => $now, 'user_id' => $this->id]);
}

public function getSoldItems(): array {
    $now = AppTime::get_current_datetime();
    $sql = "SELECT * FROM v_items_status 
            WHERE owner = :user_id AND (buy_now_reached = 1 OR (end_at < :now AND has_bids = 1)) 
            ORDER BY end_at DESC";
    return $this->fetchItems($sql, ['now' => $now, 'user_id' => $this->id]);
}


private function fetchItems(string $sql, array $params): array {
    $query = self::execute($sql, $params);
    $items = [];
    foreach ($query->fetchAll() as $row) {
        $items[] = new Item(
            $row["id"], $row["title"], $row["description"], $row["owner"], $row["created_at"], 
            $row["is_auction"], $row["has_bids"], $row["has_buy_now"], $row["end_at"], 
            $row["not_purchased_direct_sale"], $row["is_direct_sale"],
            $row["max_bid"], $row["buy_now_price"], $row["duration_days"], 
            $row["starting_bid"], $row["buy_now_reached"] 
        );
    }
    return $items;
}

    public function buyItem(Item $item): bool {
      
        if ($item->getBuyNowPrice() === null || $item->getBuyNowPrice() <= 0) {
            return false;
        }

        
        if ($item->getOwner() === $this->id) {
            return false;
        }

    
        if ($item->isClosed()) {
            return false;
        }
        $amount = $item->getBuyNowPrice();
        $now = AppTime::get_current_datetime();
        
        $bid = new Bids($this->id, $item->getId(), $now, $amount);
        return $bid->add();
    }


    public function add(Item $item) : bool {
    try {
        $sql = "INSERT INTO items (title, description, owner, created_at, buy_now_price, duration_days, starting_bid) 
                VALUES (?,?,?,?,?,?,?)";
        
        $res = self::execute($sql, [
            $item->getTitle(),
            $item->getDescription(),
            $item->getOwner(),
            $item->getCreatedAt(),
            $item->getBuyNowPrice(),
            $item->getDurationDays(),
            $item->getStartingBid()
        ]);

        if ($res !== false) {
           
            $newId = Item::getLastItemsId(); 
            $item->setId($newId); 
            return true;
        }
        return false;
    } catch (Exception $e) {
        return false;
    }
    }

   

}