<?php
session_start();
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signin'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate user inputs
    if (empty($email) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors']['login'] = "Invalid email or password.";
        header("Location: index.php");
        exit();
    }

    try {
        // Query the database to verify the user
        $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password directly
            if ($password === $user['pwd']) {
                $_SESSION['email'] = $user['email'];
                header("Location: homepage.php");
                exit();
            } else {
                $_SESSION['errors']['login'] = "Incorrect Email/Password.";
                header("Location: index.php");
                exit();
            }
        } else {
            // If user is not found, register them in the database
            $created_at = date("Y-m-d");

            $insert_query = $conn->prepare("INSERT INTO users (username, pwd, email, created_at) VALUES (?, ?, ?, ?)");
            $username = explode('@', $email)[0]; // Use email prefix as default username
            $insert_query->bind_param("ssss", $username, $password, $email, $created_at);

            if ($insert_query->execute()) {
                $_SESSION['email'] = $email;
                header("Location: homepage.php");
                exit();
            } else {
                $_SESSION['errors']['login'] = "An error occurred during registration.";
                header("Location: index.php");
                exit();
            }
        }
    } catch (Exception $e) {
        // Log the error and show a generic error message
        error_log("Database error: " . $e->getMessage());
        $_SESSION['errors']['login'] = "An error occurred. Please try again later.";
        header("Location: index.php");
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="container" id="signIn">
    <h1 class="form-title">Sign In</h1>
    <?php
    if (isset($_SESSION['errors']['login'])) {
      echo '<div class="error-main">
                <p>' . $_SESSION['errors']['login'] . '</p>
            </div>';
      unset($_SESSION['errors']['login']);
    }
    ?>
    <form id="loginform" method="POST" action="">
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="email" placeholder="Email" required>
      </div>
      <div class="input-group password">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <i id="eye" class="fa fa-eye"></i>
      </div>
      <p class="recover">
        <a href="#">Recover Password</a>
      </p>
      <input type="submit" class="btn" value="Sign In" name="signin">
    </form>

    <p class="or">----------or--------</p>
    <div class="icons">
      <i class="fab fa-google"></i>
      <i class="fab fa-facebook"></i>
    </div>
    <div class="links">
      <p>Don't have an account yet?</p>
      <a href="register.php">Sign Up</a>
    </div>
  </div>
</body>

</html>
