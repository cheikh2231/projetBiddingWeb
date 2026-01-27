<?php 

require_once "framework/Model.php";
require_once "utils/AppTime.php";



class Bids extends Model{
    public function __construct(
        public int $owner,
        public int $item,
        public string $created_at,
        public float $amount
        
    ) {}

        public static function getBids(int $id_item) : ?array {
            
            $allbid = [];
        $sql = ("select * from bids where item = :id_item group by item,amount,owner,created_at DESC ORDER BY `bids`.`created_at` DESC ");
        $res = self::execute($sql,[ 'id_item' => $id_item]);
        $bids = $res->fetchAll(); 
        foreach ($bids as $i) {
            $allbid[] = new Bids(
                $i["owner"],$i["item"],$i["created_at"],$i["amount"]
            );
        }
        if(!$allbid)
           return null;
        return $allbid;
    }

         public function add(): bool {
            $sql = "INSERT INTO bids (owner, item, created_at, amount) 
                VALUES (:owner, :item, :created_at, :amount)";
    
        self::execute($sql, [
            'owner' => $this->owner,
            'item' => $this->item,
            'created_at' => $this->created_at,
            'amount' => $this->amount
        ]);

        return true; 
    }


}  