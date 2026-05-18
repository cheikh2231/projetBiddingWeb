

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
        $currentUser = $this->get_user_or_false();
        $allCategories = Category::getAllCategories();
        if($currentUser){
            (new View("manageCategories"))->show(["currentUser"=>$currentUser , "allCategories"=>$allCategories , "place" => "category"]);
        }else
            $this->redirect("item", "browse");
        
    }
    
}