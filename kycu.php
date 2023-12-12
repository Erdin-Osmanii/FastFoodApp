<?php
session_start();
// Database connection parameters
$host = 'localhost';
$dbName = 'fooddelivery';
$username = 'root';
$password = '';

// Establish database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input from the login form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT * FROM regjistrimi WHERE Email = :email AND Password = :password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Check if a matching user was found
    if ($stmt->rowCount() > 0) {
        // User is authenticated, store email in session and redirect to index.php
        $_SESSION['email'] = $email;
        header("Location: index.php");
        exit();
    } else {
        // User authentication failed, display an error message
        $errorMessage = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./styles/kycu.css">
</head>
<body>
<header>
        <div class="bigCnt bigCntHeader">
            <div class="logo"></div> 
            <h1>Fast Food</h1>
       </div>
    </header>
    <main>
        <ul class="lista_e_ushqimeve">
            <li><a href="./index.php">Oferta</a></li>
            <li><a href="./sandwich.php">Sandwich-a</a></li>
            <li><a href="./hamburger.php">Hamburgera</a></li>
            <li><a href="./pizza.php">Pizza</a></li>
            <li><a href="./sallata.php">Sallata</a></li>
            <li><a href="./pije.php">Pije</a></li>
            <li><a href="./kycu.php">Kyçu</a></li>
            <li><a href="./regjistrohu.php">Regjistrohu</a></li>
        </ul>
        <div class="kycu">
        <h1>Kyçu</h1>
        <?php if (isset($errorMessage)) { ?>
            <p><?php echo $errorMessage; ?></p>
            <?php } ?>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required>
        <br>
        <input type="password" name="password" placeholder="Password" required>
        <br>
        <input type="submit" value="Kyçu">
        </div>
    </form>
    </main>
</body>
</html>
