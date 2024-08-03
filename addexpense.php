<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once 'includes/dbh.inc.php';
    
    $name = $_POST["name"];
    $price = $_POST["price"];
    $date = $_POST["date"];

    $username = $_SESSION["username"];

    $query = "INSERT INTO expenses (name, price, date, username) VALUES (:name, :price, :date, :username)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':name' => $name, ':price' => $price, ':date' => $date, ':username' => $username));
    
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expense Add</title>
  <style>
      body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #4d62bd;
    background-image: url('expense.jpg');
    background-size: cover; 
    background-repeat: no-repeat;
    display: flex;
}

.sidebar {
    width: 200px;
    background-color: #4d62bd;
    color: #ffffff;
    padding: 20px;
    height: 800px;
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.sidebar li {
    margin-bottom: 10px;
}

.sidebar a {
    color: #ffffff;
    text-decoration: none;
    font-size: 18px;
}

.container {
    width: 500px;
    margin: 80px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    height: 300px;
}

h1 {
    text-align: center;
    color: #1c1c2a;
}

input[type="text"] {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

select {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.btn-add {
    background-color: #4d62bd;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    display: block;
    text-align: center;
    font-size: 16px;
}

.btn-add:hover {
    background-color: #1c1c2a;
}


 

  </style>
</head>
<body>

  <div class="sidebar">
    <ul>
    <li><h3><a href="dashboard.php">Dashboard</a></h3></li>
      <li><h3><a href="addexpense.php">Add Expenses</a></h3></li>
      <li><h3><a href="delete.php">Delete Expenses</a></h3></li>
      <li><h3><a href="expensereport.php">Expense Report</a></h3></li>
      <li><h3><a href="logout.php">Logout</a></h3></li>
    </ul>
    </ul>
  </div>

  <div class="container">
  <h1>Add Transaction</h1>
  <form id="transaction-form" method="POST" action="addexpense.php">
    Enter amount:<br>
    <input type="text" id="price" name="price" placeholder="Amount" required><br>
    Enter item:
    <input type="text" id="name" name="name" placeholder="Item" required><br>
    Enter date:<br>
    <input type="date" id="date" name="date" required><br><br>
    <button type="submit" class="btn-add">Add</button>
  </form>
</div>
</body>
</html>



