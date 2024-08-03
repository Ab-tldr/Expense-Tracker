<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/dbh.inc.php';

function fetchExpenses($period, $username) {
    global $pdo;

    switch ($period) {
        case 'today':
            $query = "SELECT SUM(price) AS total_expense FROM expenses WHERE DATE(date) = CURDATE() AND username = :username;";
            break;
        case 'yesterday':
            $query = "SELECT SUM(price) AS total_expense FROM expenses WHERE DATE(date) = CURDATE() - INTERVAL 1 DAY AND username = :username;";
            break;
        case 'monthly':
            $query = "SELECT SUM(price) AS total_expense FROM expenses WHERE MONTH(date) = MONTH(CURDATE()) AND username = :username;";
            break;
        case 'yearly':
            $query = "SELECT SUM(price) AS total_expense FROM expenses WHERE YEAR(date) = YEAR(CURDATE()) AND username = :username;";
            break;
        default:
            return 0;
    }
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $username); 
    $stmt->execute(); 
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total_expense'] ? $result['total_expense'] : 0;
}
$username = $_SESSION["username"];

$today_expense = fetchExpenses('today', $username);
$yesterday_expense = fetchExpenses('yesterday', $username);
$monthly_expense = fetchExpenses('monthly', $username);
$yearly_expense = fetchExpenses('yearly', $username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expense Tracker Dashboard</title>
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
      flex: 1;
      padding: 20px;
    }

    h1 {
      text-align: center;
      color: #d5d5da;
      background-color: black;
      padding: 10px;
      border-radius: 5px;
      margin-left: 500px;
      margin-bottom: 20px;
      display: inline-block;
      
    }

    .expense-card {
      background-color: #d5d5da;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.1);
    }

    .expense-card h2 {
      color: #1f1f29;
    }

    .expense-card p {
      margin: 10px 0;
    }

    .expense-value {
      font-size: 24px;
      font-weight: bold;
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
    <h1>Dashboard</h1>

    <div class="expense-card">
      <h2>Today's Expense</h2>
      <p class="expense-value">₹<?php echo $today_expense; ?></p>
    </div>

    <div class="expense-card">
      <h2>Yesterday's Expense</h2>
      <p class="expense-value">₹<?php echo $yesterday_expense; ?></p>
    </div>

    <div class="expense-card">
      <h2>Monthly Expense</h2>
      <p class="expense-value">₹<?php echo $monthly_expense; ?></p>
    </div>

    <div class="expense-card">
      <h2>Yearly Expense</h2>
      <p class="expense-value">₹<?php echo $yearly_expense; ?></p>
    </div>

  </div>

</body>
</html>