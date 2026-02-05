<?php 

require_once "framework/Model.php";
require_once "utils/AppTime.php";

class Item extends Model {
    public function __construct(
       
        private ?int $id,
        private string $title,
        private string $description,
        private int $owner,
        private string $created_at,
        private ?int $duration_days,
        private ?float $starting_bid = null,
        private ?float $buy_now_price = null,

        
        private ?string $end_at = null,
        private ?float $max_bid = null,
        private bool $buy_now_reached = false,
        private bool $has_bids = false,
        private bool $is_auction = false,
        private bool $is_direct_sale = false,
        private bool $not_purchased_direct_sale = false,
        private bool $has_buy_now = false
    ) {}

    
    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getOwner(): User { return User::getUserById($this->owner); } 
    public function getCreatedAt(): string { return $this->created_at; }
    public function getDurationDays(): ?int { return $this->duration_days; }
    public function getStartingBid(): ?float { return $this->starting_bid; }
    public function getBuyNowPrice(): ?float { return $this->buy_now_price; }

    
    public function getEndAt(): string { return $this->end_at ?? ""; }
    public function getMaxBid(): ?float { return $this->max_bid; }
    public function getBuyNowReached(): bool { return $this->buy_now_reached; }
    public function getHasBids(): bool { return $this->has_bids; }
    public function getIsAuction(): bool { return $this->is_auction; }
    public function getIsDirectSale(): bool { return $this->is_direct_sale; }
    public function getHasBuyNow(): bool { return $this->has_buy_now; }
    public function getNotPurchasedDirectSale(): bool { return $this->not_purchased_direct_sale; }

    public function setIsDirectSale(bool $bool): void { $this->is_direct_sale = $bool; }


    public function setTitle(string $title): void {
        $this->title = trim($title);
    }

    public function setDescription(string $description): void {
        $this->description = trim($description);
    }

    public function setDurationDays(?int $duration): void {
        $this->duration_days = $duration;
    }

    public function setStartingBid(?float $amount): void {
        $this->starting_bid = ($amount > 0) ? $amount : null;
    }

    public function setBuyNowPrice(?float $amount): void {
        $this->buy_now_price = ($amount > 0) ? $amount : null;
    }

    public function validate(): array {
        $errors = [];
        
        
        $config = parse_ini_file('config/dev.ini');

       
        $minTitle    = $config['title_min_length'];
        $maxTitle    = $config['title_max_length'];
        $minDuration = $config['duration_min_value'];
        $maxDuration = $config['duration_max_value'];

        $minDescription = $config['min_description_if_not_empty'];
        $titleLen = strlen($this->title);
        if ($titleLen < $minTitle) {
            $errors['title'][] = "Title must be at least $minTitle characters.";
        } elseif ($titleLen > $maxTitle) {
            $errors['title'][] = "Title cannot exceed $maxTitle characters.";
        }
        
        if (empty($this->title)) {
            $errors['title'][] = "Title is required.";
        }

        if ($this->titleExistsForOwner()) {
            $errors['title'][] = "You already have an item with this title.";
        }

       
        if (!empty($this->description) && strlen($this->description) < $minDescription) {
            $errors['description'][] = "Description must be at least 3 characters.";
        }

        
        if ($this->duration_days < $minDuration || $this->duration_days > $maxDuration) {
            $errors['duration'][] = "Duration must be between $minDuration and $maxDuration days.";
        }

        
        $hasStarting = ($this->starting_bid !== null && $this->starting_bid > 0);
        $hasBuyNow   = ($this->buy_now_price !== null && $this->buy_now_price > 0);

        if (!$hasStarting && !$hasBuyNow) {
            $errors['startingAndOrSale'][] = "An item must have a starting bid or a buy now price.";
        } else if ($hasStarting && $this->is_direct_sale) {
            $errors['startingAndOrSale'][] = "Cannot create both auction and direct sale.";
        } else if ($hasStarting && $hasBuyNow && $this->buy_now_price <= $this->starting_bid) {
            $errors['instant_price'][] = "The buy now price must be strictly greater than the starting bid.";
        }

        return array_filter($errors, fn($e) => !empty($e));
    }

    private function titleExistsForOwner(): bool {
        $sql = "SELECT COUNT(*) FROM items WHERE title = ? AND owner = ? AND id != ?";
        $query = self::execute($sql, [$this->title, $this->owner, $this->id]);
        return $query->fetchColumn() > 0;
    }

    
    public function persist(): array {
        $errors = $this->validate();
        
        if (empty($errors)) {
            if ($this->id === null) {
                
                $sql = "INSERT INTO items (title, description, owner, created_at, duration_days, starting_bid, buy_now_price) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                self::execute($sql, [
                    $this->title, $this->description, $this->owner, $this->created_at, 
                    $this->duration_days, $this->starting_bid, $this->buy_now_price
                ]);
                
                
                $this->id = self::lastInsertId();

               
                $path = "uploads/items/" . $this->id;
                
                if (!is_dir($path)) {
                    
                    if (!mkdir($path, 0755, true)) {
                        $errors['folder'] = "Erreur lors de la création du dossier de stockage.";
                    }
                }
                
            } else {
                $this->update();
            }
        }
        
        return $errors;
    }

    public function update(): bool {
        $sql = "UPDATE items SET title = ?, description = ?, duration_days = ?, starting_bid = ?, buy_now_price = ? WHERE id = ?"; 
        return self::execute($sql, [
            $this->title, $this->description, $this->duration_days, 
            $this->starting_bid, $this->buy_now_price, $this->id
        ]) !== false;
    }

    
    public function isClosed(): bool {
        $now = AppTime::get_current_datetime();
        return ($now >= $this->getEndAt()) || $this->buy_now_reached;
    }

    public function delete(): bool {

        if ($this->has_bids) return false;

        $pictures = $this->getPictures(); 

       
        self::execute("DELETE FROM bids WHERE item = ?", [$this->id]);
        self::execute("DELETE FROM item_pictures WHERE item = ?", [$this->id]);
        $res = self::execute("DELETE FROM items WHERE id = ?", [$this->id]) !== false;

        
        if ($res) {
            $itemDirectory = "uploads/items/" . $this->id;

            
            foreach ($pictures as $path) {
               
                if (file_exists($path)) {
                    unlink($path);
                }
                
                
                $info = pathinfo($path);
                $thumbnailPath = $info['dirname'] . '/' . $info['filename'] . '_thumbnail.' . $info['extension'];
                
                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }
            }

           
            if (is_dir($itemDirectory)) {
                
                $remainingFiles = array_diff(scandir($itemDirectory), array('.', '..'));
                foreach ($remainingFiles as $file) {
                    unlink($itemDirectory . '/' . $file);
                }
                
                
                rmdir($itemDirectory);
            }
        }

        return $res;
    }

    
    public static function getItemById(int $id): ?Item {
        $query = self::execute("SELECT * FROM v_items_status WHERE id = ?", [$id]);
        $row = $query->fetch();
        return $row ? self::makeItemFromRow($row) : null;
    }

    public static function makeItemFromRow(array $i): Item {
        return new Item(
            (int)$i["id"],
            $i["title"],
            $i["description"] ?? "",
            (int)$i["owner"],
            $i["created_at"],
            (int)$i["duration_days"],
            $i["starting_bid"] ? (float)$i["starting_bid"] : null,
            $i["buy_now_price"] ? (float)$i["buy_now_price"] : null,
            $i["end_at"] ?? null,
            $i["max_bid"] ? (float)$i["max_bid"] : null,
            (bool)($i["buy_now_reached"] ?? false),
            (bool)($i["has_bids"] ?? false),
            (bool)($i["is_auction"] ?? false),
            (bool)($i["is_direct_sale"] ?? false),
            (bool)($i["not_purchased_direct_sale"] ?? false),
            (bool)($i["has_buy_now"] ?? false)
        );
    }

    public static function getAllActivesItems(): array {
        $now = AppTime::get_current_datetime();
        $sql = "SELECT * FROM v_items_status 
                WHERE end_at > :now 
                AND (not_purchased_direct_sale OR (is_auction AND (NOT has_buy_now OR NOT buy_now_reached)))
                ORDER BY end_at ASC";
                
        $query = self::execute($sql, ['now' => $now]);
        
        $items = [];
        foreach ($query->fetchAll() as $row) {
            $items[] = self::makeItemFromRow($row);
        }
        return $items;
    }

    public function getPictures(): array {
        $sql = "SELECT picture_path FROM item_pictures WHERE item = :id";
        $query = self::execute($sql, ["id" => $this->id]);
       
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getMaxBidder() : ?User {

        if (!$this->has_bids) {
            return null;
        }


        $lastBid = $this->getLastBid();
        return $lastBid !== null ? $lastBid->getOwner():null ;
    }

    public function getWinner() : ?User {
        return $this->isClosed()?$this->getMaxBidder():null;
    }


    public function getBids() : array {
        $sql = "SELECT * FROM bids WHERE item = ? ORDER BY amount DESC, created_at ASC";
        $res = self::execute($sql, [$this->id]);
        $bids = [];
        foreach ($res->fetchAll() as $row) {
            $bids[] = new Bids($row['owner'], $row['item'], $row['created_at'], $row['amount']);
        }
        return $bids;
    }

   
    private function getLastBid(): ?Bids {
        $bids = $this->getBids();
        return !empty($bids) ? $bids[0] : null;
    }

    
    public function getFinalBuyPrice() : float {
        if ($this->buy_now_reached) {
            return $this->buy_now_price;
        }
        $lastBid = $this->getLastBid();
        return $lastBid ? $lastBid->getAmount() : 0.0;
    }


    public function validate_photo(array $file) : array {
        $errors = [];
    
        $max_size = 5 * 1024 * 1024; 
        $valid_types = ["image/gif", "image/jpeg", "image/png", "image/webp"];

        if (!isset($file['tmp_name']) || $file['tmp_name'] == '') {
            return $errors;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur lors du transfert du fichier.";
            return $errors;
        }

        
        if ($file['size'] > $max_size) {
            $errors[] = "Le fichier est trop lourd (Max 5MB).";
        }

        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $valid_types)) {
            $errors[] = "Format non supporté : JPEG, PNG, GIF ou WEBP uniquement.";
        }

        return $errors;
    }

    public function process_image(string $tmp_path, string $destination_folder, string $filename, int $max_w, int $max_h, string $suffix = "") : string|false {
    if (!$tmp_path) return false;
        $info = getimagesize($tmp_path);
        

        list($old_w, $old_h) = $info;
        $mime = $info['mime'];

        
        switch ($mime) {
            case 'image/jpeg': $src = imagecreatefromjpeg($tmp_path); break;
            case 'image/png':  $src = imagecreatefrompng($tmp_path);  break;
            case 'image/gif':  $src = imagecreatefromgif($tmp_path);  break;
            case 'image/webp': $src = imagecreatefromwebp($tmp_path); break;
            default: return false;
        }

    
        $ratio = min($max_w / $old_w, $max_h / $old_h);
        
    
        $new_w = ($ratio < 1) ? (int)round($old_w * $ratio) : $old_w;
        $new_h = ($ratio < 1) ? (int)round($old_h * $ratio) : $old_h;

        
        $dst = imagecreatetruecolor($new_w, $new_h);

        
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);

    
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_w, $new_h, $old_w, $old_h);


        if (!is_dir($destination_folder)) {
            mkdir($destination_folder, 0777, true);
        }

        $file_name_only = pathinfo($filename, PATHINFO_FILENAME);
        $final_name = $file_name_only . $suffix . ".jpg";
        $full_path = $destination_folder . "/" . $final_name;

        imagejpeg($dst, $full_path, 85);

    
        imagedestroy($src);
        imagedestroy($dst);

        return $full_path;
    }

    public function addPicture(String $path) : void {
        $sql = "INSERT INTO item_pictures (item, priority, picture_path) 
                VALUES (?, ?, ?)";
        
        self::execute($sql, [
            $this->id, 
            $this->getLastPriority(), 
            $path
        ]);
    }

    public function getLastPriority() : int {
        
        $sql = "SELECT MAX(priority) FROM item_pictures WHERE item = ?";
        
        
        $query = self::execute($sql, [$this->id]);
        
        
        $max = $query->fetchColumn();
        
       
        return (int)$max + 1;
    }

    public function deleteImage(?int $priority): bool {
        if ($priority === null) return false;

       
        $sqlFile = "SELECT picture_path FROM item_pictures WHERE item = ? AND priority = ?";
        $query = self::execute($sqlFile, [$this->id, $priority]);
        $path = $query->fetchColumn();
        if ($path && file_exists($path)) {
            unlink($path);
        }
        $info = pathinfo($path);
        $thumbnailPath = $info['dirname'] . '/' . $info['filename'] . '_thumbnail.' . $info['extension'];

        if (file_exists($thumbnailPath)) {
            unlink($thumbnailPath);
        }

        
        $success = self::execute("DELETE FROM item_pictures WHERE item = ? AND priority = ?", [$this->id, $priority]) !== false;

        
        if ($success) {
            $this->updatePriority($priority);
        }
                                                                                                                                                                                                                                
        return $success;
    }

    public function updatePriority(int $deletedPriority): void {
        
        $sql = "UPDATE item_pictures 
                SET priority = priority - 1 
                WHERE item = ? AND priority > ?";
        
        self::execute($sql, [$this->id, $deletedPriority]);
    }


    public function swapPic(int $iBefore, int $i): void {

        $sql1 = "UPDATE item_pictures SET priority = -1 WHERE item = ? AND priority = ?";
        self::execute($sql1, [$this->id, $iBefore]);

        if($sql1){
            $sql2 = "UPDATE item_pictures SET priority = ? WHERE item = ? AND priority = ?";
            self::execute($sql2, [$iBefore, $this->id, $i]);

            if($sql2){
                $sql3 = "UPDATE item_pictures SET priority = ? WHERE item = ? AND priority = ?";
                self::execute($sql3, [$i, $this->id, -1]);
            }
        }
    }
}