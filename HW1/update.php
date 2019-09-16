<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $email = $surname = $id = "";
$name_err = $surname_err = $id_err = $email_err= "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

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
        // Prepare an update statement
        $sql = "UPDATE users SET name=?, surname=?, email=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_surname, $param_email, $param_id);

            // Set parameters
            $param_name = $name;
            $param_surname = $surname;
            $param_email = $email;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM users WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $name = $row["name"];
                    $email = $row["email"];
                    $surname = $row["surname"];
                    $id = $row["id"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2>Update Record</h2>
                </div>
                <p>Please edit the input values and submit to update the record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                        <span class="help-block"><?php echo $name_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($surname_err)) ? 'has-error' : ''; ?>">
                        <label>email</label>
                        <input type="text" name="surname" class="form-control" value="<?php echo $surname; ?>">
                        <span class="help-block"><?php echo $surname_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($id_err)) ? 'has-error' : ''; ?>">
                        <label>id</label>
                        <input type="text" name="id" class="form-control" value="<?php echo $id; ?>">
                        <span class="help-block"><?php echo $id_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                        <label>id</label>
                        <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                        <span class="help-block"><?php echo $email_err;?></span>
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
