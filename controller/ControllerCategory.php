

<?php
require_once "framework/Controller.php";
require_once 'model/Item.php';
require_once 'model/Bids.php';
require_once 'model/User.php';
require_once 'model/Category.php';
require_once 'utils/UrlSafeEncoder.php';
class ControllerCategory extends Controller {

     public function index(): void {
        $this->manage();
    }

public function manage(): void {
    $currentUser = $this->get_user_or_redirect();
    if(!$currentUser->isAdmin()){
        $this->redirect("item", "browse");
        return;
    }

    $errors = [];
    $values = [];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if(isset($_POST['add'])){
            $catName = trim($_POST['catName'] ?? '');
            $newCategory = new Category(-1, $catName, -1);
            $addErrors = $newCategory->persist();
            
            if(empty($addErrors)){
                // unset($values['new']);
                $this->redirect("category", "manage");
                return;
            }
            $errors['new'] = $addErrors; 
            $values['new'] = $catName;
        } 
        
       
        elseif(isset($_GET['param1']) && $_GET['param1'] != ''){
            $cat_id = $_GET['param1'];
            $cat = Category::getCatById($cat_id);
            
            if($cat) {
                $priority = $cat->getPriority();
                
                if (isset($_POST['up']) && !$cat->isFirst()) {
                    $cat->swapPriority($priority - 1, $priority);
                    $this->redirect('category', 'manage');
                    return;
                } elseif (isset($_POST['down']) && !$cat->isLast()) {
                    $cat->swapPriority($priority, $priority + 1);
                    $this->redirect('category', 'manage');
                    return;
                } elseif (isset($_POST['save'])){
                    $newName = $_POST['catName'] ?? '';
                    $cat->setName($newName);
                    $catErrors = $cat->persist();
                    
                    if (empty($catErrors)) {
                        $this->redirect("category", "manage");
                        return;
                    }
                    $errors[$cat->getId()] = $catErrors;
                    $values[$cat->getId()] = $newName;
                }
            }
        }
    }

    $allCategories = Category::getAllCategories();

    (new View("manageCategories"))->show([
        "currentUser" => $currentUser, 
        "allCategories" => $allCategories, 
        "place" => "category", 
        "errors" => $errors,
        "values" => $values
    ]);
}


   public function delete(): void {
        $user = $this->get_user_or_redirect();

        $id = $_GET['param1'] ?? null;
        $cat = Category::getCatById($id);
        if($user->isAdmin() && $cat != null){            
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {  
                if($cat && $cat->isNotUsed()){
                    if ($cat->delete()) {
                        $this->redirect("category","manage");
                        return;
                    } else {
                        $errors = ["This category cannot be deleted."];
                    }
                }else{
                    $errors = ["This category cannot be deleted, it is used."];
                }
               
            }

           (new View("deleteCategories"))->show(["cat"=>$cat, "errors" => $errors ?? [],"currentUser"=>$user]);
        }elseif($user->isAdmin()){
            $this->redirect("category","manage");
        }else{
            $this->redirect("item","index");
        }
       
    }
    // ====================================================
    // NOUVELLES METHODES POUR LE JAVASCRIPT AJAX
    // ====================================================

    public function addAjax(): void {
        $user = $this->get_user_or_redirect();
        if(!$user->isAdmin()) { echo json_encode(['success' => false, 'errors' => ['general' => 'Accès refusé']]); exit; }

        $name = trim($_POST['name'] ?? '');
        $cat = new Category(-1, $name, -1);
        $errors = $cat->persist();

        if (empty($errors)) {
            $newCat = Category::getCatByName($name); 
            echo json_encode([
                'success' => true,
                'id' => $newCat->getId(),
                'name' => $newCat->getName()
            ]);
        } else {
            echo json_encode(['success' => false, 'errors' => $errors]);
        }
        exit;
    }

    public function editAjax(): void {
        $user = $this->get_user_or_redirect();
        if(!$user->isAdmin()) { echo json_encode(['success' => false, 'errors' => ['general' => 'Accès refusé']]); exit; }

        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        
        $cat = Category::getCatById((int)$id);
        if ($cat) {
            $cat->setName($name);
            $errors = $cat->persist();
            
            if (empty($errors)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'errors' => $errors]);
            }
        } else {
            echo json_encode(['success' => false, 'errors' => ['general' => 'Catégorie introuvable']]);
        }
        exit;
    }

    public function deleteAjax(): void {
        $user = $this->get_user_or_redirect();
        if(!$user->isAdmin()) { echo json_encode(['success' => false]); exit; }

        $id = $_POST['id'] ?? null;
        if ($id) {
            $cat = Category::getCatById((int)$id);
            if ($cat && $cat->isNotUsed() && $cat->delete()) {
                echo json_encode(['success' => true]);
                exit;
            }
        }
        echo json_encode(['success' => false]);
        exit;
    }

    public function reorderAjax(): void {
        $user = $this->get_user_or_redirect();
        if(!$user->isAdmin()) { echo json_encode(['success' => false]); exit; }

        $inputData = json_decode(file_get_contents('php://input'), true);
        if (isset($inputData['order']) && is_array($inputData['order'])) {
            Category::updateAllPriorities($inputData['order']);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }


}