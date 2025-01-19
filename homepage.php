<?php
session_start();
include("connect.php");

// Redirect to login page if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petaku - Pet and Dog Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f4f4f4;
            color: white;
        }

        header {
            background-color: rgba(76, 175, 80, 0.8);
            color: white;
            padding: 20px;
            text-align: center;
        }

        nav {
            display: flex;
            justify-content: center;
            background-color: rgba(51, 51, 51, 0.8);
        }

        nav a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
        }

        nav a:hover {
            background-color: rgba(221, 221, 221, 0.8);
            color: black;
        }

        .section {
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: white;
        }

        #food {
            background-image: url('images/food-background.jpg');
        }

        #vets {
            background-image: url('images/vets-background.jpg');
        }

        #shelters {
            background-image: url('images/shelters-background.jpg');
        }

        .section h2 {
            text-align: center;
            color: #FFD700;
        }

        .images-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .images-container img {
            max-width: 200px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: rgba(76, 175, 80, 0.8);
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Petaku</h1>
        <p>Your go-to platform for pet and dog management!</p>
        <p>Hello, <?php 
            $email = $_SESSION['email'];
            $query = mysqli_query($conn, "SELECT firstName, lastName FROM users WHERE email='$email'");
            if ($row = mysqli_fetch_assoc($query)) {
                echo $row['firstName'] . ' ' . $row['lastName'];
            }
        ?> :)</p>
        <a href="logout.php" style="color: white; text-decoration: underline;">Logout</a>
    </header>

    <nav>
        <a href="#food">Food</a>
        <a href="#vets">Vets</a>
        <a href="#shelters">Shelters</a>
    </nav>

    <main>
        <section id="food" class="section">
            <h2>Cat and Dog Food</h2>
            <p>Explore a variety of nutritious food options tailored for your pets. Whether you have a cat or a dog, find the best brands and recipes here.</p>
            <div class="images-container">
                <img src="images/cat-food.jpg" alt="Cat Food">
                <img src="images/dog-food.jpg" alt="Dog Food">
            </div>
        </section>

        <section id="vets" class="section">
            <h2>Cat and Dog Vets</h2>
            <p>Connect with professional veterinarians who specialize in cat and dog care. Get expert advice and book appointments easily.</p>
            <div class="images-container">
                <img src="images/cat-vet.jpg" alt="Cat Vet">
                <img src="images/dog-vet.jpg" alt="Dog Vet">
            </div>
        </section>

        <section id="shelters" class="section">
            <h2>Cat and Dog Shelters</h2>
            <p>Find safe and loving shelters for cats and dogs in need. Support or adopt to give them a better life.</p>
            <div class="images-container">
                <img src="images/cat-shelter.jpg" alt="Cat Shelter">
                <img src="images/dog-shelter.jpg" alt="Dog Shelter">
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Petaku. All rights reserved.</p>
    </footer>
</body>
</html>
