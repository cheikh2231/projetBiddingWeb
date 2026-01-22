<?php
require_once "framework/Controller.php";
require_once 'model/Item.php';
require_once 'model/Bids.php';
require_once 'model/User.php';

class ControllerItem extends Controller {
    public function browse(): void {
        $itemsUser=Item::getUserActiveItems(1);
        $items=Item::getActiveItems(1);
        (new View("item"))->show(["itemsUser"=>$itemsUser,"items"=>$items]);
    }
    public function index(): void {
    }

    public function open(): void {
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $id = $_GET['param1'];
            $source = $_GET['param2'] ?? "browse";
            $idImg = $_GET["param3"] ?? 0;
            
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['buy_now'])) {
                    $item = Item::getItemWithId($id);
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
        $itemsUser=$user->myItemsAtives();
        $UnsoldItemsUser=$user->getUnsoldItems();
        $SoldItemsUser=$user->getsoldItems();
        (new View("myItems"))->show(["itemsUser"=>$itemsUser,"unsold"=>$UnsoldItemsUser,"sold"=>$SoldItemsUser]);
        
    }

    public function delete(): void {
    if (isset($_GET["param1"])){
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
    $source = "items";

    if (!isset($_GET['param1'])) {
        $this->redirect("item","index");
        return;
    }

    $id = $_GET['param1'];
    $item = Item::getItemWithId($id);

    if (!$item) {
        $this->redirect("item","index");
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $item->title = trim($_POST['title'] ?? '');
        $item->description = trim($_POST['description'] ?? '');
        $item->duration_days = (int)$_POST['duration'];


        $errors['title'] = Item::validateTitle($item->title);
        $errors['description'] = Item::validateDescription($item->description);
        $errors['duration']  = Item::validateDuration($item->duration_days);


        $errors = array_filter($errors);

        if (empty($errors)) {
            $item->update();
            $this->redirect("item", "open", $item->id, "from-$source");
            return;
        }
    }


    (new View('add_editItem'))->show([ 
        "item" => $item, 
        "errors" => $errors, 
        "source" => $source 
    ]);
}
    public function add() : void {
        
    }


}