

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
        $allCategories = Category::getAllCategories();
        if($currentUser->isAdmin()){
            $cat = null;
           
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if(isset($_GET['param1']) && $_GET['param1']!=''){
                    $cat_id = $_GET['param1'];
                    $cat = Category::getCatById($cat_id);
                }
               
                 $errors = [];
                 $priority = $cat->getPriority();
                  if (isset($_POST['up'])) {
                    if($cat != null) {
                        if(!($cat->isFirst())){
                            $cat->swapPriority($priority-1,$priority);
                            $this->redirect('category', 'manage');
                        }
                    }
                  }elseif (isset($_POST['down'])) {
                    if($cat != null){
                        if(!($cat->isLast())){
                            $cat->swapPriority($priority,$priority+1);
                            $this->redirect('category', 'manage');
                        }
                    }
                  }elseif (isset($_POST['delete'])){
                    if($cat->isNotUsed()){
                        if($cat->delete()){
                            $this->redirect('category', 'manage');
                        }
                       
                    }
                  }
                   
            }
            (new View("manageCategories"))->show(["currentUser"=>$currentUser , "allCategories"=>$allCategories , "place" => "category"]);
        }else
            $this->redirect("item", "browse");
        
    }
    
}