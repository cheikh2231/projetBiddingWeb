<?php
require_once "framework/Controller.php";
require_once 'model/Item.php';
require_once 'model/Bids.php';
require_once 'model/User.php';
class ControllerItem extends Controller {
    
    public function browse(): void {
        $currentUser = $this->get_user_or_false();
        $itemsUserBidded = [];

        if ($currentUser) {
           
            $itemsUserBidded = $currentUser->getBiddedItems();
            

            $allItems = Item::getAllActivesItems();
            $items = array_filter($allItems, function($i) use ($currentUser) {
                return $i->getOwner()->getId() !== $currentUser->getId() && !$currentUser->hasBidIn($i);
            });
        } else {
            
            $items = Item::getAllActivesItems();
        }

        (new View("item"))->show([
            "itemsUser" => $itemsUserBidded, 
            "items" => $items,
            "currentUser" => $currentUser
        ]);
    }

    public function index(): void {
        $this->browse();
    }

    public function open(): void {

        $id = $_GET['param1'] ?? "";


        if ($id === "") {
            $this->redirect("item", "browse");
            return;
        }
        $source = $this->getSourceNature($_GET["param2"]??'');
      
        $idImg = $_GET["param3"] ?? 0;
        $item = Item::getItemById($id);
        if (!$item) {
            throw new Exception("Item not found"); 
        }        
        $currentUser = $this->get_user_or_redirect();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            if (isset($_POST['buy_now'])) {
                if ($currentUser->buyItem($item)) {
                    $this->redirect("item", "open", $id,$source,$idImg);
                    return; 
                }
            }

            if (isset($_POST['amount'])) {
                $amount = floatval($_POST['amount']);
                $newBid = new Bids($currentUser->getId(), $id, AppTime::get_current_datetime() , $amount);
                $newBid->add();
                $this->redirect("item", "open", $id,$source);
                return;
            }
        }

        (new View("openItems"))->show([
            "item" => $item, 
            "source" => $source, 
            "idImg" => $idImg,
            "currentUser" => $currentUser,
            "success_message" => "" 
        ]);
    }
    public function list(): void {
        $user = $this->get_user_or_false();
        $itemsUser = [];
        $unsold = [];
        $sold = [];
        if($user){
            $itemsUser = $user->myItemsAtives();
            $unsold = $user->getUnsoldItems();
            $sold = $user->getsoldItems();
        }
        (new View("myItems"))->show([
            "itemsUser" => $itemsUser,
            "unsold" => $unsold,
            "sold" => $sold,
            "currentUser"=>$user

        ]);
    }

    public function edit(): void {
        $user = $this->get_user_or_redirect();

        $id = $_GET['param1'] ?? null;
        $source = "from-items";
        $item = Item::getItemById($id);
        if (!$item || $item->getOwner()->getId() !== $user->getId()) {
            $this->redirect("item", "list");
            return;
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           
            $item->setTitle(trim($_POST['title'] ?? ''));
            $item->setDescription(trim($_POST['description'] ?? ''));
            $item->setDurationDays((int)$_POST['duration']);
            
            $start_bid = floatval($_POST['starting'] ?? null);

            $sale_price = floatval($_POST['sale_price'] ?? null);

            $instant_price = floatval($_POST['instant_price'] ?? null);

            $buy_now_price = null;
            if ($start_bid != null && $start_bid > 0) {

                $buy_now_price = $instant_price>0 ? $instant_price:null;

            } else if ($sale_price != null && $sale_price > 0) {
                
                $buy_now_price = $sale_price;

            } 

            $item->setStartingBid($start_bid);
            $item->setBuyNowPrice($buy_now_price);
            if($sale_price !== null && $sale_price>0 ){
                $item->setIsDirectSale(true);
            }else 
                $item->setIsDirectSale(false);
            $errors = $item->persist();

            if (empty($errors)) {
                $this->redirect("item", "open", $item->getId(), "from-items");
                return;
            }
        }

        (new View('add_editItem'))->show([ 
            "item" => $item, 
            "errors" => $errors, 
            "source" => $source ,
             "currentUser"=>$user
        ]);
    }

    public function add(): void {
        $user = $this->get_user_or_redirect();

        $errors = [];
        $newItem = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $now = AppTime::get_current_datetime();

            $start_bid = floatval($_POST['starting'] ?? null);

            $sale_price = floatval($_POST['sale_price'] ?? null);

            $instant_price = floatval($_POST['instant_price'] ?? null);

            $buy_now_price = null;
            if ($start_bid != null && $start_bid > 0) {

                $buy_now_price = $instant_price>0 ? $instant_price:null;

            } else if ($sale_price != null && $sale_price > 0) {
                
                $buy_now_price = $sale_price;

            } 

            $newItem = new Item(
                null, 
                trim($_POST['title'] ?? ''),
                trim($_POST['description'] ?? ''),
                $user->getId(),
                $now,
                (int)($_POST['duration'] ?? 0),
                $start_bid,
                $buy_now_price
            );

            if($sale_price>0 ){
                $newItem->setIsDirectSale(true);
            }
            $errors = $newItem->persist();

            if (empty($errors)) {
                $this->redirect("item", "open", $newItem->getId());
                return;
            }
        }

        (new View('add_editItem'))->show([ 
            "item" => $newItem, 
            "errors" => $errors,
            "source" => "browse", 
            "currentUser"=>$user
        ]);
    }

    public function delete(): void {
        $user = $this->get_user_or_redirect();

        $id = $_GET['param1'] ?? null;
        $item = Item::getItemById($id);

        if (!$item || $item->getOwner()->getId() !== $user->getId()) {
            $this->redirect("item", "list");
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
           
            if ($item->delete()) {
                $this->redirect("item", "list");
                return;
            } else {
                $errors = ["This item cannot be deleted (it might have bids)."];
            }
        }

        (new View("deleteItems"))->show(["item" => $item, "errors" => $errors ?? [],"currentUser"=>$user]);
    }




    public function sales() : void {
        $source = "sales";
         $currentUser = $this->get_user_or_redirect();
  
        $sold = $currentUser->getSoldItems();
        (new View('sales'))->show(["sold"=>$sold,"currentUser"=>$currentUser,"source"=>$source]);
    }

    public function purchases() : void {
        $source = "purchases";
        $currentUser = $this->get_user_or_redirect();
        if (!$currentUser) {
            $this->redirect("user", "browse");
            return;
        }       
        $purchases = $currentUser->getPurchasesItems();
        (new View('purchases'))->show(["purchases"=>$purchases,"currentUser"=>$currentUser,"source"=>$source]);
    }

    public function manage_images() : void{
        $user = $this->get_user_or_redirect();
                
        $config = parse_ini_file('config/dev.ini'); 
        $max_size_mb = (int)$config['upload_max_size'];
        $max_res_main = (int)$config['image_max_res_main']; 
        $max_res_thumb = (int)$config['image_max_res_thumb'];

        if(!isset($_GET['param1'])|| $_GET['param1']==''){
           $this->redirect('item','browse');
           return;
        }else{
          $item_id = $_GET['param1'];
          $item = Item::getItemById($item_id);
        }
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         $errors = [];
         $index = $_POST['image_index'] ?? null;

        if (isset($_POST['delete'])) {
            if($item->deleteImage($index)){
                 $this->redirect('item', 'manage_images', $item_id);
                 return;
            }
            $errors[] = "image introuvable ";
            
        } elseif (isset($_POST['left'])) {
            if(($index-1) > 0){
                $item->swapPic($index-1,$index);
            }
        } elseif (isset($_POST['right'])) {
            if(($index+1) <= count($item->getPictures())){
                $item->swapPic($index,$index+1);
            }
        }else

         if (isset($_FILES['image'])) {
            $errors = $item->validate_photo($_FILES['image'], $max_size_mb);
            
            if (empty($errors)) {
                
                $folder = "uploads/items/$item_id";
                $original_name = $_FILES['image']['name'];

    
                $path_main = $item->process_image(
                    $_FILES['image']['tmp_name'], 
                        $folder, 
                        $original_name, 
                        $max_res_main, 
                        $max_res_main
                    );

                    
                    $path_thumb = $item->process_image(
                        $_FILES['image']['tmp_name'], 
                        $folder, 
                        $original_name, 
                        $max_res_thumb, 
                        $max_res_thumb, 
                        "_thumbnail"
                    );

                    if ($path_main && $path_thumb) {
                        
                        $item->addPicture( $path_main);
                        
                        
                        $this->redirect('item', 'manage_images', $item_id);
                        return;
                    } else {
                        $errors[] = "Erreur lors du traitement de l'image.";
                    }
                }
            } else {
                $errors[] = "Aucun fichier sélectionné.";
            }
            
            (new View('manage_images'))->show([
                "item" => $item, 
                "currentUser" => $user, 
                "errors" => $errors
            ]);
            return;
        }
        (new View('manage_images'))->show(["currentUser" =>$user , "item"=>$item]);
    }

    private function getSourceNature(string $s): string {
        return match ($s) {
            'from-browse'    => 'browse',
            'from-items'      => 'list',
            'from-sales'     => 'sales',
            'from-purchases' => 'purchases',
            default          => 'browse'
        };
    }

}