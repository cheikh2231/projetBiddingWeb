<?php 

require_once "framework/Model.php";
require_once "utils/AppTime.php";


class Bids extends Model {



    public function __construct(  
        private int $owner,
        private int $item,
        private string $created_at,
        private float $amount) 
        {}
    

    public function getOwner(): User {
        return User::getUserById($this->owner);
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

