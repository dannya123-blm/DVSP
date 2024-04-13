<?php
require '../template/header.php';
require '../classes/Customer.php'; // Update the path to Customer.php
require '../src/dbconnect.php'; // Include your database connection

// Assuming $pdo is your database connection instance
$customer = new Customer($pdo); // Pass $pdo to the Customer constructor

// Set a new address using the setAddress method
$newAddress = "Half&Half, Unit 42a Coolmine Industrial Estate, Blanchardstown, Dublin, Ireland Dublin 15";
$customer->setAddress($newAddress);

// Get the updated address using getAddress method
$address = $customer->getAddress();

?>

<h1>Find Us</h1>
<link rel="stylesheet" href="../css/findus.css">
<div class="map-container">
    <div class="map-frame">
        <iframe src="https://www.openstreetmap.org/export/embed.html?bbox=-6.3900%2C53.3858%2C-6.3885%2C53.3866&amp;layer=mapnik&amp;marker=53.3862%2C-6.3892" allowfullscreen></iframe>
    </div>
    <div class="map-info">
        <p>Location: <?php echo $address; ?></p>
        <p>Number: +0858096251</p>

        <h2>Socials:</h2>
        <ul>
            <li><a href="https://www.instagram.com/dvsexpansion/">Instagram</a></li>
            <li><a href="https://facebook.com">Facebook</a></li>
            <li><a href="https://twitter.com">Twitter</a></li>
            <li><a href="https://www.linkedin.com/in/daniel-aigbe-184057253/">Daniel Aigbe: LinkedIn</a></li>
            <li><a href="https://www.linkedin.com/in/adefolajuwon-adeniran-9918b7258/">Adefolajuwon Adeniran: LinkedIn</a></li>
            <li><a href="https://www.linkedin.com/in/salem-elatrash/">Salem Elatrash: LinkedIn</a></li>
        </ul>
    </div>
</div>

<?php require '../template/footer.php'; ?>
