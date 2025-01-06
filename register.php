<?php
// Include database configuration
include 'db_config.php';

// Initialize feedback variable
$feedback = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        $feedback = "Email already exists. Please use a different email.";
    } else {
        // Insert the user into the database
        $sql = "INSERT INTO users (name, gender, dob, email, password) VALUES ('$name', '$gender', '$dob', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            $feedback = "Account created successfully!";
        } else {
            $feedback = "Error: " . $conn->error;
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AstroVista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #171738; 
            overflow: auto; 
        }

        /* Background GIF */
        .background-gif {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: url('images/stars.gif') center center / cover no-repeat;
            opacity: 0.6; 
        }

        .content-box {
            background-color: #ffffff;
            color: #171738;
            padding: 30px;
            border-radius: 15px;
            max-width: 600px;
            margin: auto;
            margin-top: 50px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 1;
            position: relative;
        }

        .btn-custom {
            background-color: #2E1760;
            color: #ffffff;
            border: none;
        }

        .btn-custom:hover {
            background-color: #502290;
        }

        h1, h2 {
            color: #171738;
        }
    </style>
</head>

<body>
    <!-- Background GIF -->
    <div class="background-gif"></div>

    <div class="container text-center">
        <div class="content-box">
            <img src="images/logo.png" alt="Astro World Logo" width="250" height="250">
            <h2>Register</h2>
            <?php if (!empty($feedback)): ?>
                <div class="alert alert-info"><?php echo $feedback; ?></div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" id="gender" name="gender" required>
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-custom">Register</button>
            </form>
            <br>
            <a href="login.php" class="btn btn-custom">Login</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
