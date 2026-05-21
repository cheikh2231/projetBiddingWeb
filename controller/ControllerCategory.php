

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


}