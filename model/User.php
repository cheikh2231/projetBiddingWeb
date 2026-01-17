<?php
class User extends Model {
    public function __construct(
        public int $id,
        public string $name, 
        public string $email, 
        public string $pseudo, 
        public string $password, 
        public string $role, 
        public ?string $picture_path, 
        public ?string $iban
    ) {}

    public static function getUserByPseudo(string $pseudo) : User|false {
        $query = self::execute("SELECT * FROM users WHERE pseudo = :pseudo", ["pseudo" => $pseudo]);
        $data = $query->fetch();
        if (!$data) return false;

        return new User($data["id"], $data["full_name"], $data["email"], $data["pseudo"], $data["password"], $data["role"], $data["picture_path"], $data["iban"]);
    }

    public static function getUserByEmail(string $email) : User|false {
        $query = self::execute("SELECT * FROM users WHERE email = :email", ["email" => $email]);
        $data = $query->fetch();
        if (!$data) return false;

        return new User($data["id"], $data["full_name"], $data["email"], $data["pseudo"], $data["password"], $data["role"], $data["picture_path"], $data["iban"]);
    }

    public static function getUserById(int $id): User|false {
        $query = self::execute("SELECT * FROM users WHERE id = :id", ["id" => $id]);
        $data = $query->fetch();
        if (!$data) return false;

        return new User($data["id"], $data["full_name"], $data["email"], $data["pseudo"], $data["password"], $data["role"], $data["picture_path"], $data["iban"]);
    }
                
    public static function getAllUsers() : array {
        $query = self::execute("SELECT * FROM users", []);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new User($row["id"], $row["full_name"], $row["email"], $row["pseudo"], $row["password"], $row["role"], $row["picture_path"], $row["iban"]);
        }
        return $results;
    }
    
    public static function validate_login(string $email, string $password) : array {
        $errors = [];
        $user = self::getUserByEmail($email);
        if ($user) {
            if (!self::check_password($password, $user->password)) {
                $errors[] = "Wrong password. Please try again.";
            }
        } else {
            $errors[] = "Can't find a member with the email '$email'. Please sign up.";
        }
        return $errors;
    }

    private static function check_password(string $clear_password, string $hash) : bool {
        return password_verify($clear_password, $hash);
    }

    public function myItemsAtives() : array {
        $now = AppTime::get_current_datetime();

        $sql = "
            SELECT DISTINCT v.*
            FROM v_items_status v
            WHERE v.owner = :user_id 
            AND (
                v.not_purchased_direct_sale
                OR (
                    v.is_auction
                    AND v.end_at > :now
                    AND (NOT v.has_buy_now OR NOT v.buy_now_reached)
                )
            )
            ORDER BY v.end_at ASC
        ";

        $query = self::execute($sql, [
            'now' => $now, 
            'user_id' => $this->id
        ]);

        $res = $query->fetchAll();
        $items = [];
        foreach ($res as $i) {
            $items[] = new Item(
                $i["id"], $i["title"], $i["description"], $i["owner"], $i["created_at"], 
                $i["is_auction"], $i["has_bids"], $i["has_buy_now"], $i["end_at"], $i["not_purchased_direct_sale"],
                $i["max_bid"], $i["buy_now_price"], $i["duration_days"], $i["starting_bid"]
            );
        }
        return $items;
    }

/**
     * Récupère les annonces de l'utilisateur qui sont clôturées SANS avoir été vendues.
     */
    public function getUnsoldItems() : array {
        $now = AppTime::get_current_datetime();
        
        // On récupère les items de l'utilisateur qui sont terminés (temps écoulé)
        // et qui n'ont pas été vendus par achat immédiat.
        $sql = "SELECT * FROM v_items_status 
                WHERE owner = :user_id 
                AND end_at < :now 
                AND buy_now_reached = 0";
        
        $query = self::execute($sql, ['user_id' => $this->id, 'now' => $now]);
        $data = $query->fetchAll();
        
        $unsold = [];
        foreach ($data as $row) {
            $item = new Item(
                $row["id"], $row["title"], $row["description"], $row["owner"], $row["created_at"], 
                $row["is_auction"], $row["has_bids"], $row["has_buy_now"], $row["end_at"], $row["not_purchased_direct_sale"],
                $row["max_bid"], $row["buy_now_price"], $row["duration_days"], $row["starting_bid"], $row["buy_now_reached"] 
            );
            
            // Un item est invendu s'il est terminé ET que isSold() est faux
            if (!$item->isSold()) {
                $unsold[] = $item;
            }
        }
        return $unsold;
    }

    public function getSoldItems() : array {
        $now = AppTime::get_current_datetime();
        $sql = "SELECT * FROM v_items_status 
                WHERE owner = :user_id 
                AND (end_at < :now OR buy_now_reached = 1)";
        
        $query = self::execute($sql, ['user_id' => $this->id, 'now' => $now]);
        $data = $query->fetchAll();
        
        $sold = [];
        foreach ($data as $row) {
            $item = new Item(
                $row["id"], $row["title"], $row["description"], $row["owner"], $row["created_at"], 
                $row["is_auction"], $row["has_bids"], $row["has_buy_now"], $row["end_at"], $row["not_purchased_direct_sale"],
                $row["max_bid"], $row["buy_now_price"], $row["duration_days"], $row["starting_bid"], $row["buy_now_reached"] 
            );
            
            if ($item->isSold()) {
                $sold[] = $item;
            }
        }
        return $sold;
    }
}
?>