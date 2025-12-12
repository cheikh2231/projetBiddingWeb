<?php
require_once "framework/Controller.php";
require_once 'model/Item.php';

class ControllerItem extends Controller {
    public function browse(): void {
        $itemsUser=Item:: getUserActiveItems(2);
        $items=Item::getActiveItems(2);
        
        (new View("item"))->show(["itemsUser"=>$itemsUser,"items"=>$items]);
    }
    public function index(): void {
    }


    public function open(): void {
        if(isset($_GET['param1'])){
            $id = $_GET['param1'];
            $item = Item::getItemWithId($id);
           (new View("openItems"))->show(["item"=>$item]);
        }else
          $this->redirect('Item');
    }
}