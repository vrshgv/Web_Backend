<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $surname = $email = $id = "";
$name_err = $surname_err = $id_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }

    $input_surname = trim($_POST["surname"]);
    if(empty($input_surname)){
        $surname_err = "Please enter a surname.";
    } else{
        $surname = $input_surname;
    }

    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $address2_err = "Please enter an email.";
    } else{
        $email = $input_email;
    }

    $input_id = trim($_POST["id"]);
    if(empty($input_id)){
        $id_err = "Please enter the id.";
    } elseif(!ctype_digit($input_id)){
        $id_err = "Please enter a positive integer value.";
    } else{
        $id = (int)$input_id;
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($surname_err) && empty($id_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO users (id, name, surname, email) VALUES (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isss", $param_id,$param_name, $param_surname, $param_email);

            // Set parameters
            $param_name = $name;
            $param_surname= $surname;
            $param_email = $email;
            $param_id=$id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Create Record</h2>
                </div>
                <p>Please fill this form and submit to add employee record to the database.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                        <span class="help-block"><?php echo $name_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($surname_err)) ? 'has-error' : ''; ?>">
                        <label>Surname</label>
                        <input type="text" name="surname" class="form-control" value="<?php echo $surname; ?>">
                        <span class="help-block"><?php echo $surname_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($id_err)) ? 'has-error' : ''; ?>">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                        <span class="help-block"><?php echo $address2_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($id_err)) ? 'has-error' : ''; ?>">
                        <label>ID</label>
                        <input type="text" name="id" class="form-control" value="<?php echo $id; ?>">
                        <span class="help-block"><?php echo $id_err;?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>