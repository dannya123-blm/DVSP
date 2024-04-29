
<?php
// This code is based on the SymfonyCasts:
// Include header and necessary files
include '../template/header.php';
include '../src/dbconnect.php'; // Assuming this includes database connection
require '../classes/User.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, allow them to contact us
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Us</title>
        <link rel="stylesheet" href="../css/contactus.css">
    </head>
    <body>
    <div class="contact-container">
        <h2>Contact Us</h2>
        <p>Feel free to reach out to us for any inquiries or feedback!</p>
        <form method="post" action=index.php" class="custom-form">
            <label for="subject" class="form-label">Subject:</label>
            <input type="text" id="subject" name="subject" class="form-input" required><br><br>

            <label for="message" class="form-label">Message:</label><br>
            <textarea id="message" name="message" rows="6" class="form-textarea" required></textarea><br><br>

            <button type="submit" name="submit" class="form-button">Submit</button>
        </form>

    </div>
    </body>
    </html>
    <?php
} else {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// Include footer
include '../template/footer.php';
?>
