<?php
    session_start();
   if (!isset($_SESSION['username'])) {
    header('Location: adminlogin.php');
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Food</title>
    <style>
        .add-food {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .add-food label {
            display: block;
            margin-bottom: 10px;
            color: #333333;
        }

        .add-food input[type="text"],
        .add-food textarea,
        .add-food select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .add-food input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #ffffff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .add-food input[type="submit"]:hover {
            background-color: #45a049;
        }

        .add-food textarea::placeholder,
        .add-food input::placeholder {
            color: #999999;
        }

        .add-food textarea:focus,
        .add-food input:focus {
            outline: none;
            border-color: #66afe9;
            box-shadow: 0 0 5px #66afe9;
        }

        .add-food .title {
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }

        .add-food p.form-message {
            text-align: center;
            font-size: 14px;
            color: #333333;
        }

        #deleteFoodDiv {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #deleteFoodDiv h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }

        #deleteFoodDiv form {
            text-align: center;
        }

        #deleteFoodDiv label {
            display: block;
            margin-bottom: 10px;
            color: #333333;
        }

        #deleteFoodDiv select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        #deleteFoodDiv button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #ff0000;
            color: #ffffff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        #deleteFoodDiv button[type="submit"]:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <?php

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if delete action is requested
        if (isset($_POST["deleteFood"]) && !empty($_POST["foodId"])) {
            $foodId = $_POST["foodId"];

            // Database credentials
            $servername = "localhost";
            $db_username = "root";
            $db_password = "";
            $dbname = "fooddelivery";

            // Create a connection
            $conn = new mysqli($servername, $db_username, $db_password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and execute the query to delete the food item
            $sql = "DELETE FROM ushqimet WHERE id = '$foodId'";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='form-message'>Food deleted successfully.</p>";
            } else {
                echo "<p class='form-message'>Error deleting food: " . $conn->error . "</p>";
            }

            // Close the database connection
            $conn->close();
        } else {
            // Retrieve form data
            $foodName = isset($_POST["foodName"]) ? $_POST["foodName"] : "";
            $details = isset($_POST["details"]) ? $_POST["details"] : "";
            $price = isset($_POST["price"]) ? $_POST["price"] : "";
            $foodType = isset($_POST["foodType"]) ? $_POST["foodType"] : "";
            $imageSource = isset($_POST["imSource"]) ? $_POST["imSource"] : "";

            // Database credentials
            $servername = "localhost";
            $db_username = "root";
            $db_password = "";
            $dbname = "fooddelivery";

            // Create a connection
            $conn = new mysqli($servername, $db_username, $db_password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and execute the query to insert the new food item
            $sql = "INSERT INTO ushqimet (emri, detajet, cmimi, lloji, imazhi) VALUES ('$foodName', '$details', '$price', '$foodType', '$imageSource')";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='form-message'>Food added successfully.</p>";
            } else {
                echo "<p class='form-message'>Error adding food: " . $conn->error . "</p>";
            }

            // Close the database connection
            $conn->close();
        }
    }
    ?>

    <div class="add-food">
        <h2 class="title">Add Food</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label for="foodName">Emri i ushqimit:</label>
            <input type="text" id="foodName" name="foodName" required><br>

            <label for="details">Detajet:</label>
            <textarea id="details" name="details" placeholder="Shkruaj detajet e ushqimit" required></textarea><br>

            <label for="price">Çmimi:</label>
            <input type="number" id="price" name="price" step="0.01" required><br>

            <label for="foodType">Lloji:</label>
            <select id="foodType" name="foodType">
                <option value="Ofertë">Ofertë</option>
                <option value="Sandwich">Sandwich</option>
                <option value="Hamburger">Hamburger</option>
                <option value="Pizza">Pizza</option>
                <option value="Sallate">Sallate</option>
                <option value="Pije">Pije</option>
            </select><br>

            <label for="imSource">Burimi i imazhit:</label>
            <input type="text" id="imSource" name="imSource" required>

            <input type="submit" value="Shto Ushqimin">
        </form>
    </div>

    <div id="deleteFoodDiv">
        <h2>Shlyej ushqimin</h2>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label for="foodId">Zgjedh:</label>
            <select id="foodId" name="foodId">
                <?php
                // Fetch food items from the database
                $servername = "localhost";
                $db_username = "root";
                $db_password = "";
                $dbname = "fooddelivery";

                // Create a connection
                $conn = new mysqli($servername, $db_username, $db_password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Retrieve food items
                $sql = "SELECT id, emri FROM ushqimet";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $foodId = $row['id'];
                        $foodName = $row['emri'];
                        echo "<option value='$foodId'>$foodName</option>";
                    }
                }

                // Close the database connection
                $conn->close();
                ?>
            </select><br>

            <button type="submit" name="deleteFood">Shlyej ushqimin</button>
        </form>
    </div>
</body>
</html>
