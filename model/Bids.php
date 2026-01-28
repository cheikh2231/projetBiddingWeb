<?php 

require_once "framework/Model.php";
require_once "utils/AppTime.php";

class Bids extends Model {

    private int $owner;
    private int $item;
    private string $created_at;
    private float $amount;

    public function __construct(int $owner, int $item, string $created_at, float $amount) {
        $this->owner = $owner;
        $this->item = $item;
        $this->created_at = $created_at;
        $this->amount = $amount;
    }


    public function getOwner(): int {
        return $this->owner;
    }

    public function getItem(): int {
        return $this->item;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    public function getAmount(): float {
        return $this->amount;
    }

    public function setOwner(int $owner): void {
        $this->owner = $owner;
    }

    public function setItem(int $item): void {
        $this->item = $item;
    }

    public function setCreatedAt(string $created_at): void {
        $this->created_at = $created_at;
    }

    public function setAmount(float $amount): void {
        $this->amount = $amount;
    }



    public static function getBids(int $id_item): ?array {
        $allbid = [];
       
        $sql = "SELECT * FROM bids b WHERE item = :id_item ORDER BY b.created_at DESC";
        $res = self::execute($sql, ['id_item' => $id_item]);
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

    public function add(): bool {
        $sql = "INSERT INTO bids (owner, item, created_at, amount) 
                VALUES (:owner, :item, :created_at, :amount)";
    
        self::execute($sql, [
            'owner'      => $this->owner,
            'item'       => $this->item,
            'created_at' => $this->created_at,
            'amount'     => $this->amount
        ]);

        return true; 
    }
}