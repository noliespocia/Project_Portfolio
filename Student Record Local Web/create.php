<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$Firstname = $Lastname = $Prelim = $Midterm = $Finals = $Final_grade = "";
$Firstname_err = $Lastname_err = $Prelim_err = $Midterm_err = $Finals_err = "";   
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Validate name
    $input_Firstname = trim($_POST["Firstname"]);
    if(empty($input_Firstname))
    {
        $Firstname_err = "Please Enter First Name.";
    } 
    elseif(!filter_var($input_Firstname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Firstname_err = "Please Enter Valid First Name.";
    } 
    else
    {
        $Firstname = $input_Firstname;
    }
	
	// Validate Last name
    $input_Lastname = trim($_POST["Lastname"]);
    if(empty($input_Lastname))
    {
        $Lastname_err = "Please Enter Last Name.";
    } 
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $input_Lastname))
    {
        $Lastname_err = "Please Enter Valid Last Name.";
    } 
    else
    {
        $Lastname = $input_Lastname;
    }
	
	//Validate Prelim Grade
	$input_Prelim = trim($_POST["Prelim"]);
	if (!is_numeric($input_Prelim))
	{
	  $Prelim_err = "Please Enter Valid Prelim Grade.";
	}
	else
	{
	  $Prelim = $input_Prelim;
	}
	
	//Validate Midterm Grade
	$input_Midterm = trim($_POST["Midterm"]);
	if (!is_numeric($input_Midterm))
	{
	  $Midterm_err = "Please Enter Valid Midterm Grade.";
	}
	else
	{
	  $Midterm = $input_Midterm;
	}
	
	//Validate Finals Grade
	$input_Finals = trim($_POST["Finals"]);
	if (!is_numeric($input_Finals))
	{
	  $Finals_err = "Please Enter Valid Finals Grade.";
	}
	else
	{
	  $Finals = $input_Finals;
	}
    
    //Compute Final Grade
    $Final_grade = ($Prelim + $Midterm + $Finals) / 3;


    // Check input errors before inserting in database
    if(empty($Firstname_err) && empty($Lastname_err) && empty($Prelim_err) && empty($Midterm_err) && empty($Finals_err))
        {
        // Prepare an insert statement
        $sql = "INSERT INTO studentrecord_tbl (Firstname, Lastname, Prelim, Midterm, Finals, Final_grade) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql))
            {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssdddd", $Firstname, $Lastname, $Prelim, $Midterm, $Finals, $Final_grade);
            }
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
                {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
             // Close statement
            mysqli_stmt_close($stmt);
        }
      
    // Close connection
    mysqli_close($link);  
}  
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add student record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="Firstname" class="form-control <?php echo (!empty($Firstname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Firstname; ?>">
                            <span class="invalid-feedback"><?php echo $Firstname_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="Lastname" class="form-control <?php echo (!empty($Lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Lastname; ?>">                            
                            <span class="invalid-feedback"><?php echo $Lastname_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Prelim Grade</label>
                            <input type="text" name="Prelim" class="form-control <?php echo (!empty($Prelim_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Prelim; ?>">
                            <span class="invalid-feedback"><?php echo $Prelim_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Midterm Grade</label>
                            <input type="text" name="Midterm" class="form-control <?php echo (!empty($Midterm_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Midterm; ?>">
                            <span class="invalid-feedback"><?php echo $Midterm_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Finals Grade</label>
                            <input type="text" name="Finals" class="form-control <?php echo (!empty($Finals_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Finals; ?>">
                            <span class="invalid-feedback"><?php echo $Finals_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>