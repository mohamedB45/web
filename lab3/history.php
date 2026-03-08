<?php
$pdo = new PDO("mysql:host=localhost;dbname=gpa_db;charset=utf8mb4", "root", "");
$stmt = $pdo->query("
    SELECT student_name, semester, gpa, MAX(timestamp) as timestamp
    FROM calculations
    GROUP BY student_name, semester, gpa
    ORDER BY timestamp DESC
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GPA History</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
  <h2>Previous GPA Calculations</h2>
  <table class="table table-bordered">
    <thead class="thead-dark">
      <tr>
        <th>Student Name</th>
        <th>Semester</th>
        <th>GPA</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['student_name']) ?></td>
          <td><?= htmlspecialchars($row['semester']) ?></td>
          <td><?= number_format($row['gpa'], 2) ?></td>
          <td><?= $row['timestamp'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>

