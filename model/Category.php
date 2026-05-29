<?php 
require_once "framework/Model.php";
require_once "utils/AppTime.php";
require_once "framework/Configuration.php";

class Category extends Model{
    private int $id;
    private string $name;
    private int $priority;

    public function __construct(int $id, string $name, int $priority){
        $this->id = $id;
        $this->name = $name;
        $this->priority = $priority;
    }

    public function getId(): int{return $this->id;}
    public function getName(): string{return $this->name;}
    public function getPriority(): int{return $this->priority;}
    public function setName(string $name): void{ $this->name = $name;}
    
    private static function mapToCategories(array $data): Category{
        return new Category(
            $data["id"], 
            $data["name"], 
            (int) $data["priority"]
        );
    }

    public static function getAllCategories(): array{
        $query = self::execute("SELECT * FROM categories ORDER BY priority ASC", []);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = self::mapToCategories($row);
        }
        return $results;
    }

    public function isFirst() : bool{
        $query = self::execute("SELECT priority FROM categories WHERE id = ?", [$this->id]);
        return $query->fetchColumn() == 1;
    }

    public function isLast() : bool{
        $query = self::execute("SELECT MAX(priority) FROM categories", []);
        return $query->fetchColumn() == $this->priority;
    }

    public function createNewPriority() : int{
        $query = self::execute("SELECT MAX(priority) FROM categories", []);
        $data = $query->fetchColumn();
        return $data ? (int)$data+1 : 1; // Retourne 1 si la table est vide
    }

    public function swapPriority(int $iBefore, int $i): void{
        self::execute("UPDATE categories SET priority= -1 WHERE priority=?",[$iBefore]);
        self::execute("UPDATE categories SET priority=? WHERE priority=?",[$iBefore,$i]);
        self::execute("UPDATE categories SET priority=? WHERE priority=?",[$i,-1]);
    }

    public static function getCatById(int $id) : ?Category{
        $query = self::execute("SELECT * FROM categories WHERE id = ?", [$id]);
        $row = $query->fetch();
        return $row ? self::mapToCategories($row) : null;
    }

    public static function getCatByName(string $name): ?Category{
        $query = self::execute("SELECT * FROM categories WHERE name = ?", [$name]);
        $row = $query->fetch();
        return $row ? self::mapToCategories($row) : null;
    }

    public function getNbUse() : int{
        $query = self::execute("SELECT COUNT(*) FROM item_categories WHERE category = ?", [$this->id]);
        return $query ? (int)$query->fetchColumn() : 0;
    }

    public function isNotUsed() : bool{
        return $this->getNbUse() === 0;
    }

    public function delete() : bool{
        $res = self::execute("DELETE FROM categories WHERE id = ?", [$this->id]) !== false;
        if ($res) {
            $this->updatePriority();
        }
        return $res;
    }

    public function updatePriority(): void{
        $sql = "UPDATE categories 
                SET priority = priority - 1 
                WHERE priority > ? 
                ORDER BY priority ASC";
        self::execute($sql, [$this->priority]);
    }

    public static function updateAllPriorities(array $orderedIds): void{
        foreach ($orderedIds as $index => $id) {
            $tempPriority = -($index + 1);
            self::execute("UPDATE categories SET priority = ? WHERE id = ?", [$tempPriority, $id]);
        }
        foreach ($orderedIds as $index => $id) {
            $finalPriority = $index + 1;
            self::execute("UPDATE categories SET priority = ? WHERE id = ?", [$finalPriority, $id]);
        }
    }

    public function persist(): array{
        $errors = $this->validate();
        if (empty($errors)) {
            if($this->id === -1){
                $sql = "INSERT INTO categories (name, priority) VALUES (?, ?)";
                self::execute($sql, [$this->name, $this->createNewPriority()]);
            }else
                $this->update();
        }
        
        return $errors;
    }

    public function validate(): array{
        $errors = [];
        $minChar = Configuration::get('min_char_category');
        $maxChar = Configuration::get('max_char_category');
        
        if(strlen($this->name) < $minChar || strlen($this->name) > $maxChar)
            $errors['nbChar'] = "Le nom doit comporter entre " . $minChar . " et " . $maxChar . " caractères.";
        if($this->categoryNameExists())
            $errors['nameExists'] = "Le nom " . htmlspecialchars($this->name) . " existe déjà.";
        return array_filter($errors, fn($e) => !empty($e));
    }

    public function update(): bool{
        $sql= "UPDATE categories SET name = ? WHERE id = ?"; 
        return self::execute($sql, [$this->name, $this->id])!== false;
    }

    private function categoryNameExists(): bool{
        $sql= "SELECT COUNT(*) FROM categories WHERE name = ? AND id != ?";
        $query= self::execute($sql, [$this->name, $this->id ?? -1]); 
        return $query->fetchColumn() > 0;
    }
}