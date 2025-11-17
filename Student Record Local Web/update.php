<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$firstname    = $lastname = "";
$prelim       = $midterm = $finals = $final_grade = 0;
$firstname_err = $lastname_err = $prelim_err = $midterm_err = $finals_err = "";

// Processing form data when form is submitted
if (isset($_POST["studid"]) && !empty($_POST["studid"])) 
    {
    // Get hidden input value
    $studid = (int) $_POST["studid"];

    // Validate firstname
    $input_firstname = trim($_POST["firstname"]);
    if (empty($input_firstname)) 
        {
        $firstname_err = "Please enter first name.";
        } 
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $input_firstname)) 
    {
        $firstname_err = "Please enter a valid first name.";
    } 
    else 
    {
        $firstname = $input_firstname;
    }

    // Validate lastname
    $input_lastname = trim($_POST["lastname"]);
    if (empty($input_lastname)) 
    {
        $lastname_err = "Please enter last name.";
    } 
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $input_lastname)) 
    {
        $lastname_err = "Please enter a valid last name.";
    } 
    else 
    {
        $lastname = $input_lastname;
    }

    // Validate prelim
    $input_prelim = trim($_POST["prelim"]);
    if (!is_numeric($input_prelim)) 
        {
            $prelim_err = "Please enter a valid prelim grade.";
        } 
    else
    {
        $prelim = $input_prelim;
    }

    // Validate midterm
    $input_midterm = trim($_POST["midterm"]);
    if (!is_numeric($input_midterm)) {
        $midterm_err = "Please enter a valid midterm grade.";
    } 
    else 
    {
        $midterm = $input_midterm;
    }

    // Validate finals
    $input_finals = trim($_POST["finals"]);
    if (!is_numeric($input_finals)) {
        $finals_err = "Please enter a valid finals grade.";
    } 
    else 
    {
        $finals = $input_finals;
    }

    // Compute final_grade
    if (empty($prelim_err . $midterm_err . $finals_err)) 
        {
            $final_grade = round(($prelim + $midterm + $finals) / 3, 2);
        }

    // Check input errors before updating in database
    if (empty($firstname_err . $lastname_err . $prelim_err . $midterm_err . $finals_err)) 
        {
        // Prepare an update statement
        $sql = "UPDATE studentrecord_tbl SET firstname = ?, lastname = ?, prelim = ?, midterm = ?, finals = ?, final_grade = ? WHERE studid = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt,"ssddddi",$param_firstname,$param_lastname,$param_prelim,$param_midterm,$param_finals,$param_final_grade,$param_studid);

            // Set parameters
            $param_firstname   = $firstname;
            $param_lastname    = $lastname;
            $param_prelim      = $prelim;
            $param_midterm     = $midterm;
            $param_finals      = $finals;
            $param_final_grade = $final_grade;
            $param_studid      = $studid;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong: " . mysqli_stmt_error($stmt);
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of studid parameter before processing further
    if (isset($_GET["studid"]) && !empty(trim($_GET["studid"]))) {
        // Get URL parameter
        $studid = (int) trim($_GET["studid"]);

        // Prepare a select statement
        $sql = "SELECT firstname, lastname, prelim, midterm, finals, final_grade
                FROM studentrecord_tbl
                WHERE studid = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_studid);

            // Set parameter
            $param_studid = $studid;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) === 1) {
                    // Fetch result row as an associative array
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $firstname    = $row["firstname"];
                    $lastname     = $row["lastname"];
                    $prelim       = $row["prelim"];
                    $midterm      = $row["midterm"];
                    $finals       = $row["finals"];
                    $final_grade  = $row["final_grade"];
                } else {
                    // URL doesn't contain valid studid. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain studid parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Student Record</title>
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper { width: 600px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2 class="mt-5">Update Student Record</h2>
        <p>Please edit the input values and submit to update the student record.</p>
        <form action="update.php?studid=<?php echo $studid; ?>" method="post">            
            <div class="form-group">
                <label>First Name</label>
                <input
                  type="text"
                  name="firstname"
                  class="form-control <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>"
                  value="<?php echo htmlspecialchars($firstname); ?>"
                >
                <span class="invalid-feedback"><?php echo $firstname_err; ?></span>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input
                  type="text"
                  name="lastname"
                  class="form-control <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>"
                  value="<?php echo htmlspecialchars($lastname); ?>"
                >
                <span class="invalid-feedback"><?php echo $lastname_err; ?></span>
            </div>
            <div class="form-group">
                <label>Prelim Grade</label>
                <input
                  type="text"
                  name="prelim"
                  class="form-control <?php echo (!empty($prelim_err)) ? 'is-invalid' : ''; ?>"
                  value="<?php echo htmlspecialchars($prelim); ?>"
                >
                <span class="invalid-feedback"><?php echo $prelim_err; ?></span>
            </div>
            <div class="form-group">
                <label>Midterm Grade</label>
                <input
                  type="text"
                  name="midterm"
                  class="form-control <?php echo (!empty($midterm_err)) ? 'is-invalid' : ''; ?>"
                  value="<?php echo htmlspecialchars($midterm); ?>"
                >
                <span class="invalid-feedback"><?php echo $midterm_err; ?></span>
            </div>
            <div class="form-group">
                <label>Finals Grade</label>
                <input
                  type="text"
                  name="finals"
                  class="form-control <?php echo (!empty($finals_err)) ? 'is-invalid' : ''; ?>"
                  value="<?php echo htmlspecialchars($finals); ?>"
                >
                <span class="invalid-feedback"><?php echo $finals_err; ?></span>
            </div>
            <input type="hidden" name="studid" value="<?php echo $studid; ?>"/>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</body>
</html>