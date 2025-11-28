<?php 

require_once "framework/Model.php";



class Item extends Model{
    public function __construct(
        public string $title,
        public string $description,
        public int $owner,
        public string $created_at,
        public ?float $buy_now_price=null,
        public ?int $duration_days=null,
        public ?float $starting_bid = null
    ) {}


    private function validateTitle(String $title) : array{
        $min = 3;
        $max = 255;
        $errors = [];
        if(strlen($title)<$min || strlen($title)>$max){
            $errors[]="Title length must be between 3 and 255 ";
        }

        if($title==null){
            $errors[]="Title is requiered";
        }
        return $errors;
    }

    private function validateDescription(String $description) : array{
        $min = 3;
        $errors = [];
        if(strlen($description)<$min){
            $errors[]="Title length must be between 3 and 255 ";
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
            $items[]=new item($i["title"],$i["description"],$i["owner"],$i["created_at"],$i["buy_now_price"],$i["duration_days"],$i["starting_bid"]);
        }
        return $items;
    }

}