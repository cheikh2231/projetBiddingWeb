<?php
require_once "framework/Controller.php";
require_once 'model/Item.php';

class ControllerItem extends Controller {
    public function browse(): void {
        $itemsUser=Item::getUserActiveItems(1);
        $items=Item::getActiveItems(1);
        
        (new View("item"))->show(["itemsUser"=>$itemsUser,"items"=>$items]);
    }
    public function index(): void {
    }


    public function open(): void {
        if(isset($_GET["param1"]) && $_GET["param1"] !== "" ){
            
            $id = $_GET['param1'];
            $source = $_GET['param2']??"je_sais_pas";
            $idImg = $_GET["param3"]??0;
            $item = Item::getItemWithId($id);
           
        }

        
        (new View("openItems"))->show(["item"=>$item,"source"=>$source,"idImg"=>$idImg]);
    }
}