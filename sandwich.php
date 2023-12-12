<?php
    session_start();

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the new address from the form
        $newAddress = $_POST['new_address'];

        // Update the user's address in the database
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
            try {
                $host = 'localhost';
                $dbname = 'fooddelivery';
                $username = 'root';
                $password = '';

                $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = "UPDATE regjistrimi SET Adresa = :address WHERE Email = :email";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':address', $newAddress);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                // Display success message
                echo "<div class='success-message'>Address updated successfully!</div>";
            } catch (PDOException $e) {
                // Display error message
                echo "<div class='error-message'>Error: " . $e->getMessage() . "</div>";
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="bigCnt bigCntHeader">
            <div class="logo"></div> 
            <h1>Fast Food</h1>
       </div>
    </header>
    <main>
        <div class="pjesaKryesoreListaDheUshqimet">
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

        <div class="gjithaUshqimet">
            <?php
                $host = 'localhost';
                $dbname = 'fooddelivery';
                $username = 'root';
                $password = '';

                try {
                    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $query = "SELECT emri, detajet, cmimi, imazhi FROM ushqimet WHERE LOWER(lloji) = 'sandwich'";
                    $stmt = $conn->query($query);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    //data to be displayed
                    if ($stmt->rowCount() > 0) {
                        foreach ($result as $row) {
                            echo "<div class='ushqimi'>";
                            echo "<div class='oferta-emri'>".$row['emri']."</div>";
                            echo "<div class='oferta-detajet'>".$row['detajet']."</div>";
                            echo "<div class='ushqimiBottom'>";
                            echo "<div class='oferta-cmimi'>".$row['cmimi']."€</div>";
                            echo "<button class='add-to-cart' data-name='".$row['emri']."' data-price='".$row['cmimi']."'>Shto në shportë</button>";
                            echo "</div>";
                            echo "<div class='imazhi'><img src='".$row['imazhi']."' alt=''  /></div>";
                            echo "</div>";
                        }
                    } else {
                        echo "No records found.";
                    }
                } catch(PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
            ?>
        </div>

        <div class="miniCartTotal">
        <span class="totalPrice">Shporta:</span>
            <div id="chart"></div>    
            <div class="totalPrice">Çmimi total: <span id="total">0.00</span>€</div>
            <div id="checkout">
                <div class="payment-methods">
                    <button id="cash-button">Pay with Cash</button>
                    <button id="paypal-button">Pay with PayPal</button>
                </div>
                <?php
                    // Fetch user address based on the logged-in user
                    if (isset($_SESSION['email'])) {
                        $email = $_SESSION['email'];
                        try {
                            $query = "SELECT Adresa FROM regjistrimi WHERE Email = :email";
                            $stmt = $conn->prepare($query);
                            $stmt->bindParam(':email', $email);
                            $stmt->execute();
                            $user = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($stmt->rowCount() > 0) {
                            $address = $user['Adresa'];
                            echo "<div class='user-address'>Adresa: " . $address . "</div>";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                }
            ?>

            <form action="" method="post">
                <label for="new_address">Ndrysho Adresën:</label>
                <input type="text" name="new_address" id="new_address" placeholder="Enter new address" required>
                <button type="submit">Ndrysho</button>
            </form>
        </div>
        </div>
 
            </div>
        </div>
    </div>

    <script src="https://www.paypal.com/sdk/js?client-id=AUIM-g4xxRtmJM6W4Wyrb4fMmVE6fN2WDcRUPgJAlg2UWo38DBbq1kSD4hP2WloBMaTQ9mgA1nAT5Ohi&currency=EUR"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>
</html>