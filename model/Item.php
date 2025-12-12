<?php 

require_once "framework/Model.php";
require_once "utils/AppTime.php";



class Item extends Model{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public int $owner,
        public string $created_at,
        public bool $is_auction,
        public bool $has_bids,
        public bool $has_buy_now,
        public ?float $max_bid = null,
        public ?float $buy_now_price=null,
        public ?int $duration_days=null,
        public ?float $starting_bid = null
        
    ) {}
   
    public function validate(): array {
        $errors = [];

        $errors = array_merge($errors, $this->validateTitle($this->title));
        $errors = array_merge($errors, $this->validateDescription($this->description));

        if ($this->buy_now_price !== null) {
            $errors = array_merge($errors, $this->validatePrice($this->buy_now_price));
        }

        if ($this->starting_bid !== null) {
            $errors = array_merge($errors, $this->validatePrice($this->starting_bid));
        }

        return $errors;
    }


    private function validateTitle(String $title) : array{
        $errors = [];
         if(empty($title)){
            $errors[]="Title is requiered";
        }elseif (strlen($title) < 3 || strlen($title) > 255) {
            $errors[] = "Title length must be between 3 and 255 characters.";
        }

       
        return $errors;
    }

    private function validateDescription(String $description) : array{
        $errors = [];
        if(strlen($description)<3){
            $errors[]="Description must be greater than 3";
        }
        return $errors;
    }

    private function validatePrice(float|string $price) : array{
        $errors = [];
        $priceStr = (string)$price;

        if (!preg_match('/^\d{1,8}(\.\d{1,2})?$/', $priceStr)) {
            $errors[]="Le prix doit être un DECIMAL(10,2).";
        }
    return $errors;

    }



public static function getActiveItems(int $user_id) : array {
    $now = AppTime::get_current_datetime();

    $sql = "
        SELECT v.*
        FROM v_items_status v
        WHERE (v.not_purchased_direct_sale
             OR (
                 v.is_auction
                 AND v.end_at > :now
                 AND (NOT v.has_buy_now OR NOT v.buy_now_reached)
               )
             )
             AND v.id NOT IN (
                   SELECT DISTINCT item FROM bids WHERE owner = :user_id
               )
             AND v.owner != :user_id 
    ";

    $query = self::execute($sql, ['now' => $now, 'user_id' => $user_id]);

    $res = $query->fetchAll();
    $items = [];
    foreach ($res as $i) {
        $items[] = new Item(
             $i["id"], $i["title"], $i["description"], $i["owner"], $i["created_at"], 
             $i["is_auction"], $i["has_bids"], $i["has_buy_now"], 
             $i["max_bid"], $i["buy_now_price"], $i["duration_days"], $i["starting_bid"]
        );
    }
    return $items;
}

public static function getUserActiveItems(int $user_id) : array {
    $now = AppTime::get_current_datetime();

    $sql = "
        SELECT DISTINCT v.*
        FROM v_items_status v
        JOIN bids b ON v.id = b.item
        WHERE b.owner = :user_id 
          AND (
                v.not_purchased_direct_sale
                OR (
                    v.is_auction
                    AND v.end_at > :now
                    AND (NOT v.has_buy_now OR NOT v.buy_now_reached)
                  )
              )
        ORDER BY v.end_at DESC
    ";


    $query = self::execute($sql, ['now' => $now, 'user_id' => $user_id]);

    $res = $query->fetchAll();
    $items = [];
    foreach ($res as $i) {
        $items[] = new Item(
             $i["id"], $i["title"], $i["description"], $i["owner"], $i["created_at"], 
             $i["is_auction"], $i["has_bids"], $i["has_buy_now"], 
             $i["max_bid"], $i["buy_now_price"], $i["duration_days"], $i["starting_bid"]
        );
    }
    return $items;
}



    public  function getPictures() {
    $query = self::execute(
        "SELECT picture_path FROM item_pictures WHERE item = :id",
        ["id" => $this->id]
    );

    return $query->fetchAll(PDO::FETCH_COLUMN);
    }


    public static function itemUserName(int $id_owner) : String {
        $sql = ("SELECT pseudo
                            FROM users
                            WHERE id = ?"
                            );
        $res = self::execute($sql, array($id_owner));
        $name = $res->fetchColumn(); 

        return $name;
    }

    public static function end_at(int $id_owner) : DateTime {
        $sql = ("SELECT v.end_at
                            FROM v_items_status v
                            WHERE id = ?"
                            );
        $res = self::execute($sql, array($id_owner));
        $date = $res->fetchColumn(); 
        
    return new DateTime($date);
    }


    // SELECT b.owner,b.amount,b.item FROM `bids` b WHERE b.amount IN (SELECT max(amount) from bids GROUP by item)

    public static function max_bidder(int $item) : int{
        $sql = ("SELECT b.owner FROM `bids` b WHERE b.item = ? and b.amount IN (SELECT max(amount) from bids GROUP by item)");
        $res = self::execute($sql, array($item));
        $bidder = $res->fetchColumn(); 
        
      return $bidder;
    }

}