<?php
require_once "framework/Controller.php";
require_once 'model/Item.php';
require_once 'model/Bids.php';
require_once 'model/User.php';
class ControllerItem extends Controller {
    
    public function browse(): void {
        $itemsUser = Item::getUserActiveItems(1);
        $items = Item::getActiveItems(1);
        (new View("item"))->show(["itemsUser" => $itemsUser, "items" => $items]);
    }

    public function index(): void {
        $this->browse();
    }

    public function open(): void {
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $id = $_GET['param1'];
            $source = $_GET['param2'] ?? "browse";
            $idImg = $_GET["param3"] ?? 0;
            $item = Item::getItemWithId($id);
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['buy_now'])) {
                    
                    if ($item->buy()) {
                        $this->redirect("item", "open", $id, $source, $idImg);
                        return; 
                    }
                }

                if (isset($_POST['amount'])) {
                    $amount = floatval($_POST['amount']);
                    $user = 1; 
                    $newBid = new Bids($user, $id, date('Y-m-d H:i:s'), $amount);
                    $newBid->add();
                
                    $this->redirect("item", "open", $id, $source, $idImg);
                    return;
                }
            }

            $item = Item::getItemWithId($id);
            (new View("openItems"))->show([
                "item" => $item, 
                "source" => $source, 
                "idImg" => $idImg,
                "success_message" => "" 
            ]);
        }
    }

    public function list(): void {
        $user = User::getUserById(1);
        $itemsUser = $user->myItemsAtives();
        $UnsoldItemsUser = $user->getUnsoldItems();
        $SoldItemsUser = $user->getsoldItems();
        (new View("myItems"))->show([
            "itemsUser" => $itemsUser,
            "unsold" => $UnsoldItemsUser,
            "sold" => $SoldItemsUser
        ]);
    }

    public function delete(): void {
        if (isset($_GET["param1"])) {
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
        $source = $_GET['param2'] ?? "from-items";

        if (!isset($_GET['param1'])) {
            $this->redirect("item", "list");
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

}