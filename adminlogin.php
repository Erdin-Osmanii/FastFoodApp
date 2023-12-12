<?php
session_start();

// Check if the user is already logged in, redirect to admin.php

// Rest of the code...
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
    <style>
        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #ffffff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        input::placeholder {
            color: #999999;
        }

        input:focus {
            outline: none;
            border-color: #66afe9;
            box-shadow: 0 0 5px #66afe9;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }

        .keni_llogari,
        .pa_llogari {
            text-decoration: none;
            color: black;
            display: block;
            text-align: center;
            font-size: smaller;
        }

        .terms {
            color: black;
        }
    </style>
</head>
<body>
    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $username = isset($_POST["username"]) ? $_POST["username"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";

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

        // Prepare and execute the query
        $sql = "SELECT * FROM administratoret WHERE emri = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        // Check if the query returned any rows
        if ($result && $result->num_rows > 0) {
            // User is authenticated, set session variable and redirect to admin.php
            $_SESSION['username'] = $username;
            header("Location: admin.php");
            exit;
        } else {
            // Invalid username or password
            echo "Invalid username or password.";
        }

        // Close the database connection
        $conn->close();
    }
    ?>

    <h2>Kyçja e administratorit</h2>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="username">Emri:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Fjalëkalimi:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Kyçu">
    </form>
</body>
</html>