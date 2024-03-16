<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
   exit(); // Make sure to exit after redirection
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "database.php";

    // Retrieve form data
    $centreName = $_POST["centre_name"];
    $centreLocation = $_POST["centre_location"];
    $contactNumber = $_POST["contact_number"];
    $email = $_POST["email_id"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];
    $landlineNumber = $_POST["landline_number"];
    $authorisedPerson = $_POST["authorised_person"];
    $availabilityOfASV = $_POST["availability_of_asv"];
    $currentStockOfASV = $_POST["current_stock_of_asv"];
    $description = $_POST["description"];

    $errors = array();

    // Validation
    if (empty($centreName) || empty($centreLocation) || empty($contactNumber) || empty($email) || empty($password) || empty($passwordRepeat) || empty($landlineNumber) || empty($authorisedPerson) || empty($availabilityOfASV) || empty($currentStockOfASV) || empty($description)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email ID is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $passwordRepeat) {
        array_push($errors, "Password does not match");
    }

    // Check if email already exists
    $sql = "SELECT * FROM mydataset WHERE email_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rowCount = mysqli_num_rows($result);
        if ($rowCount > 0) {
            array_push($errors, "Email already exists!");
        }
    } else {
        die("Something went wrong");
    }

    // If no errors, insert into database
    if (count($errors) == 0) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO mydataset (password, centre_name, centre_location, contact_number, email_id, landline_number, authorised_person, availability_of_asv, current_stock_of_asv, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssssssss", $passwordHash, $centreName, $centreLocation, $contactNumber, $email, $landlineNumber, $authorisedPerson, $availabilityOfASV, $currentStockOfASV, $description);
            mysqli_stmt_execute($stmt);
            echo "<div class='alert alert-success'>You are registered successfully.</div>";
        } else {
            die("Something went wrong");
        }
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
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
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <img src="logo.png" alt="Logo" class="logo">
        <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="centre_name" placeholder="Centre Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="centre_location" placeholder="Centre Location">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="contact_number" placeholder="Contact Number">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email_id" placeholder="Email ID">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="landline_number" placeholder="Landline Number">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="authorised_person" placeholder="Authorised Person">
            </div>
            <div class="form-group">
                <label for="availability_of_asv">Availability of ASV:</label>
                <select class="form-control" name="availability_of_asv" id="availability_of_asv">
                    <option value="">Select Availability</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="current_stock_of_asv" placeholder="Current Stock of ASV">
            </div>
            <div class="form-group">
                <textarea class="form-control" name="description" placeholder="Description"></textarea>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div>
            <p>Already Registered <a href="login.php">Login Here</a></p>
        </div>
    </div>
</body>
</html>

