<?php


// Product class
class Products {
    protected $pdo;

    protected $idProducts;
    protected $Name;
    protected $Description;
    protected $Price;
    protected $StockQuantity;
    protected $Category;
    protected $idAdmin;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

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

    public function getProductById($productId) {
        $stmt = $this->pdo->prepare("SELECT * FROM Products WHERE idProducts = :productId");
        $stmt->execute(['productId' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $this->idProducts = $product['idProducts'];
            $this->Name = $product['Name'];
            $this->Description = $product['Description'];
            $this->Price = $product['Price'];
            $this->StockQuantity = $product['StockQuantity'];
            $this->Category = $product['Category'];
            $this->idAdmin = $product['idAdmin'];
            return $this;
        } else {
            return null;
        }
    }

    public function updateProduct($productId, $name, $description, $price, $stockQuantity, $category) {
        $stmt = $this->pdo->prepare("UPDATE Products SET Name = :name, Description = :description, Price = :price, StockQuantity = :stockQuantity, Category = :category WHERE idProducts = :productId");
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stockQuantity' => $stockQuantity,
            'category' => $category,
            'productId' => $productId
        ]);
    }
}

