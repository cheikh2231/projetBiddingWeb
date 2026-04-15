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
     public static function getUserByFullname(string $fullname): User|false {
        $query = self::execute("SELECT * FROM users WHERE full_name = :fullname", ["fullname" => $fullname]);
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
                $errors['password'] = "Wrong password. Please try again.";
            }
        } else {
            $errors['email'] = "Can't find a member with the email '$email'. Please sign up.";
        }
        return $errors;
    }
    //methode validation SIGNUP
    public static function validate_signup(String $email, String $fullname, String $pseudo, String $password, String $confirmPassword) : array{
        $errors = [];
        $config = parse_ini_file('config/dev.ini');

        //verification tout les champs remplis
        if(empty($email)||empty($fullname)||empty($pseudo)||empty($password)||empty($confirmPassword)){
            $errors['champs'] = "All fields are required.";    
        }else{
            //email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                $errors['email']= "invalid email format.";
            else if(self::getUserByEmail($email))
                $errors['email'] = "Email already taken.";
            //fullname
            if(strlen($fullname) < $config['full_name_min'])
                $errors['fullName'] = "Full name must be more than 3 letters.";
            else if(self::getUserByFullname($fullname))
                $errors['fullName'] = "This name is already taken.";
            //pseudo
            if(self::getUserByPseudo($pseudo))
                $errors['pseudo'] = "Pseudo already taken.";
            else if((strlen($pseudo) < $config['full_name_min'] || strlen($pseudo) > $config['pseudo_max']))
                $errors['pseudo'] = "Lenght of the pseudo invalid";
            //mdp
            $pattern = $config['mdp_pattern'];
            if(!preg_match($pattern, $password))
                $errors['password'] = "The password must be at least 8 characters long and include 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.";
            //confirmPassword
            if($confirmPassword !== $password)
                $errors['confirmPassword'] = "Confirm password doesn't match.";
        }
        return $errors;
    }
    //methode validation edit profile
    public function validate_edit(String $email, String $fullname, String $pseudo, String $iban): array{
        $errors = [];
        $config = parse_ini_file('config/dev.ini');

        //email
        if(empty($email)){
            $errors['email']= "Email is required.";

        }else if($email != $this->email){
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                $errors['email']= "invalid email format.";
            if(self::getUserByEmail($email))
                $errors['email'] = "Email already taken.";
        }
        //fullname
        if(empty($fullname))
            $errors['fullName'] = "Full name is required";
        else if($fullname != $this->name){
            if(self::getUserByFullname($fullname))
                $errors['fullname'] = "Fullname already taken.";
            else if((strlen($fullname) < $config['full_name_min'] || strlen($fullname) > $config['pseudo_max']))
                $errors['fullname'] = "Lenght of the pseudo invalid";
        }
        //pseudo
        if(empty($pseudo))
            $errors['pseudo'] = "Pseudo is required.";
        else if($pseudo != $this->pseudo){
            if(self::getUserByPseudo($pseudo))
                $errors['pseudo'] = "Pseudo already taken.";
            else if((strlen($pseudo) < $config['full_name_min'] || strlen($pseudo) > $config['pseudo_max']))
            $errors['pseudo'] = "Lenght of the pseudo invalid";
        }
        //iban
        if(!empty($iban)){
            $ibanPattern = $config['iban_pattern'];
            if (!preg_match($ibanPattern, str_replace(' ', '', $iban))) {
                $errors['iban'] = "Invalid iban format";
            }
        }
        
        return $errors;
    }
    //methode validation edit profile
    public function validate_password(String $currentPassword, String $newPassword, String $confirmNewPassword): array{
        $errors = [];
        $config = parse_ini_file('config/dev.ini');

        if(empty($currentPassword)||empty($newPassword)||empty($confirmNewPassword)){
            $errors['champs'] = "All fields are required.";
        }else{
            //current password
            if (!password_verify($currentPassword, $this->getPassword()))
                $errors['currentPassword'] = "Current password doesn't match";
            //new password
            $pattern =  $config['mdp_pattern'];
            if(!preg_match($pattern, $newPassword))
                $errors['newPassword'] = "The password must be at least 8 characters long and include 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.";
            //confirmPassword
            if($confirmNewPassword !== $newPassword)
                $errors['confirmNewPassword'] = "Confirm password doesn't match.";
        }
        return $errors;
    }
    //fonction validation image de profile
    public function validate_photoDeProfil(array $file) : array{
        $errors = [];
        if(isset($_FILES['image'])){
            if($file['error']!=0)
                $errors ['picture']= "Error while uploading picture.";

            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $validFormat = ["png", "gif", "jpeg", "webp", "jpg"];

            if (!in_array($extension, $validFormat)) 
                $errors['picture'] = "Invalid format";
                    
                
            // verifacation taille du fichier (5242880 = 5mb)
             $config = parse_ini_file('config/dev.ini');
            $taille_image = $config['taille_image'];

            if($file['size']>$taille_image)
                $errors['picture'] = "invalid size.";
                    
            
        }else{    
            $errors['picture'] = "no image";
        }
        return $errors;
    }
    public function change_or_add_profile_picture(array $file) : void{
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

        $this->generate_thumbnail($folder.$picName);
        self::setPicturePath($folder.$picName);
        self::persist();
    }

    private function generate_thumbnail(string $sourcePath, int $maxWidth = 360, int $maxHeight = 360): void {
        if (!extension_loaded('gd')) {
        return; 
    }

    $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
    
    switch ($extension) {
        case 'jpeg': case 'jpg': $srcImage = imagecreatefromjpeg($sourcePath); break;
        case 'png':  $srcImage = imagecreatefrompng($sourcePath); break;
        case 'gif':  $srcImage = imagecreatefromgif($sourcePath); break;
        case 'webp': $srcImage = imagecreatefromwebp($sourcePath); break;
        default: return;
    }

    $width = imagesx($srcImage);
    $height = imagesy($srcImage);
    $ratio = min($maxWidth / $width, $maxHeight / $height);
    
    if ($ratio >= 1) {
        $newWidth = $width;
        $newHeight = $height;
    } else {
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);
    }

    $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

    if ($extension == 'png' || $extension == 'webp' || $extension == 'gif') {
        imagealphablending($thumbnail, false);
        imagesavealpha($thumbnail, true);
    }

    imagecopyresampled($thumbnail, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    $thumbPath = str_replace('.' . $extension, '_thumbnail.' . $extension, $sourcePath);
    
    switch ($extension) {
        case 'jpeg': case 'jpg': imagejpeg($thumbnail, $thumbPath, 85); break;
        case 'png':  imagepng($thumbnail, $thumbPath); break;
        case 'gif':  imagegif($thumbnail, $thumbPath); break;
        case 'webp': imagewebp($thumbnail, $thumbPath, 85); break;
    }

    imagedestroy($srcImage);
    imagedestroy($thumbnail);
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
    //fonction delete profile picture
    public function delete_profile_picture():void{
        $path = "uploads/users/" . $this->id;
        $files = glob($path . "/*"); 
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        self::setPicturePath(null);
        self::persist();
        rmdir("uploads/users/".$this->id);
    }



    //methode ajout/mise à jour User dans la database
    public function persist(): void{
        //mise à jour
        if($this->id !== null){
            self::execute("UPDATE Users SET email=:email, full_name=:fullname,pseudo=:pseudo, iban=:iban, password=:password, picture_path=:picture_path WHERE id=:id",["email"=>$this->email, "fullname"=>$this->name,"pseudo"=>$this->pseudo,"iban"=>$this->iban, "id"=>$this->id, "password"=>$this->password, "picture_path"=>$this->picture_path]);
        }        
        else{
        //ajout
            self::execute("INSERT INTO Users(full_name,email,pseudo,password,role,picture_path,iban) VALUES(:full_name,:email,:pseudo,:password,:role,:picture_path,:iban)", 
            ["full_name"=>$this->name,"email"=>$this->email,"pseudo"=>$this->pseudo,"password"=>$this->password,"role"=>$this->role,"picture_path"=>$this->picture_path,"iban"=>$this->iban]);
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
                ORDER BY end_at ASC"; 
        return $this->fetchItems($sql, ['now' => $now, 'user_id' => $this->id]);
    }

    public function getUnsoldItems(): array {
        $now = AppTime::get_current_datetime();
        $sql = "SELECT * FROM v_items_status 
                WHERE owner = :user_id AND end_at < :now AND buy_now_reached = 0 AND has_bids = 0 
                ORDER BY end_at ASC";
        return $this->fetchItems($sql, ['now' => $now, 'user_id' => $this->id]);
    }

    public function getSoldItems(): array {
        $now = AppTime::get_current_datetime();
                $sql = "SELECT DISTINCT v.*
                FROM v_items_status v join bids b ON v.id = b.item
                WHERE  v.owner = :user_id AND (v.buy_now_reached OR (v.has_bids AND end_at < :now))
                ORDER BY v.end_at DESC";
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
                ORDER BY v.end_at DESC";
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