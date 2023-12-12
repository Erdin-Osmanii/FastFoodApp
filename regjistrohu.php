<?php
session_start();
$host = 'localhost';
$db_name = 'fooddelivery';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Emri = $_POST['emri'] ?? '';
    $Email = $_POST['email'] ?? '';
    $Password = $_POST['password'] ?? '';
    $Adresa = $_POST['adresa'] ?? '';
    $NrTel = $_POST['nrtel'] ?? '';

    $conn = new mysqli($host, 'root', '', $db_name);
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        // Check if terms and conditions are accepted
        if (isset($_POST['terms'])) {
            $stmt = $conn->prepare("INSERT INTO regjistrimi (Emri, Email, Password, Adresa, NrTel) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $Emri, $Email, $Password, $Adresa, $NrTel);
            $stmt->execute();
            echo "Registration successful...";
            $stmt->close();
        } else {
            echo "Please accept the Terms & Conditions.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./styles/regjistrohu.css">
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
    <form action="regjistrohu.php" method="post">
        <input type="text" name="emri" placeholder="Emri">
        <input type="text" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <input type="text" name="adresa" placeholder="Adresa">
        <input type="text" name="nrtel" placeholder="Numri i telefonit">
        <div class="terms-checkbox">
            <input type="checkbox" name="terms" id="terms-checkbox">
            <label for="terms-checkbox">A pajtoheni me <a href="terms.php" class="terms">Terms & Conditions</a> dhe <a href="privacyPolicy.php" class="terms">Privacy Policy</a></label>
        </div>
        <input type="submit" value="Regjistrohu">
    </form>
    <a href="./kycu.php" class="keni_llogari">Keni llogari? Kycu</a>
    <a href="" class="pa_llogari">Vazhdo pa krijuar llogari</a>
    <p class="form-message">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['terms'])) {
                echo "Please accept the Terms & Conditions.";
            }
        }
        ?>
    </p>
    </main>
</body>
</html>
