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
            if(strlen($fullname) < 3)
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
    //methode validation edit profile
    public function validate_edit(String $email, String $fullname, String $pseudo, String $iban): array{
        $errors = [];
        if(empty($email)||empty($fullname)||empty($pseudo)){
            $errors[] = "Email, Name and Pseudo need to be filled in.";
        }else{
            //email
            if($email != $this->email){
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                    $errors[]= "invalid email format.";
                if(self::getUserByEmail($email))
                    $errors[] = "Email already taken.";
            }
            //fullname
            if(strlen($fullname) < 3)
                $errors[] = "Full name must be more than 3 letters.";
            //pseudo
            if($pseudo != $this->pseudo){
                if(self::getUserByPseudo($pseudo))
                    $errors[] = "Pseudo already taken.";
                else if((strlen($pseudo) < 3 || strlen($pseudo) > 30))
                $errors[] = "Lenght of the pseudo invalid";
            }
            //iban
            if(!empty($iban)){
                $ibanPattern = "/^BE\d{2}(\s?\d{4}){3}$/";
                if (!preg_match($ibanPattern, str_replace(' ', '', $iban))) {
                    $errors[] = "Invalid iban format";
                }
            }
        }
        return $errors;
    }
    //methode validation edit profile
    public function validate_password(String $currentPassword, String $newPassword, String $confirmNewPassword): array{
        $errors = [];
        if(empty($currentPassword)||empty($newPassword)||empty($confirmNewPassword)){
            $errors[] = "All fields are required.";
        }else{
            //current password
            if (!password_verify($currentPassword, $this->getPassword()))
                $errors[] = "Current password doesn't match";
            //new password
            $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}$/';
            if(!preg_match($pattern, $newPassword))
                $errors[] = "The password must be at least 8 characters long and include 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.";
            //confirmPassword
            if($confirmNewPassword !== $newPassword)
                $errors[] = "Confirm password doesn't match.";
        }
        return $errors;
    }
    //fonction validation image de profile
    public function validate_photoDeProfil(array $file) : array{
        $errors = [];
        if(!isset($file['image']) || $file['name'] ==''){
            if($file['error']!=0)
                $errors ['error']= "Error while uploading picture.";

            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $validFormat = ["png", "gif", "jpeg", "webp", "jpg"];

            if (!in_array($extension, $validFormat)) 
                $errors['badFormat'] = "Invalid format";
                    
                
            // verifacation taille du fichier (5242880 = 5mb)
            if($file['size']>5242880)
                $errors['badFormat'] = "invalid size.";
                    
            
        }else{
            $errors['noImage'] = "You have to add a picture.";
        }
        self::change_or_add_profile_picture($file);
        

        return $errors;
    }
    private function change_or_add_profile_picture(array $file) : void{
        $picName = self::create_picture_name($file);
        
        //creation dossier si inexistant
        $folder = "uploads/users/".$this->id."/";
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }
        if($this->picture_path !=null){
            $oldFile = $folder.$this->picture_path;
            if($oldFile && file_exists($oldFile)){
                unlink($oldFile);
                $extension = pathinfo($oldFile, PATHINFO_EXTENSION);
                $oldFileThumb = str_replace('.' . $extension, '_thumbnail.' . $extension, $oldFile);
                if(file_exists($oldFileThumb)) 
                    unlink($oldFileThumb);
            }
        }
        
        move_uploaded_file($_FILES['image']['tmp_name'], $folder.$picName);
        self::setPicturePath($folder.$picName);
        self::persist();
    }

    //fonction creation de nom image
    private function create_picture_name(array $file): string{
        $formatOfPic = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $valideFormat = ["jpeg","jpg","png","gif","webp"];

        if (in_array($formatOfPic, $valideFormat)) 
            return $this->pseudo . time() . "." . $formatOfPic;
        else
            return "ERROR new picture name";             
    }



    //methode ajout/mise à jour User dans la database
    public function persist(): void{
        //mise à jour
        if($this->id !== null){
            self::execute("UPDATE Users SET email=:email, full_name=:fullname,pseudo=:pseudo, iban=:iban, password=:password, picture_path=:picture_path WHERE id=:id",["email"=>$this->email, "fullname"=>$this->name,"pseudo"=>$this->pseudo,"iban"=>$this->iban, "id"=>$this->id, "password"=>$this->password, "picture_path"=>$this->picture_path]);
        }        
        else{
        //ajout
            self::execute("INSERT INTO Users(id,full_name,email,pseudo,password,role,picture_path,iban) VALUES(:id,:full_name,:email,:pseudo,:password,:role,:picture_path,:iban)", 
            ["id"=>$this->id,"full_name"=>$this->name,"email"=>$this->email,"pseudo"=>$this->pseudo,"password"=>$this->password,"role"=>$this->role,"picture_path"=>$this->picture_path,"iban"=>$this->iban]);
            //mettre l'id à jour sinon il vaut null. lastInsertId() est une méthode de Model.
            $this->id = self::lastInsertId();
        }
    }



    public function getBiddedItems(): array {
        $now = AppTime::get_current_datetime();
        $sql = "SELECT DISTINCT v.* FROM v_items_status v 
                JOIN bids b ON v.id = b.item
                WHERE b.owner = :user_id 
                AND v.end_at > :now 
                AND (v.not_purchased_direct_sale OR (v.is_auction AND (NOT v.has_buy_now OR NOT v.buy_now_reached)))
                ORDER BY v.end_at ASC";
                
        $query = self::execute($sql, [
            'user_id' => $this->id, 
            'now' => $now
        ]);
        
        $items = [];
        foreach ($query->fetchAll() as $row) {
            $items[] = Item::makeItemFromRow($row);
        }
        return $items;
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
                $sql = "SELECT DISTINCT v.*
                FROM v_items_status v join bids b ON v.id = b.item
                WHERE  v.owner = :user_id AND (v.buy_now_reached OR (v.has_bids AND end_at < :now))
                ORDER BY b.created_at DESC";
        return $this->fetchItems($sql, ['now' => $now,'user_id'=> $this->id]);
    }


        private function fetchItems(string $sql, array $params): array {
        $query = self::execute($sql, $params);
        $items = [];
        foreach ($query->fetchAll() as $row) {
            $items[] = Item::makeItemFromRow($row);
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
            $item->getOwner()->getId(),
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

    public function hasBidIn(Item $item) : bool {
        $sql = "SELECT COUNT(*) FROM `v_items_status` v join bids b on b.item = v.id WHERE b.item = ? and b.owner = ?";
        $res = self::execute($sql, [$item->getId(),$this->id]);
        $count = $res->fetchColumn(); 
    
        return $count > 0;
    }

    public function getMyMostLoyalBidder() : ?User {

        $buyerStats = [];
        if(!empty($this->getSoldItems())){
            foreach ($this->getSoldItems() as $soldItem) {
                $winner = $soldItem->getWinner();
            
                if ($winner != null) {
                    $winnerId = $winner->getId();
                    $buyerStats[$winnerId] = ($buyerStats[$winnerId] ?? 0) + 1;
                }
            }

            
            $mostLoyalBuyerId = null;
                if (!empty($buyerStats)) {
                    arsort($buyerStats); 
                    $mostLoyalBuyerId = array_key_first($buyerStats);
                }
                if($mostLoyalBuyerId == null)
                    return null;
                 return  $this->getUserById($mostLoyalBuyerId);  
            }else
            return null;
        
    }

    public function getPurchasesItems(): array {
         $purchases = [];
        foreach ($this->getAllOtherItemSold() as $item) {
           if($item->getWinner()->getId()==$this->id){
                $purchases[]=$item;
           }
        }
        return $purchases;
    }

    private function getAllOtherItemSold(): array {
        $now = AppTime::get_current_datetime();
                $sql = "SELECT DISTINCT v.*
                FROM v_items_status v join bids b ON v.id = b.item
                WHERE  v.owner != :user_id AND (v.buy_now_reached OR (v.has_bids AND end_at < :now))
                ORDER BY b.amount DESC";
        return $this->fetchItems($sql, ['now' => $now,'user_id'=> $this->id]);
    }

     public function getMyItemsTopSeller() : ?User {
    
        $sellerStats = [];
        if(!empty($this->getPurchasesItems())){
            foreach ($this->getPurchasesItems() as $purchasesItem) {
                $seller = $purchasesItem->getOwner();
            
                if ($seller != null) {
                    $sellerId = $seller->getId();
                    $sellerStats[$sellerId] = ($sellerStats[$sellerId] ?? 0) + 1;
                }
            }

            
            $topSellerId = null;
                if (!empty($sellerStats)) {
                    arsort($sellerStats); 
                    $topSellerId = array_key_first($sellerStats);
                    
                }
                if($topSellerId == null)
                    return null;
                 return  $this->getUserById($topSellerId);  
        }else
            return null;
           
                
    }


   

}