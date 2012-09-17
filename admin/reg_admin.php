<?php
include '../assets/db/init.php';
include '../assets/db/class.acl.php';
include '../assets/db/user.func.php';

$myACL = new ACL();
if ($myACL->hasPermission('access_admin') != true)
{
	header("location: ../index.php");
}
?>

<!DOCTYPE HTML>
<head>
      <title>DIT Admin</title>
      <link href="../assets/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>

<div id="main_wrapper">
    <header>
	<section id="top_header_left">
            <h1><a href="../index.php">DIT Admin</a></h1>
            <h2></h2>		
	</section>
		
	<section id="top_header_right">
            <nav id="top_menu">
                <h1>Navigation</h1>
                <ul>
                    <li><a href="register.php">Register User</a></li>
                    <li><a href="roles.php">Manage Roles</a></li>
                    <li><a href="../logout.php">Logout </a></li>
                </ul>
            </nav>
	</section>
    </header>
    
    <section id="main_section">
        <article>
        <h2>Register a new administrator</h2>

            <form action="" method='POST'>
                <table class="Table">
                    <tr>
                        <td><span class="Bold">Student ID:</span></td>
                        <td><input type='text' name='register_studentID' value='<?php echo $studentID; ?>'></td>
                        <td>(9 character student number i.e. d12345678)</td>
                    </tr>              
                    <tr>
                        <td><span class="Bold">First Name:</span></td>
                        <td><input type='text' name='register_firstName' value='<?php echo $firstName; ?>'></td>
                    </tr>
                    <tr>
                        <td><span class="Bold">Last Name:</span></td>
                        <td><input type='text' name='register_lastName' value='<?php echo $lastName; ?>'></td>
                    </tr>
                    <tr>
                        <td><span class="Bold">Email:</span></td>
                        <td><input type='email' name='register_email' value='<?php echo $email; ?>'></td>
                    </tr>
                    <tr>
                        <td><span class="Bold">Course ID:</span></td>
                        <td><input type='text' name='register_courseID' value='<?php echo $courseID; ?>'></td>
                        <td>(5 character course code, i.e. DT249)</td>
                    </tr>						
                    <tr>
                        <td><span class="Bold">Password:</span></td>
                        <td><input type='password' name='register_password'></td>
                        <td>(between 6 and 30 characters)</td>
                    </tr>
                    <tr>
                        <td><span class="Bold">Repeat Password:</span></td>
                        <td><input type='password' name='register_repeatPassword'></td>
                        <td>(password and repeat password must match)</td>
                    </tr>
                    <tr>
                        <td><input type='submit' value='Register'></td>
                        <td></td>
                    </tr>
                </table>			
            </form>

    <?php

    if (isset($_POST['register_studentID'], $_POST['register_firstName'], $_POST['register_lastName'], $_POST['register_email'], $_POST['register_password'], $_POST['register_repeatPassword'], $_POST['register_courseID'])){
        $register_studentID = strip_tags($_POST['register_studentID']);
        $register_firstName = strip_tags($_POST['register_firstName']);
        $register_lastName = strip_tags($_POST['register_lastName']);
        $register_email = strip_tags($_POST['register_email']);
        $register_password = strip_tags($_POST['register_password']);
        $register_repeatPassword = strip_tags($_POST['register_repeatPassword']);
        $register_courseID = strip_tags($_POST['register_courseID']);

        $errors = array();

        // if any fields are empty
        if(empty($register_studentID) || empty($register_firstName) || empty($register_lastName) || empty($register_email) || empty ($register_password) || empty ($register_repeatPassword) || empty ($register_courseID)){
            $errors[] = 'All fields required';
        }
        else{
            if (filter_var($register_email, FILTER_VALIDATE_EMAIL) === false){
                $errors[] = 'Email address not valid';
            }
            if (strlen($register_studentID) > 9 || strlen($register_studentID) < 9){
                $errors[] = 'Student ID must be 9 characters long';
            }   

            if (strlen($register_firstName) > 35){
                $errors[] = 'First name address is to long';
            }    

            if (strlen($register_lastName) > 35){
                $errors[] = 'Last name address is to long';
            }    

            if (strlen($register_email) > 255){
                $errors[] = 'Email address is to long';
            }


            if (strlen($register_password) > 25 || strlen($register_password) < 6){
                $errors[] = 'Password must be between <b>6</b> and <b>25</b> characters long';
            }

            // user_exists is from user.func.php
            if (user_exists($register_studentID)){
                $errors[] = 'A student with ID: <b>'.$register_studentID.'</b> has already been registered';
            }

            if ($register_password != $register_repeatPassword){
                $errors[] = 'Your passwords do not match';
            }

        }

        if (!empty($errors)){
            foreach ($errors as $error){
                echo '<h3><br/>', $error, '<br/></h3>'; // if there are errors contained in the array, print each one seperated by a line break
            }
        } else{
            // Register User
            $register = user_register($register_studentID, $register_firstName, $register_lastName, $register_email, $register_password, $register_courseID);


            // Automatically log user in and re-direct to index
            $_SESSION['user_id'] = $register;
            //echo $_SESSION['user_id'];
            header('Location: index.php');
            exit;
        }
    }
    ?>
        </article>
    </section>

    <?php include '../assets/template/footer.php'; ?>