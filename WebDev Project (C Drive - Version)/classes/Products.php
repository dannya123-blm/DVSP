<?php

namespace classes;

// Product class
class Products {
    protected $idProducts;
    protected $Name;
    protected $Description;
    protected $Price;
    protected $StockQuantity;
    protected $Category;
    protected $idAdmin;


    public function getProductID() {
        return $this->idProducts;
    }

    public function setProductID($productID) {
        $this->idProducts = $productID;
    }

    public function getName() {
        return $this->Name;
    }

    public function setName($name) {
        $this->Name = $name;
    }

    public function getDescription() {
        return $this->Description;
    }

    public function setDescription($description) {
        $this->Description = $description;
    }

    public function getPrice() {
        return $this->Price;
    }

    public function setPrice($price) {
        $this->Price = $price;
    }

    public function getStockQuantity() {
        return $this->StockQuantity;
    }

    public function setStockQuantity($stockQuantity) {
        $this->StockQuantity = $stockQuantity;
    }

    public function getCategory() {
        return $this->Category;
    }

    public function setCategory($category) {
        $this->Category = $category;
    }

    public function getAdminID() {
        return $this->idAdmin;
    }

    public function setAdminID($adminID) {
        $this->idAdmin = $adminID;
    }
}
