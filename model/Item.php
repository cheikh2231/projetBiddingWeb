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
        private ?bool $is_auction,
        private bool $has_bids,
        private bool $has_buy_now,
        private string $end_at,
        private bool $not_purchased_direct_sale,
        private bool $is_direct_sale,
        private ?float $max_bid = null,
        private ?float $buy_now_price = null,
        private ?int $duration_days = null,
        private ?float $starting_bid = null,
        private bool $buy_now_reached = false 
    ) {}


    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getOwner(): int { return $this->owner; }
    public function getCreatedAt(): string { return $this->created_at; }
    public function getIsAuction(): ?bool { return $this->is_auction; }
    public function getHasBids(): bool { return $this->has_bids; }
    public function getHasBuyNow(): bool { return $this->has_buy_now; }
    public function getEndAt(): string { return $this->end_at; }
    public function getNotPurchasedDirectSale(): bool { return $this->not_purchased_direct_sale; }
    public function getIsDirectSale(): bool { return $this->is_direct_sale; }
    public function getMaxBid(): ?float { return $this->max_bid; }
    public function getBuyNowPrice(): ?float { return $this->buy_now_price; }
    public function getDurationDays(): ?int { return $this->duration_days; }
    public function getStartingBid(): ?float { return $this->starting_bid; }
    public function getBuyNowReached(): bool { return $this->buy_now_reached; }



    public function setId(int $id): void { $this->id = $id; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setOwner(int $owner): void { $this->owner = $owner; }
    public function setCreatedAt(string $created_at): void { $this->created_at = $created_at; }
    public function setIsAuction(?bool $is_auction): void { $this->is_auction = $is_auction; }
    public function setHasBids(bool $has_bids): void { $this->has_bids = $has_bids; }
    public function setHasBuyNow(bool $has_buy_now): void { $this->has_buy_now = $has_buy_now; }
    public function setEndAt(string $end_at): void { $this->end_at = $end_at; }
    public function setNotPurchasedDirectSale(bool $val): void { $this->not_purchased_direct_sale = $val; }
    public function setIsDirectSale(bool $is_direct_sale): void { $this->is_direct_sale = $is_direct_sale; }
    public function setMaxBid(?float $max_bid): void { $this->max_bid = $max_bid; }
    public function setBuyNowPrice(?float $price): void { $this->buy_now_price = $price; }
    public function setDurationDays(?int $days): void { $this->duration_days = $days; }
    public function setStartingBid(?float $bid): void { $this->starting_bid = $bid; }
    public function setBuyNowReached(bool $reached): void { $this->buy_now_reached = $reached; }

    public function validate(): array {
        $errors = [];
        $errors = array_merge($errors, self::validateTitle($this->title));
        $errors = array_merge($errors, self::validateDescription($this->description));
        $errors = array_merge($errors, self::validateDuration($this->duration_days));
        $errors = array_merge($errors, $this->validateStartingAndSale());
        return $errors;
    }

    public static function validateTitle(string $title) : array {
        $errors = [];
        if (empty($title)) {
            $errors[] = "Title is required";
        } if (strlen($title) < 3 || strlen($title) > 255) {
            $errors[] = "Title length must be between 3 and 255 characters.";
        }
        return $errors;
    }

    public static function validateDuration($duration) : array {
        $errors = [];
        $d = (int)$duration;
        if ($d < 1 || $d > 365) {
            $errors[] = "Duration must be between 1 and 365 days.";
        }
        return $errors;
    }

    public static function validateDescription(string $description) : array {
        $errors = [];
        if (empty($description) || strlen($description) < 3) {
            $errors[] = "Description must be greater than 3 characters.";
        }
        return $errors;
    }

    public function validateStartingAndSale() : array {
        $errors = [];

        if (($this->starting_bid === null || $this->starting_bid <= 0) && (!$this->is_direct_sale)) {
            $errors[] = "An item must have at least a starting bid or a buy now price.";
        }else if(($this->starting_bid !== null && $this->starting_bid > 0 && $this->is_direct_sale) ){
            $errors[] = "cannot create both auction and direct sale";
        }
        return $errors;
    }

    public function validatePurchasePrice() : array {
        $errors = [];
        if (!$this->is_direct_sale) {
            if ($this->starting_bid > 0 && $this->buy_now_price > 0) {
                if ($this->buy_now_price <= $this->starting_bid) {
                    $errors[] = "The buy now price must be greater than the starting bid.";
                }
            }
        }
        return $errors;
    }
    public static function getItemWithId(int $id) : ?Item {
        $sql = "SELECT * FROM v_items_status WHERE id = ?";
        $query = self::execute($sql, [$id]);
        $i = $query->fetch(); 
        if ($i === false) {
           return null;
        }
        return self::makeItemFromRow($i);
    }

    public static function getActiveItems(int $user_id) : array {
        $now = AppTime::get_current_datetime();
        $sql = "SELECT v.* FROM v_items_status v 

                WHERE v.end_at > :now 
                AND (v.not_purchased_direct_sale OR (v.is_auction AND (NOT v.has_buy_now OR NOT v.buy_now_reached)))
                AND v.id NOT IN (SELECT DISTINCT item FROM bids WHERE owner = :user_id)
                AND v.owner != :user_id 
                ORDER BY v.end_at ASC";
                

        $query = self::execute($sql, ['now' => $now, 'user_id' => $user_id]);
        $items = [];
        foreach ($query->fetchAll() as $row) {
            $items[] = self::makeItemFromRow($row);
        }
        return $items;
    }


    public static function getAllActivesItems() : array {
        $now = AppTime::get_current_datetime();
        $sql = "SELECT v.* FROM v_items_status v 
                WHERE (v.not_purchased_direct_sale OR (v.is_auction AND v.end_at > :now AND (NOT v.has_buy_now OR NOT v.buy_now_reached)))
                ORDER BY v.end_at ASC";
        $query = self::execute($sql, ['now' => $now]);
        $items = [];
        foreach ($query->fetchAll() as $row) {
            $items[] = self::makeItemFromRow($row);
        }
        return $items;
    }


    public static function getUserActiveItems(int $user_id) : array {
        $now = AppTime::get_current_datetime();
        $sql = "SELECT DISTINCT v.* FROM v_items_status v JOIN bids b ON v.id = b.item
                WHERE b.owner = :user_id AND (v.not_purchased_direct_sale OR (v.is_auction AND v.end_at > :now AND (NOT v.has_buy_now OR NOT v.buy_now_reached)))
                ORDER BY v.end_at ASC";
        $query = self::execute($sql, ['now' => $now, 'user_id' => $user_id]);
        $items = [];
        foreach ($query->fetchAll() as $row) {
            $items[] = self::makeItemFromRow($row);
        }
        return $items;
    }

    private static function makeItemFromRow(array $i) : Item {
        return new Item(
            (int)$i["id"],
            (string)$i["title"],
            (string)$i["description"],
            (int)$i["owner"],
            (string)$i["created_at"],
            (bool)($i["is_auction"] ?? false),
            (bool)($i["has_bids"] ?? false),
            (bool)($i["has_buy_now"] ?? false),
            (string)$i["end_at"],
            (bool)($i["not_purchased_direct_sale"] ?? false),
            (bool)($i["is_direct_sale"] ?? false),
            $i["max_bid"] !== null ? (float)$i["max_bid"] : null,
            $i["buy_now_price"] !== null ? (float)$i["buy_now_price"] : null,
            $i["duration_days"] !== null ? (int)$i["duration_days"] : null,
            $i["starting_bid"] !== null ? (float)$i["starting_bid"] : null,
            (bool)($i["buy_now_reached"] ?? false)
        );
    }

    public function update() : bool {
        try {
            $sql = "UPDATE items SET title = ?, description = ?, duration_days = ?, starting_bid = ?, buy_now_price = ? WHERE id = ?"; 
            $res = self::execute($sql, [
                $this->title,
                $this->description,
                $this->duration_days,
                ($this->starting_bid > 0) ? $this->starting_bid : null,
                $this->buy_now_price,
                $this->id
            ]);
            return $res !== false;
        } catch (Exception $e) {
            return false;
        }
    }

   

    public function delete() : bool {
        try {
            self::execute("DELETE FROM bids WHERE item = ?", [$this->id]);
            self::execute("DELETE FROM item_pictures WHERE item = ?", [$this->id]);
            $res = self::execute("DELETE FROM items WHERE id = ?", [$this->id]);
            return $res !== false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function isClosed() : bool {
        $now = AppTime::get_current_datetime();
        return ($now >= $this->end_at) || $this->buy_now_reached;

    }

    public function isSold() : bool {
        if ($this->buy_now_reached) return true;
        if ($this->isClosed()) return $this->has_bids;
        return false;
    }

    public function getPictures() {
        $query = self::execute("SELECT picture_path FROM item_pictures WHERE item = :id", ["id" => $this->id]);
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function itemUserName(int $id_owner) : string {
        $res = self::execute("SELECT pseudo FROM users WHERE id = ?", [$id_owner]);
        return $res->fetchColumn(); 
    }


    public static function getUserPic(int $id) : ?string {
        $res = self::execute("SELECT picture_path FROM users WHERE id = ?", [$id]);
        $path = $res->fetchColumn();
        return $path ?: null;
    }

    public static function getUserRole(int $id) : ?string {
        $res = self::execute("SELECT role FROM users WHERE id = ?", [$id]);
        $role = $res->fetchColumn();
        return $role ?: null;
    }

    public static function getLastItemsId() : int {
        $res = self::execute("SELECT id FROM items ORDER BY id DESC LIMIT 1", []);
        $id = $res->fetchColumn();
        return $id ? (int)$id : 0;
    }


    public function getFinalBuyPrice() : float {
        $val ;
        if($this->isClosed()){
            if($this->getBuyNowReached()){
                $val = $this->getBuyNowPrice();
            }elseif($this->getHasBids()){
                $val = $this->getMaxBid();
            }
        }
        if($val===null)
            return false;
        return $val;
    }

    public function getWinner() : ?User {
        $bids = $this->getBids();
        $user = null;
        if($this->isClosed()){
            if($this->getBuyNowReached() || $this->getHasBids()){
                $user =  User::getUserById($bids[0]->getOwner());
            }
        }
        return $user;
    }
    public function getMaxBidder() : ?User {
        $sql = "SELECT owner FROM bids WHERE item = ? ORDER BY amount DESC, created_at ASC LIMIT 1";
        $res = self::execute($sql, [$this->id]);
        $ownerId = $res->fetchColumn();
        return $ownerId !== false ? User::getUserById($ownerId) : null;
    }

    public function getBids(): ?array {
        $allbid = [];
       
        $sql = "SELECT * FROM bids b WHERE item = :id_item ORDER BY b.created_at DESC";
        $res = self::execute($sql, ['id_item' =>$this->id]);
        $bids = $res->fetchAll(); 
        
        foreach ($bids as $i) {
            $allbid[] = new Bids(
                $i["owner"], 
                $i["item"], 
                $i["created_at"], 
                $i["amount"]
            );
        }
        
        return empty($allbid) ? null : $allbid;
    }

}