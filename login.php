<?php
// Include database configuration
include 'db_config.php';

// Initialize feedback variable
$feedback = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if email exists
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Start session and redirect to dashboard.php
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            $feedback = "Incorrect password.";
        }
    } else {
        $feedback = "No user found with this email.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AstroVista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #171738; /* Fallback for when GIF doesn't load */
            overflow: hidden;
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
            opacity: 0.6; /* Optional: Adjust the transparency */
        }

        .content-box {
            background-color: #ffffff;
            color: #171738;
            padding: 30px;
            border-radius: 15px;
            max-width: 600px;
            margin: auto;
            margin-top: 100px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 1;
            position: relative;
        }

        .content-box img {
            max-width: 150px;
            margin-bottom: 20px;
        }

        .btn-custom {
            background-color: #2E1760;
            color: #ffffff;
            border: none;
        }

        .btn-custom:hover {
            background-color: #502290;
        }

        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Background GIF -->
    <div class="background-gif"></div>

    <div class="container text-center">
        <div class="content-box">
            <!-- Logo -->
            <img src="images/logo.png" alt="Astro World Logo">
            <h2>Login</h2>
            <?php if (!empty($feedback)): ?>
                <div class="alert alert-danger"><?php echo $feedback; ?></div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-custom">Login</button>
            </form>
            <br>
            <a href="register.php" class="btn btn-custom">Register</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
