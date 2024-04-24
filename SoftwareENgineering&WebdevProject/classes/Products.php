<?php

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
    public function removeFromCart($productId) {
        if (isset($_SESSION['cart']) && in_array($productId, $_SESSION['cart'])) {
            $index = array_search($productId, $_SESSION['cart']);
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);

            $stmt = $this->pdo->prepare("UPDATE Products SET StockQuantity = StockQuantity + 1 WHERE idProducts = :product_id");
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();
        }
    }
    public function getCartItems() {
        $items = [];
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            return $items;
        }

        foreach ($_SESSION['cart'] as $productId) {
            $stmt = $this->pdo->prepare("SELECT * FROM Products WHERE idProducts = :productId");
            $stmt->execute(['productId' => $productId]);
            $productData = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($productData) {
                if (!isset($items[$productId])) {
                    // Make sure the array keys match your database column names
                    $items[$productId] = [
                        'name' => $productData['Name'], // Adjust 'Name' if necessary
                        'price' => $productData['Price'], // Adjust 'Price' if necessary
                        'quantity' => 1
                    ];
                } else {
                    $items[$productId]['quantity']++;
                }
            }
        }
        return $items;
    }

    public function getProductById($productId, $newInstance = false) {
        $stmt = $this->pdo->prepare("SELECT * FROM Products WHERE idProducts = :productId");
        $stmt->execute(['productId' => $productId]);
        $productData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($productData) {
            $product = $newInstance ? new self($this->pdo) : $this;
            $product->idProducts = $productData['idProducts'];
            $product->Name = $productData['Name'];
            $product->Description = $productData['Description'];
            $product->Price = $productData['Price'];
            $product->StockQuantity = $productData['StockQuantity'];
            $product->Category = $productData['Category'];
            $product->idAdmin = $productData['idAdmin'];

            return $product;
        } else {
            return null;
        }
    }

    // Getters and setters
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

    // Updates a product in the database
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

    // Retrieves random products from the database
    public function getRandomProducts($limit = 5) {
        $stmt = $this->pdo->prepare("SELECT * FROM Products ORDER BY RAND() LIMIT :limit");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $products = [];
        while ($productData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new self($this->pdo);
            $product->idProducts = $productData['idProducts'];
            $product->Name = $productData['Name'];
            $product->Description = $productData['Description'];
            $product->Price = $productData['Price'];
            $product->StockQuantity = $productData['StockQuantity'];
            $product->Category = $productData['Category'];
            $product->idAdmin = $productData['idAdmin'];

            $products[] = $product;
        }

        return $products;
    }

    // Retrieves all products from the database
    public function getAllProducts() {
        $stmt = $this->pdo->query("SELECT * FROM Products");
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product = new self($this->pdo);
            $product->idProducts = $row['idProducts'];
            $product->Name = $row['Name'];
            $product->Description = $row['Description'];
            $product->Price = $row['Price'];
            $product->StockQuantity = $row['StockQuantity'];
            $product->Category = $row['Category'];
            $product->idAdmin = $row['idAdmin'];
            $products[] = $product;
        }
        return $products;
    }
    public function addProduct($idAdmin, $idProducts, $name, $description, $price, $stockQuantity, $category) {
        $stmt = $this->pdo->prepare("INSERT INTO Products (idAdmin, idProducts, Name, Description, Price, StockQuantity, Category) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$idAdmin, $idProducts, $name, $description, $price, $stockQuantity, $category]);
    }
    public function deleteProduct($productId) {
        $stmt = $this->pdo->prepare("DELETE FROM Products WHERE idProducts = ?");
        $stmt->execute([$productId]);
    }
}

?>
