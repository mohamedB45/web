<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course'], $_POST['credits'], $_POST['grade'], $_POST['student_name'], $_POST['semester'])) {
    $courses = $_POST['course'];
    $credits = $_POST['credits'];
    $grades  = $_POST['grade'];

    $totalPoints = 0;
    $totalCredits = 0;

    echo "<h2>Results</h2>";
    echo "<table border='1' cellpadding='8'>";
    echo "<tr>
            <th>Course</th>
            <th>Credits</th>
            <th>Grade</th>
            <th>Grade Points</th>
          </tr>";

    for ($i = 0; $i < count($courses); $i++) {
        $course = htmlspecialchars($courses[$i]);
        $cr = floatval($credits[$i]);
        $g  = floatval($grades[$i]);

        if ($cr <= 0) continue;

        $pts = $cr * $g;
        $totalPoints += $pts;
        $totalCredits += $cr;

        echo "<tr>
                <td>$course</td>
                <td>$cr</td>
                <td>$g</td>
                <td>$pts</td>
              </tr>";
    }
    echo "</table>";

    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;
        if ($gpa >= 3.7) {
            $interpretation = "Distinction";
        } elseif ($gpa >= 3.0) {
            $interpretation = "Merit";
        } elseif ($gpa >= 2.0) {
            $interpretation = "Pass";
        } else {
            $interpretation = "Fail";
        }
        echo "<p>Your GPA is <strong>" . number_format($gpa, 2) . "</strong> ($interpretation).</p>";
    } else {
        echo "<p>No valid courses entered.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GPA Calculator with jQuery and Bootstrap</title>
  <a href="history.php" class="btn btn-info mt-3">View GPA History</a>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="script.js" defer></script>
</head>
<body>
  <div class="container">
    <h1 class="mt-5">GPA Calculator</h1>
    <div id="result" class="mt-3"></div>

    <!-- الفورم يحتوي الآن على جميع الحقول -->
    <form id="gpaForm" class="mt-3">
      <div class="form-group">
        <label for="studentName">Student Name</label>
        <input type="text" id="studentName" name="student_name" class="form-control" placeholder="Your Name" required>
      </div>
      <div class="form-group">
        <label for="semester">Semester</label>
        <input type="text" id="semester" name="semester" class="form-control" placeholder="Number of Semester" required>
      </div>

      <div id="courses">
        <div class="form-row mb-2 course-row">
          <div class="col">
            <input type="text" name="course[]" class="form-control" placeholder="Course name" required>
          </div>
          <div class="col-2">
            <input type="number" name="credits[]" class="form-control" placeholder="Credits" min="1" required>
          </div>
          <div class="col-2">
            <select name="grade[]" class="form-control">
              <option value="4.0">A</option>
              <option value="3.0">B</option>
              <option value="2.0">C</option>
              <option value="1.0">D</option>
              <option value="0.0">F</option>
            </select>
          </div>
        </div>
      </div>

      <button type="button" id="addCourse" class="btn btn-secondary mb-3">+ Add Course</button><br>
      <button type="submit" class="btn btn-primary">Calculate GPA</button>
    </form>

    <div class="progress mt-3">
      <div id="gpaBar" class="progress-bar" role="progressbar" style="width:0%">0.0</div>
    </div>
  </div>
</body>
</html>