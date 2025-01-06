<?php
// Include database configuration
include 'db_config.php';

// Start session to check user login status
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch user information (name and dob)
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Calculate zodiac sign based on the user's DOB
$dob = $user['dob'];
$zodiac_id = getZodiacSign($dob);

// Fetch zodiac details (name, date_range, description, image)
$zodiac_sql = "SELECT * FROM zodiac_signs WHERE id = $zodiac_id";
$zodiac_result = $conn->query($zodiac_sql);
$zodiac = $zodiac_result->fetch_assoc();

// Fetch today's horoscope for the user's zodiac sign
$today = date('Y-m-d'); // Current date
$horoscope_sql = "SELECT * FROM horoscopes WHERE zodiac_id = $zodiac_id AND date = '$today'";
$horoscope_result = $conn->query($horoscope_sql);
$horoscope = $horoscope_result->fetch_assoc();

// Fetch all zodiac signs
$all_zodiacs_sql = "SELECT * FROM zodiac_signs";
$all_zodiacs_result = $conn->query($all_zodiacs_sql);

// Function to calculate zodiac sign based on DOB
function getZodiacSign($dob) {
    $zodiac_dates = [
        ['start' => '03-21', 'end' => '04-19', 'sign' => 1], // Aries
        ['start' => '04-20', 'end' => '05-20', 'sign' => 2], // Taurus
        ['start' => '05-21', 'end' => '06-20', 'sign' => 3], // Gemini
        ['start' => '06-21', 'end' => '07-22', 'sign' => 4], // Cancer
        ['start' => '07-23', 'end' => '08-22', 'sign' => 5], // Leo
        ['start' => '08-23', 'end' => '09-22', 'sign' => 6], // Virgo
        ['start' => '09-23', 'end' => '10-22', 'sign' => 7], // Libra
        ['start' => '10-23', 'end' => '11-21', 'sign' => 8], // Scorpio
        ['start' => '11-22', 'end' => '12-21', 'sign' => 9], // Sagittarius
        ['start' => '12-22', 'end' => '12-31', 'sign' => 10], // Capricorn (First part of Capricorn)
        ['start' => '01-01', 'end' => '01-19', 'sign' => 10], // Capricorn (Second part of Capricorn)
        ['start' => '01-20', 'end' => '02-18', 'sign' => 11], // Aquarius
        ['start' => '02-19', 'end' => '03-20', 'sign' => 12], // Pisces
    ];

    $month_day = date('m-d', strtotime($dob));
    foreach ($zodiac_dates as $zodiac) {
        if ($month_day >= $zodiac['start'] && $month_day <= $zodiac['end']) {
            return $zodiac['sign'];
        }
    }
    return 1; // Default to Aries if not found (this is a fallback)
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AstroVista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
         body {
            margin: 0;
            padding: 0;
            background: url('images/stars.gif') center center / cover no-repeat fixed;
            color: #333;
			background-color: #171738;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.8); /* Changed to white with the same opacity */
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .card h3, .card h4 {
            color: #007bff;
        }

        .card img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .btn-logout {
            display: block;
            margin: 20px auto 0;
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }

        .show-other-signs {
            display: block;
            margin: 20px auto;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
        }

        .zodiac-grid {
            display: none;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .zodiac-card-other {
            background-color: rgba(255, 255, 255, 0.8); /* Changed to white with the same opacity */
            border-radius: 10px;
            padding: 15px;
            width: 200px;
            text-align: center;
            transition: transform 0.3s;
        }

        .zodiac-card-other:hover {
            transform: scale(1.05);
        }

        .zodiac-card-other img {
            width: 80%;
            border-radius: 10px;
        }

        .zodiac-card-other h5 {
            color: #007bff;
            margin: 10px 0;
        }

        .zodiac-card-other p {
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card text-center">
            <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
        </div>

        <div class="card">
            <h3>Your Zodiac Sign: <?php echo htmlspecialchars($zodiac['name']); ?></h3>
            <img src="images/<?php echo $zodiac['image']; ?>" alt="<?php echo htmlspecialchars($zodiac['name']); ?>">
            <p><strong>Date Range:</strong> <?php echo htmlspecialchars($zodiac['date_range']); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($zodiac['description'])); ?></p>
        </div>

        <div class="card">
            <h4>Your Daily Horoscope</h4>
            <p>
                <?php echo $horoscope ? nl2br(htmlspecialchars($horoscope['content'])) : 'No horoscope available for today.'; ?>
            </p>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>

        <div class="show-other-signs" onclick="toggleOtherSigns()">View Other Zodiac Signs</div>

        <div class="zodiac-grid" id="other-signs">
            <?php while ($other_zodiac = $all_zodiacs_result->fetch_assoc()): ?>
                <div class="zodiac-card-other">
                    <img src="images/<?php echo $other_zodiac['image']; ?>" alt="<?php echo htmlspecialchars($other_zodiac['name']); ?>">
                    <h5><?php echo htmlspecialchars($other_zodiac['name']); ?></h5>
                    <p><?php echo htmlspecialchars($other_zodiac['date_range']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        function toggleOtherSigns() {
            const zodiacGrid = document.getElementById('other-signs');
            zodiacGrid.style.display = zodiacGrid.style.display === 'none' || zodiacGrid.style.display === '' ? 'flex' : 'none';
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
