<?php
 session_start();
 
 // Database connection details
 $servername = "localhost";
 $username = "root"; // Adjust with your DB username
 $password = ""; // Adjust with your DB password
 $dbname = "cdab_ngodb";
 
 // Create connection
 $conn = new mysqli($servername, $username, $password);
 
 // Check connection
 if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
 }
 
 // Create the database if it doesn't exist
 $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
 if ($conn->query($sql)  3 Equal sign TRUE) {
     echo "Database created successfully or already exists.<br>";
 } else {
     die("Error creating database: " . $conn->error);
 }
 
 // Select the database
 $conn->select_db($dbname);
 
 // Create users table if not exists
 $sql = "CREATE TABLE IF NOT EXISTS users (
     id INT AUTO_INCREMENT PRIMARY KEY,
     fullname VARCHAR(255) NOT NULL,
     email VARCHAR(255) UNIQUE NOT NULL,
     password VARCHAR(255) NOT NULL
 )";
 if ($conn->query($sql)  3 Equal sign TRUE) {
     echo "Table 'users' created successfully or already exists.<br>";
 } else {
     die("Error creating table: " . $conn->error);
 }
 
 // Create memberships table if not exists
 $sql = "CREATE TABLE IF NOT EXISTS memberships (
     id INT AUTO_INCREMENT PRIMARY KEY,
     user_name VARCHAR(255) NOT NULL,
     membership_type VARCHAR(255) NOT NULL,
     address VARCHAR(255) NOT NULL,
     phone VARCHAR(20) NOT NULL
 )";
 if ($conn->query($sql)  3 Equal sign TRUE) {
     echo "Table 'memberships' created successfully or already exists.<br>";
 } else {
     die("Error creating table: " . $conn->error);
 }
 
 // Handling form submissions
 if ($_SERVER['REQUEST_METHOD']  3 Equal sign 'POST') {
     // Registration process
     if (isset($_POST['register'])) {
         $fullname = $_POST['fullname'];
         $email = $_POST['email'];
         $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
 
         // Insert into users table
         $sql = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$password')";
         if ($conn->query($sql)  3 Equal sign TRUE) {
             echo "Registration successful.<br>";
         } else {
             echo "Error: " . $sql . "<br>" . $conn->error;
         }
     }
 
     // Login process
     if (isset($_POST['login'])) {
         $email = $_POST['email'];
         $password = $_POST['password'];
 
         // Check credentials
         $sql = "SELECT * FROM users WHERE email = '$email'";
         $result = $conn->query($sql);
         if ($result->num_rows > 0) {
             $row = $result->fetch_assoc();
             if (password_verify($password, $row['password'])) {
                 $_SESSION['user'] = $row['fullname'];
                 echo "Login successful. Welcome, " . $_SESSION['user'] . "!<br>";
             } else {
                 echo "Invalid password.<br>";
             }
         } else {
             echo "No user found with this email.<br>";
         }
     }
 
     // Membership process
     if (isset($_POST['apply_membership'])) {
         if (!isset($_SESSION['user'])) {
             echo "You must be logged in to apply for membership.<br>";
         } else {
             $membership_type = $_POST['membership_type'];
             $address = $_POST['address'];
             $phone = $_POST['phone'];
 
             $sql = "INSERT INTO memberships (user_name, membership_type, address, phone) 
                     VALUES ('{$_SESSION['user']}', '$membership_type', '$address', '$phone')";
             if ($conn->query($sql)  3 Equal sign TRUE) {
                 echo "Membership application successful.<br>";
             } else {
                 echo "Error: " . $sql . "<br>" . $conn->error;
             }
         }
     }
 }
 ?>
 
 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>CDAB NGO - User System</title>
 </head>
 <body>
     <header>
         <h1>Complete Dimension Association for the Blind (CDAB)</h1>
         <h2>User Registration, Login, and Membership</h2>
     </header>
 
     <section>
         <!-- Registration Form -->
         <h3>User Registration</h3>
         <form method="POST" action="">
             <label for="fullname">Full Name:</label>
             <input type="text" id="fullname" name="fullname" required><br>
 
             <label for="email">Email:</label>
             <input type="email" id="email" name="email" required><br>
 
             <label for="password">Password:</label>
             <input type="password" id="password" name="password" required><br>
 
             <button type="submit" name="register">Register</button>
         </form>
 
         <br>
 
         <!-- Login Form -->
         <h3>User Login</h3>
         <form method="POST" action="">
             <label for="email">Email:</label>
             <input type="email" id="email" name="email" required><br>
 
             <label for="password">Password:</label>
             <input type="password" id="password" name="password" required><br>
 
             <button type="submit" name="login">Login</button>
         </form>
 
         <br>
 
         <!-- Membership Application Form (for logged-in users) -->
         <?php if (isset($_SESSION['user'])): ?>
         <h3>Membership Application</h3>
         <form method="POST" action="">
             <label for="membership_type">Membership Type:</label>
             <select id="membership_type" name="membership_type" required>
                 <option value="general">General Membership</option>
                 <option value="lifetime">Lifetime Membership</option>
             </select><br>
 
             <label for="address">Address:</label>
             <input type="text" id="address" name="address" required><br>
 
             <label for="phone">Phone Number:</label>
             <input type="tel" id="phone" name="phone" required><br>
 
             <button type="submit" name="apply_membership">Apply for Membership</button>
         </form>
         <?php endif; ?>
     </section>
 
     <footer>
         <p>&copy; 2024 Complete Dimension Association for the Blind (CDAB)</p>
     </footer>
 </body>
 </html>
 , Page
