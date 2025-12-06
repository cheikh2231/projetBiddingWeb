<?php
require_once "framework/Controller.php";
require_once 'model/Item.php';

class ControllerItem extends Controller {
    public function browse(): void {
        $itemsUser=Item:: getUserActiveItems();
        $items=Item::getActiveItems();
        
        (new View("item"))->show(["itemsUser"=>$itemsUser,"items"=>$items]);
    }
    public function index(): void {
    }
}