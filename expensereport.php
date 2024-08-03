<?php
session_start();
require_once 'includes/dbh.inc.php';
$username = $_SESSION["username"];
$query = "SELECT DATE(date) AS expense_date, name, price, date FROM expenses WHERE username = :username ORDER BY date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(array(':username' => $username));
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expense Tracker</title>
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
      position: fixed;
      top: 0;
      left: 0;
      width: 200px;
      height: 100%;
      background-color: #4d62bd;
      color: #fff;
      padding: 20px;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .sidebar li {
      margin-bottom: 10px;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
    }
    .container {
    margin-left: 220px;
    padding: 20px;
}

.content {
    background-color: #d5d5da;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin:55px;
    min-width:1100px;
    
}


    h1 {
      text-align: center;
      color: #1c1c2a;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #8c949c;
    }

    th {
      background-color: #4d62bd;
      color: #fff;
    }
    tr.group-separator td {
        border-top: 2px solid #4d62bd;
        border-bottom: 3px solid #000;
    }
  </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
  <div class="content">
    <h1>Expense Statistics</h1>
    <button id="generateChart">Generate Monthly Expense Chart</button>
    <canvas id="expenseChart" width="300" height="300" style="display: none;"></canvas>
    <table>
      <thead>
        <tr>
          <th>Statistic</th>
          <th>Value</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $monthsExpenses = []; 
        $itemExpenses = []; 
        foreach ($expenses as $expense) {
          $month = date('F Y', strtotime($expense['expense_date']));
          if (!isset($monthsExpenses[$month])) {
            $monthsExpenses[$month] = 0;
          }
          $monthsExpenses[$month] += $expense['price'];

          $itemName = $expense['name'];
          if (!isset($itemExpenses[$itemName])) {
            $itemExpenses[$itemName] = 0;
          }
          $itemExpenses[$itemName] += $expense['price'];
        }


        $maxMonth = empty($monthsExpenses) ? "N/A" : array_keys($monthsExpenses, max($monthsExpenses))[0];
        $minMonth = empty($monthsExpenses) ? "N/A" : array_keys($monthsExpenses, min($monthsExpenses))[0];
        $maxItem = empty($itemExpenses) ? "N/A" : array_keys($itemExpenses, max($itemExpenses))[0];



        echo "<tr><td>Month with Most Expense</td><td>$maxMonth</td></tr>";
        echo "<tr><td>Month with Least Expense</td><td>$minMonth</td></tr>";
        echo "<tr><td>Item with Most Expense</td><td>$maxItem</td></tr>";
        ?>
      </tbody>
    </table>

    <h1>Expense Report</h1>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Expense Name</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $currentDate = null;
        $totalAmount = 0;

        foreach ($expenses as $expense) {
         
          if ($expense['expense_date'] !== $currentDate) {
            if ($currentDate !== null) {
              echo "<tr class='group-separator'><td></td><td></td><td><strong>Total: ₹$totalAmount</strong></td></tr>";
              $totalAmount = 0; 
            }
            echo "<tr><td colspan='3'><strong>" . date('F j, Y', strtotime($expense['expense_date'])) . "</strong></td></tr>";
            $currentDate = $expense['expense_date'];
          }

          echo "<tr>";
          echo "<td>" . date('Y-m-d', strtotime($expense['date'])) . "</td>";
          echo "<td>" . $expense['name'] . "</td>";
          echo "<td>₹" . number_format($expense['price'], 2) . "</td>";
          echo "</tr>";

          $totalAmount += $expense['price'];
        }

        if ($currentDate !== null) {
          echo "<tr class='group-separator'><td></td><td></td><td><strong>Total: ₹$totalAmount</strong></td></tr>";
        }
        ?>
      </tbody>
    </table>
    <script>
document.getElementById('generateChart').addEventListener('click', function() {

  var ctx = document.getElementById('expenseChart').getContext('2d');
  var labels = <?php echo json_encode(array_keys($monthsExpenses)); ?>;
  var data = <?php echo json_encode(array_values($monthsExpenses)); ?>;

  var chart = new Chart(ctx, {
    type: 'bar', 
    data: {
      labels: labels,
      datasets: [{
        label: 'Monthly Expenses',
        data: data,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        xAxes: [{
          ticks: {
            beginAtZero: true
          }
        }]
      },
      responsive: false,
      maintainAspectRatio: false,
    }
  });


  document.getElementById('expenseChart').style.display = 'block';
  document.getElementById('expenseChart').style.width = '500px';
  document.getElementById('expenseChart').style.height = '250px';
});
</script>

  </div>
</div>

</body>
</html>
