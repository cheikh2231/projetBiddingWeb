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
}