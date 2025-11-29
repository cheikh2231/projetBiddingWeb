<?php 

require_once "framework/Model.php";



class Item extends Model{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public int $owner,
        public string $created_at,
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

    public static function getItems() : array{
        $query = self::execute("SELECT * FROM items", []);
        $res = $query->fetchAll();
        $items = [];
        foreach($res as $i){
            $items[]=new item($i["id"],$i["title"],$i["description"],$i["owner"],$i["created_at"],$i["buy_now_price"],$i["duration_days"],$i["starting_bid"]);
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

}