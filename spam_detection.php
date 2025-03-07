<?php
// Start session
session_start();

// Set default timezone to IST
date_default_timezone_set('Asia/Kolkata');

// Database Connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'spam_detection';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Spam Detection Function
function detectSpam($message) {
    $spamKeywords = ['free', 'win', 'prize', 'offer', 'cash','bumper','http','spin']; // Add more keywords
    foreach ($spamKeywords as $keyword) {
        if (stripos($message, $keyword) !== false) {
            return true; // Spam detected
        }
    }
    return false; // Not spam
}

// Handle Registration
if (isset($_POST['register'])) {
    $us_fname = $_POST['us_fname'];
    $us_lname = $_POST['us_lname'];
    $us_usname = $_POST['us_usname'];
    $us_pass = password_hash($_POST['us_pass'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (us_fname, us_lname, us_usname, us_pass)
            VALUES ('$us_fname', '$us_lname', '$us_usname', '$us_pass')";
    if ($conn->query($sql)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle Login
if (isset($_POST['login'])) {
    $us_usname = $_POST['us_usname'];
    $us_pass = $_POST['us_pass'];

    $sql = "SELECT * FROM users WHERE us_usname='$us_usname'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($us_pass, $row['us_pass'])) {
            $_SESSION['us_usname'] = $us_usname;
            header('Location: spam_detection.php');
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }
}

// Handle Compose Email
if (isset($_POST['compose'])) {
    $msg_to = $_POST['msg_to'];
    $msg_sub = $_POST['msg_sub'];
    $message = $_POST['message'];
    $msg_date = date('Y-m-d H:i:s'); // Current date and time in IST

    if (detectSpam($message)) {
        // Store spam message in the spam table
        $msg_from = $_SESSION['us_usname'];
        $sql = "INSERT INTO spam (msg_from, msg_to, msg_sub, message, msg_date)
                VALUES ('$msg_from', '$msg_to', '$msg_sub', '$message', '$msg_date')";
        if ($conn->query($sql)) {
            echo "Spam detected! Email stored in spam folder.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Store non-spam message in the mail table
        $msg_from = $_SESSION['us_usname'];
        $sql = "INSERT INTO mail (msg_from, msg_to, msg_sub, message, msg_date)
                VALUES ('$msg_from', '$msg_to', '$msg_sub', '$message', '$msg_date')";
        if ($conn->query($sql)) {
            echo "Email sent successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: spam_detection.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spam Detection System</title>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

h1 {
    font-size: 2.5rem;
    color: #2c3e50;
    margin-bottom: 20px;
    text-align: center;
}

h2 {
    font-size: 2rem;
    color: #34495e;
    margin-bottom: 15px;
    text-align: center;
}

.menu {
    margin-bottom: 30px;
    text-align: center;
}

.menu a {
    margin: 0 15px;
    text-decoration: none;
    color: #3498db;
    font-weight: 600;
    transition: color 0.3s ease;
}

.menu a:hover {
    color: #2980b9;
}

form {
    background: #ffffff;
    padding: 30px;
    margin: 40px auto;
    width: 100%;
    max-width: 400px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

input[type="text"],
input[type="password"],
textarea {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus,
input[type="password"]:focus,
textarea:focus {
    border-color: #3498db;
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    background: #3498db;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

button:hover {
    background: #2980b9;
}

.email-list {
    background: #ffffff;
    padding: 20px;
    margin: 20px auto;
    width: 100%;
    max-width: 800px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.email-list div {
    padding: 15px;
    border-bottom: 1px solid #eee;
    transition: background 0.3s ease;
}

.email-list div:last-child {
    border-bottom: none;
}

.email-list div:hover {
    background: #f8f9fa;
}

.email-list strong {
    color: #2c3e50;
    font-weight: 600;
}

.email-list .date {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-top: 5px;
}
    </style>
</head>
<body>
    <div class="container">
        <?php if (!isset($_SESSION['us_usname'])): ?>
            <h1>Welcome to Spam Detection System</h1>
            <div class="menu">
                <a href="spam_detection.php?action=login">Login</a> |
                <a href="spam_detection.php?action=register">Register</a>
            </div>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'register'): ?>
                <h2>Register</h2>
                <form action="spam_detection.php" method="POST">
                    <input type="text" name="us_fname" placeholder="First Name" required>
                    <input type="text" name="us_lname" placeholder="Last Name" required>
                    <input type="text" name="us_usname" placeholder="Username" required>
                    <input type="password" name="us_pass" placeholder="Password" required>
                    <button type="submit" name="register">Register</button>
                </form>
            <?php else: ?>
                <h2>Login</h2>
                <form action="spam_detection.php" method="POST">
                    <input type="text" name="us_usname" placeholder="Username" required>
                    <input type="password" name="us_pass" placeholder="Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
            <?php endif; ?>

        <?php else: ?>
            <h1>Welcome, <?php echo $_SESSION['us_usname']; ?>!</h1>
            <div class="menu">
                <a href="spam_detection.php?action=compose">Compose</a> |
                <a href="spam_detection.php?action=inbox">Inbox</a> |
                <a href="spam_detection.php?action=spam">Spam</a> |
                <a href="spam_detection.php?logout">Logout</a>
            </div>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'compose'): ?>
                <h2>Compose Email</h2>
                <form action="spam_detection.php" method="POST">
                    <input type="text" name="msg_to" placeholder="To" required>
                    <input type="text" name="msg_sub" placeholder="Subject" required>
                    <textarea name="message" placeholder="Message" required></textarea>
                    <button type="submit" name="compose">Send</button>
                </form>
            <?php elseif (isset($_GET['action']) && $_GET['action'] == 'inbox'): ?>
                <h2>Inbox</h2>
                <div class="email-list">
                    <?php
                    $us_usname = $_SESSION['us_usname'];
                    $sql = "SELECT * FROM mail WHERE msg_to='$us_usname'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div>";
                            echo "<div><strong>From:</strong> " . $row['msg_from'] . "</div>";
                            echo "<div><strong>Subject:</strong> " . $row['msg_sub'] . "</div>";
                            echo "<div><strong>Message:</strong> " . $row['message'] . "</div>";
                            echo "<div class='date'><strong>Date:</strong> " . $row['msg_date'] . " (IST)</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<div>No emails found.</div>";
                    }
                    ?>
                </div>
            <?php elseif (isset($_GET['action']) && $_GET['action'] == 'spam'): ?>
                <h2>Spam</h2>
                <div class="email-list">
                    <?php
                    $us_usname = $_SESSION['us_usname'];
                    $sql = "SELECT * FROM spam WHERE msg_to='$us_usname'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div>";
                            echo "<div><strong>From:</strong> " . $row['msg_from'] . "</div>";
                            echo "<div><strong>Subject:</strong> " . $row['msg_sub'] . "</div>";
                            echo "<div><strong>Message:</strong> " . $row['message'] . "</div>";
                            echo "<div class='date'><strong>Date:</strong> " . $row['msg_date'] . " (IST)</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<div>No spam emails found.</div>";
                    }
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>

