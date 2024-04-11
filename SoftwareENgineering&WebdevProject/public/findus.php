<?php
require '../template/header.php';
require '../classes/Customer.php'; // Update the path to Customer.php

// Create an instance of the Customer class
$customer = new Customer();

// Set a new address using the setAddress method
$newAddress = "Half&Half, Unit 42a Coolmine Industrial Estate, Blanchardstown, Dublin, Ireland Dublin 15";
$customer->setAddress($newAddress);

// Get the updated address using getAddress method
$address = $customer->getAddress();

?>

<h2>Find Us</h2>
<link rel="stylesheet" href="../css/findus.css">
<div class="map-container">
    <div class="map-frame">
        <iframe src="https://www.openstreetmap.org/export/embed.html?bbox=-6.3900%2C53.3858%2C-6.3885%2C53.3866&amp;layer=mapnik&amp;marker=53.3862%2C-6.3892" allowfullscreen></iframe>
    </div>
    <div class="map-info">
        <p>Location: <?php echo $address; ?></p>
        <p>Number: +123456789</p>
        <p>Socials:
            <a href="https://instagram.com">Instagram</a>,
            <a href="https://facebook.com">Facebook</a>,
            <a href="https://twitter.com">Twitter</a>,
            <a href="https://linkedin.com">LinkedIn</a>
        </p>
    </div>
</div>

<?php require '../template/footer.php'; ?>
