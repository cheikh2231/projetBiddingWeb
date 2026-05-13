
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
    public function getPriority(): string { return $this->$priority; }

    private static function mapToCategories(array $data): Category {
        return new Category(
            $data["id"], 
            $data["name"], 
            $data["priority"]
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

}

