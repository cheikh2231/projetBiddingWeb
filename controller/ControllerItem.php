<?php
require_once "framework/Controller.php";
require_once 'model/Item.php';
require_once 'model/Bids.php';

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
}