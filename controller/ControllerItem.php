<?php
require_once "framework/Controller.php";
require_once 'model/Item.php';
require_once 'model/Bids.php';
require_once 'model/User.php';
require_once 'utils/UrlSafeEncoder.php';
class ControllerItem extends Controller {
    
    public function browse(): void {
        $currentUser = $this->get_user_or_false();
        $itemsUserBidded = [];
        $searchName = "";
        if ($currentUser) {
           
            $itemsUserBidded = $currentUser->getBiddedItems();
            

            $allItems = Item::getAllActivesItems();
            $items = array_filter($allItems, function($i) use ($currentUser) {
                return $i->getOwner()->getId() !== $currentUser->getId() && !$currentUser->hasBidIn($i);
            });
        } else {
            
            $items = Item::getAllActivesItems();
        }
        $searchName = $this->decodeAndGetName('param1');
     
        (new View("item"))->show([
            "itemsUser" => $itemsUserBidded, 
            "items" => $items,
            "currentUser" => $currentUser,
            "searchName" => $searchName,
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

    $source  = $this->getSourceNature($_GET["param2"] ?? '');
    $encoded = $_GET["param3"] ?? "";
    $state   = [];
    // Décoder tout j'encode et la recherche et l'id de l'image
    if ($encoded !== "") {
        $decoded = UrlSafeEncoder::url_safe_decode($encoded);
        $state   = is_array($decoded) ? $decoded : [];
    }

    // je recupere l'id de l'image
    $idImg = isset($state['img']) ? (int)$state['img'] : (empty($state) ? (int)$encoded:0);

    // encode sans l'id de l'image pour la redirection vers browse ou list
    $stateClean = $state;
    unset($stateClean['img']);
    $encodedClean = !empty($stateClean) ? UrlSafeEncoder::url_safe_encode($stateClean) : "";

    $item = Item::getItemById($id);
    if (!$item) {
        throw new Exception("Item not found");
    }

    $currentUser = $this->get_user_or_false();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!$currentUser) {
            $this->redirect("user", "signup");
        }
        if (isset($_POST['buy_now'])) {
            if ($currentUser->buyItem($item)) {
                if ($encodedClean) {
                    $this->redirect("item", "open", $id, "from-".$source, $encodedClean);
                } else {
                    $this->redirect("item", "open", $id, "from-".$source);
                }
                return;
            }
        }
        
        if (isset($_POST['amount'])) {
            $amount = floatval($_POST['amount']);
            $newBid = new Bids($currentUser->getId(), $id, AppTime::get_current_datetime(), $amount);
            $newBid->add();
            if ($encodedClean) {
                $this->redirect("item", "open", $id, "from-".$source, $encodedClean);
            } else {
                $this->redirect("item", "open", $id, "from-".$source);
            }
            return;
        }
    }

    $bids  = $item->getBids();
    $pics  = $item->getPictures();
    $owner = $item->getOwner();

    $isOwner      = $currentUser && ($currentUser->getId() === $owner->getId());
    $currentPrice = $item->getMaxBid() ?? ($item->getStartingBid() > 0 ? $item->getStartingBid() : $item->getBuyNowPrice());
    $backRoute    = "item/$source".($encodedClean ? "/$encodedClean" : "");
    $displayImg   = !empty($pics)
        ? $pics[max(0, $idImg - 1)]
        : "https://clipart-library.com/2023/disappearance-clipart-33563200-MISSING-red-Rubber-Stamp-over-a-white-background--Stock-Photo.jpg";

    $minBid = $startingValue = null;
    if ($item->getIsAuction()) {
        $bidValue      = $item->getHasBids() ? $item->getMaxBid() : $item->getStartingBid();
        $minBid        = $item->getHasBids() ? $bidValue + 0.01   : $bidValue;
        $startingValue = $item->getHasBids() ? $bidValue + 1      : $bidValue;
    }

    (new View("openItems"))->show([
        "item"          => $item,
        "source"        => $source,
        "idImg"         => $idImg,
        "currentUser"   => $currentUser,
        "bids"          => $bids,
        "pics"          => $pics,
        "owner"         => $owner,
        "isOwner"       => $isOwner,
        "currentPrice"  => $currentPrice,
        "backRoute"     => $backRoute,
        "displayImg"    => $displayImg,
        "minBid"        => $minBid,
        "startingValue" => $startingValue,
        "encodedClean"       => $encodedClean,  
        "encodedFull"   => $encoded,       
    ]);
}
    public function edit(): void {
        $user = $this->get_user_or_redirect();

        $id = $_GET['param1'] ?? null;
        $encoded = $_GET['param2'] ?? "";
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
            
            $start_bid = floatval($_POST['starting'] ?? 0);

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
                $this->redirect("item", "open", $item->getId(), "from-items",$encoded);
                return;
            }
        }

        (new View('add_editItem'))->show([ 
            "item" => $item, 
            "errors" => $errors, 
            "source" => $source ,
             "currentUser"=>$user,
             "encoded" => $encoded,
        ]);
    }

    public function add(): void {
        $user = $this->get_user_or_redirect();

        $errors = [];
        
        $newItem = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
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
        $encoded = $_GET['param2'] ?? "";
        if (!$item || $item->getOwner()->getId() !== $user->getId()) {
            $this->redirect("item", "list");
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
           
            if ($item->delete()) {
                $this->redirect("item", "list",$encoded);
                return;
            } else {
                $errors = ["This item cannot be deleted (it might have bids)."];
            }
        }

        (new View("deleteItems"))->show(["item" => $item, "errors" => $errors ?? [],"currentUser"=>$user,"encoded"=>$encoded]);
    }




    public function sales() : void {
        $source = "sales";
        $currentUser = $this->get_user_or_redirect();
  
        $sold = $currentUser->getSoldItems();
        //déplacement calcul de la somme
        $somme = 0;
        foreach ($sold as $i) {
            $somme = $somme + $i->getFinalBuyPrice(); 
        }

        (new View('sales'))->show(["sold"=>$sold,"currentUser"=>$currentUser,"source"=>$source,"somme"=>$somme]);
    }

    public function purchases() : void {
        $source = "purchases";
        $currentUser = $this->get_user_or_redirect();
        if (!$currentUser) {
            $this->redirect("user", "browse");
            return;
        }       
        $purchases = $currentUser->getPurchasesItems();
        //calcul de la somme déplacer dans le controller (correction)
        $somme = 0;
        foreach ($purchases as $i) {
            $somme = $somme + $i->getFinalBuyPrice(); 
        }

        (new View('purchases'))->show(["purchases"=>$purchases,"currentUser"=>$currentUser,"source"=>$source,"somme"=>$somme]);
    }

    public function manage_images() : void{
        $user = $this->get_user_or_redirect();
                
        $config = parse_ini_file('config/dev.ini'); 
        $max_size_mb = (int)$config['upload_max_size'];
        $max_res_main = (int)$config['image_max_res_main']; 
        $max_res_thumb = (int)$config['image_max_res_thumb'];
        $encoded = $_GET['param2'] ?? "";
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
                 $this->redirect('item', 'manage_images', $item_id,$encoded);
                 return;
            }
            $errors[] = "image introuvable ";
            
        } elseif (isset($_POST['left'])) {
            if(($index-1) > 0){
                $item->swapPic($index-1,$index);
                $this->redirect('item', 'manage_images', $item_id,$encoded);
                return;
            }
        } elseif (isset($_POST['right'])) {
            if(($index+1) <= count($item->getPictures())){
                $item->swapPic($index,$index+1);
                $this->redirect('item', 'manage_images', $item_id,$encoded);
                return;
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
                        
                        
                        $this->redirect('item', 'manage_images', $item_id,$encoded);
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
        (new View('manage_images'))->show(["currentUser" =>$user , "item"=>$item,"encoded"=>$encoded]);
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

    public function search(): void {
        $encoded = "";
        try {
            $user = $this->get_user_or_false();
            $query = $_GET['res'] ?? '';
            $place = $_GET['place'] ?? '';

            if ($query === '') {
                if($place == "browse"){
                    $items = Item::getAllActivesItems();
                }else{
                    $items = $user->getMyAllItems();
                }       

            } else {
                $items = Item::search($query,($place=="browse"?null:$user));
                $encoded = UrlSafeEncoder::url_safe_encode(['search' => $query]);
            }
            
    
            $result = [];
            
            foreach ($items as $item) {
               $result[] = [
                        'id'          => $item->getId(),
                        'title'       => $item->getTitle(),
                        'description' => $item->getDescription(),
                        'owner'       => $item->getOwner()->getPseudo(),
                        'price'       => (float)($item->getBuyNowPrice() ?? $item->getStartingBid() ?? 0),
                        'endAt'       => $item->getEndAt(),
                        'isAuction'   => $item->getIsAuction(),
                        'hasBuyNow'   => $item->getHasBuyNow(),
                        'hasBids'     => $item->getHasBids(),      
                        'maxBid'      => $item->getMaxBid(),       
                        'picture'     => $item->getPictures()[0] ?? null, 
                        'isParticipating' => $user ? $user->hasBidIn($item) : false,
                        'isMaxBidder'  => $user ? $item->getMaxBidder() == $user : false,
                        'isSold' => $item->getWinner() != null,
                        'isUnsold' => $item->isClosed() && $item->getWinner()==null,
                        
                ];
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'items'   => $result,
                'encoded' => $encoded
            ]);
            exit;
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }



    }
    
    private function decodeAndGetName(String $param) : ?String {
        $encoded = $_GET[$param] ?? "";
        if ($encoded) {
            $decoded = UrlSafeEncoder::url_safe_decode($encoded);
            $searchName = is_array($decoded) ? ($decoded['search'] ?? '') : '';
            return $searchName;
        }
        return "";
    }

     public function list(): void {
        $user = $this->get_user_or_false();
        $itemsUser = [];
        $unsold = [];
        $sold = [];
        $searchName = "";
        if($user){
            $itemsUser = $user->myItemsAtives();
            $unsold = $user->getUnsoldItems();
            $sold = $user->getsoldItems();
        }

        $searchName = $this->decodeAndGetName('param1');
        (new View("myItems"))->show([
            "itemsUser" => $itemsUser,
            "unsold" => $unsold,
            "sold" => $sold,
            "currentUser"=>$user,
            "searchName" => $searchName,
        ]);
    }


}
