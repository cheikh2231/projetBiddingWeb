
<?php 
require_once "framework/Model.php";
require_once "utils/AppTime.php";
require_once "framework/Configuration.php";

class Category extends Model {
    private int $id;
    private string $name;
    private int $priority;

    public function __construct(int $id, string $name, int $priority) {
        $this->id = $id;
        $this->name = $name;
        $this->priority = $priority;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPriority(): int { return $this->priority; }
    public function setName(string $name): void { $this->name = $name; }
    private static function mapToCategories(array $data): Category {
        return new Category(
            $data["id"], 
            $data["name"], 
            (int) $data["priority"]
        );
    }

 
    public static function getAllCategories(): array {
        $query = self::execute("SELECT * FROM categories order by priority", []);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = self::mapToCategories($row);
        }
        return $results;
    }

    public function isFirst() : bool {
        $query = self::execute("SELECT priority FROM categories where id = ?",[$this->id]);
        $data = $query->fetchColumn();
        return $data == 1;
    }

    public function isLast() : bool {
        $query = self::execute("SELECT MAX(priority) FROM categories",[]);
        $data = $query->fetchColumn();
        return $data==$this->priority;
    }

    public function createNewPriority() : int {
        $query = self::execute("SELECT MAX(priority) FROM categories",[]);
        $data = $query->fetchColumn();
        return $data != null ? $data+1 : 0 ;
    }



    public function swapPriority(int $iBefore, int $i): void {

        $sql1 = "UPDATE categories SET priority = -1 WHERE priority = ?";
        self::execute($sql1, [ $iBefore]);

        if($sql1){
            $sql2 = "UPDATE categories SET priority = ? where priority = ?";
            self::execute($sql2, [$iBefore, $i]);

            if($sql2){
                $sql3 = "UPDATE categories SET priority = ? WHERE priority = ?";
                self::execute($sql3, [$i, -1]);
            }
        }
    }

    public static function getCatById(int $id) : ?Category {
        $query = self::execute("SELECT * FROM categories WHERE id = ?", [$id]);
        $row = $query->fetch();
        return $row ? self::mapToCategories($row) : null;
    }


    public function getNbUse() : int {
        $query = self::execute("SELECT COUNT(*) from item_categories where category = ? group by category", [$this->id]);
        return $query != null ? $query->fetchColumn() : 0;
    }

    public function isNotUsed() : bool {
        return $this->getNbUse()==0;
    }

    public function delete() : bool {
        $res = self::execute("DELETE FROM categories WHERE id = ?", [$this->id]) !== false;
        if($res){
            $this->updatePriority();
        }
        return $res;
    }

    public function updatePriority(): void {
        
        $sql = "UPDATE categories 
                SET priority = priority - 1 
                WHERE priority > ?";
        
        self::execute($sql, [$this->priority]);
    }

     public function persist(): array {
        $errors = $this->validate();
        
        if (empty($errors)) {
            if ($this->id === -1) {
                
                $sql = "INSERT INTO categories (name, priority) 
                        VALUES (?, ?)";
                self::execute($sql, [
                   $this->name,($this->createNewPriority())
                ]);
            
            } else {
                $this->update();
            }
        }
        
        return $errors;
    }

    public function validate(): array {
        $errors = [];
        $minChar = Configuration::get('min_char_category');
        $maxChar = Configuration::get('max_char_category');
        
        if(strlen($this->name)<$minChar || strlen($this->name) >$maxChar){
            $errors['nbChar'] = "name must be in ".$minChar." and ".$maxChar." characters.";
        }
        if($this->categoryNameExists()){
            $errors['nameExists'] = "the name ".$this->name." already exists. Please choose another name";
        }
        return array_filter($errors, fn($e) => !empty($e));
    }

     public function update(): bool {
        $sql = "UPDATE categories SET name = ? WHERE id = ?"; 
        return self::execute($sql, [
            $this->name, $this->id
        ]) !== false;
    }

    private function categoryNameExists(): bool {
        $sql = "SELECT COUNT(*) FROM categories WHERE name = ? AND id != ?";
        $query = self::execute($sql, [$this->name , $this->id ?? -1] ); 
        return $query->fetchColumn() > 0;
    }

}

