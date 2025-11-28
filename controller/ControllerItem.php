<?php
require_once "framework/Controller.php";
require_once 'model/Item.php';

class ControllerItem extends Controller {
    public function browse(): void {
        $items=Item::getItems();
        (new View("item"))->show(["items"=>$items]);
    }
    public function index(): void {
    }
}