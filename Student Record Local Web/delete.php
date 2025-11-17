<?php
// Include config file
require_once "config.php";

// Initialize the studentId variable
$studid = "";

// If this page was requested via POST (i.e. form submission)…
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST["studid"]) && ctype_digit($_POST["studid"])) {
        // Sanitize and cast
        $studid = (int) $_POST["studid"];

        // Prepare delete statement
        $sql = "DELETE FROM studentrecord_tbl WHERE studid = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $studid);

            if (mysqli_stmt_execute($stmt)) {
                // Success: redirect
                mysqli_stmt_close($stmt);
                mysqli_close($link);
                header("Location: index.php");
                exit;
            } else {
                echo "<p class=\"alert alert-danger\">Oops! Something went wrong. Please try again later.</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p class=\"alert alert-danger\">Database error: could not prepare statement.</p>";
        }

        mysqli_close($link);
    } else {
        // Invalid POST data
        header("Location: error.php");
        exit;
    }

} else {
    // This is a GET request: make sure we have a valid ?StudentId=… parameter
    if (isset($_GET["studid"]) && ctype_digit($_GET["studid"])) {
        $studid = (int) $_GET["studid"];
    } else {
        header("Location: error.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete Record</title>
  <link
    rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
  >
  <style>
    .wrapper 
    { 
        max-width: 600px; 
        margin: 50px auto; 
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <h2 class="mt-5 mb-3">Delete Record</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="alert alert-danger">
        <input
          type="hidden"
          name="studid"
          value="<?php echo htmlspecialchars($studid, ENT_QUOTES); ?>"
        />
        <p>Are you sure you want to delete this student record?</p>
        <p>
          <button type="submit" class="btn btn-danger">Yes, delete</button>
          <a href="index.php" class="btn btn-secondary">No, go back</a>
        </p>
      </div>
    </form>
  </div>
</body>
</html>