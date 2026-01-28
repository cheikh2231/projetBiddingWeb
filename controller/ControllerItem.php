<?php
require_once "framework/Controller.php";
require_once 'model/Item.php';
require_once 'model/Bids.php';
require_once 'model/User.php';
class ControllerItem extends Controller {
    
    public function browse(): void {
       
        $currentUser = $this->get_user_or_false();
        $itemsUser = [];
        if ($currentUser) {
            $itemsUser = Item::getUserActiveItems($currentUser->getId());
            $items = Item::getActiveItems($currentUser->getId());
        } else {
            $items = Item::getAllActivesItems(); 
        }

        (new View("item"))->show([
            "itemsUser" => $itemsUser, 
            "items" => $items,
            "isGuest" => ($currentUser === false),
        
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

        $source = $_GET['param2'] ?? "browse";
        $idImg = $_GET["param3"] ?? 0;
        $item = Item::getItemWithId($id);
        $currentUser = $this->get_user_or_false();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           
            if (!$currentUser) {
                $this->redirect("login", "index");
                return;
            }

            if (isset($_POST['buy_now'])) {
                if ($currentUser->buyItem($item)) {
                    $this->redirect("item", "open", $id, $source, $idImg);
                    return; 
                }
            }

            if (isset($_POST['amount'])) {
                $amount = floatval($_POST['amount']);
                $newBid = new Bids($currentUser->getId(), $id, date('Y-m-d H:i:s'), $amount);
                $newBid->add();
                $this->redirect("item", "open", $id, $source, $idImg);
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
        $user = $this->get_user_or_redirect();
        $itemsUser = $user->myItemsAtives();
        $unsold = $user->getUnsoldItems();
        $sold = $user->getsoldItems();
        (new View("myItems"))->show([
            "itemsUser" => $itemsUser,
            "unsold" => $unsold,
            "sold" => $sold
        ]);
    }

    public function delete(): void {
         if (!isset($_GET["param1"]) || $_GET["param1"] === "") {
            $this->redirect("item", "list"); 
            return;
        }else {
            $id = $_GET['param1'];
            $item = Item::getItemWithId($id);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['delete'])) {
                    if ($item->delete()) {
                        $this->redirect("item", "list");
                        return; 
                    }             
                }
            }
            (new View("deleteItems"))->show([
                "item" => $item
            ]);
        }
    }
    
    public function edit() : void {
        $errors = [];
        $source = $_GET['param2'] ?? "items";

        if (!isset($_GET['param1'])) {
            $this->redirect("item", "list");
            return; 
        }

        $item = Item::getItemWithId($_GET['param1']);

 

    if ($item==null) {
        $this->redirect("item", "index"); 
        return;
    }

        $item = Item::getItemWithId($_GET['param1']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           
            $item->setTitle(trim($_POST['title'] ?? ''));
            $item->setDescription(trim($_POST['description'] ?? ''));
            $item->setDurationDays((int)$_POST['duration']);
            
            $start_bid = floatval($_POST['starting'] ?? null);
            $sale_price = floatval($_POST['sale_price'] ?? null);
            $instant_price = floatval($_POST['instant_price'] ?? null);
            
            $item->setStartingBid($start_bid);

            if ($start_bid != null && $start_bid > 0) {
                $item->setBuyNowPrice($instant_price>0 ? $instant_price:null);
            } else if ($sale_price != null && $sale_price > 0) {
                $item->setBuyNowPrice($sale_price);
            }
            $item->setIsDirectSale($sale_price > 0);

          
            $errors['title'] = Item::validateTitle($item->getTitle());
            $errors['description'] = Item::validateDescription($item->getDescription());
            $errors['duration'] = Item::validateDuration($item->getDurationDays());

            $typeErrors = $item->validateStartingAndSale();
            $typePrice = $item->validatePurchasePrice();
            
            if (!empty($typeErrors)) {
                $errors['coupleStartingAndDirect'] = $typeErrors;
            }
            if (!empty($typePrice)) {
                $errors['instant'] = $typePrice;
            }

            $errors = array_filter($errors);

            if (empty($errors)) {
                if ($item->update()) {
                    $this->redirect("item", "open", $item->getId(), $source);
                    return;
                }
            }
        }

        (new View('add_editItem'))->show([ 
            "item" => $item, 
            "errors" => $errors, 
            "source" => $source 
        ]);
    }

    public function add() : void {
        $errors = [];
        $source = "items";
        $newItem = null; 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $now = AppTime::get_current_datetime();
            $user = $this->get_user_or_redirect();
            
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $duration = (int)($_POST['duration'] ?? 0);
 
            $end_at = (clone(new DateTime($now)))->add(new DateInterval("P{$duration}D"));
            
            $start_bid = isset($_POST['starting']) && $_POST['starting'] !== '' ? floatval($_POST['starting']) : null;
            $sale_price = isset($_POST['sale_price']) && $_POST['sale_price'] !== '' ? floatval($_POST['sale_price']) : null;
            $instant_price = isset($_POST['instant_price']) && $_POST['instant_price'] !== '' ? floatval($_POST['instant_price']) : null;

            
            $buy_now_price = null;
            if ($start_bid !== null && $start_bid > 0) {
                $buy_now_price = ($instant_price > 0 ? $instant_price : null);
            } else if ($sale_price !== null && $sale_price > 0) {
                $buy_now_price = $sale_price;
            }

          
            $newItem = new Item(
                null,
                $title,
                $description,
                $user->getId(), 
                $now,
                ($start_bid > 0),           
                false,                      
                ($buy_now_price > 0),        
                $end_at->format('Y-m-d H:i:s'), 
                false,                       
                ($sale_price > 0),           
                ($start_bid > 0 ? $start_bid : null),
                $buy_now_price,
                $duration,
                $start_bid,
                false                        
            );

            
            $errors['title'] = Item::validateTitle($newItem->getTitle());
            $errors['description'] = Item::validateDescription($newItem->getDescription());
            $errors['duration'] = Item::validateDuration($newItem->getDurationDays());
            
            $typeErrors = $newItem->validateStartingAndSale();
            if (!empty($typeErrors)) $errors['coupleStartingAndDirect'] = $typeErrors;
            
            $typePrice = $newItem->validatePurchasePrice();
            if (!empty($typePrice)) $errors['instant'] = $typePrice;

            $errors = array_filter($errors);

            
            if (empty($errors)) {
                if ($user->add($newItem)) {
                    $this->redirect("item", "open", $newItem->getId(), $source);
                    return;
                }
            }
        }

        
        (new View('add_editItem'))->show([ 
            "item" => $newItem, 
            "errors" => $errors, 
            "source" => $source 
        ]);
    }


    public function sales() : void {
        $source = "sales";
         $currentUser = $this->get_user_or_false();
        $sold = $currentUser->getSoldItems();
        (new View('sales'))->show(["sold"=>$sold,"currentUser"=>$currentUser,"source"=>$source]);
    }
}