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
                    <li><a href="index.php">Back to Admin Screen</a></li>
                    <li><a href="../logout.php">Logout </a></li>
                </ul>
            </nav>
	</section>
    </header>
    
    <section id="main_section">
        <article>
        <h2>Register a new Lecturer</h2>
            <form action="" method='POST'>
                <table class="Table">
                    <tr><td colspan="2"><h3>System Login Details</h3></td></tr>
                    <tr>
                        <td><span class="Bold">Staff ID:</span></td>
                        <td><input type='text' name='staffID'></td>
                        <td>(8 digit Staff number i.e. 12345678)</td>
                    </tr>
                    <tr>
                        <td><span class="Bold">Password:</span></td>
                        <td><input type='password' name='password'></td>
                        <td>(between 6 and 30 characters)</td>
                    </tr>                    
                    <tr>
                        <td><span class="Bold">Repeat Password:</span></td>
                        <td><input type='password' name='repeatPassword'></td>
                        <td>(password and repeat password must match)</td>
                    </tr>
                    
                    <tr><td colspan="2"><h3>Basic User Details</h3></td></tr>
                    <tr>
                        <td><span class="Bold">First Name:</span></td>
                        <td><input type='text' name='firstName'></td>
                    </tr>
                    <tr>
                        <td><span class="Bold">Last Name:</span></td>
                        <td><input type='text' name='lastName'></td>
                    </tr>
                    
                    <tr><td colspan="2"><h3>Contact Details</h3></td></tr>
                    <tr>
                        <td><span class="Bold">Email:</span></td>
                        <td><input type='email' name='email'></td>
                    </tr> 
                    <tr>
                        <td><span class="Bold">Mobile:</span></td>
                        <td><input type='text' name='mobile'></td>
                    </tr>
                  
                    <tr><td colspan="2"><h3>Home Address</h3></td></tr>
                    <tr>
                        <td><span class="Bold">Street:</span></td>
                        <td><input type='text' name='address_line1'></td>
                    </tr>  
                    <tr>
                        <td><span class="Bold">Town:</span></td>
                        <td><input type='text' name='address_line2'></td>
                    </tr>
                    <tr>
                        <td><span class="Bold">County:</span></td>
                        <td><input type='text' name='address_line3'></td>
                    </tr> 
                    <tr>
                        <td><span class="Bold">Optional Address Line 4:</span></td>
                        <td><input type='text' name='address_line4'></td>
                    </tr>                     
                    <tr>
                        <td><input type='submit' value='Register'></td>
                        <td></td>
                    </tr>
                </table>
            </form>

    <?php

    if (isset($_POST['staffID'], $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'], $_POST['repeatPassword'], $_POST['address_line1'], $_POST['address_line2'], $_POST['address_line3'], $_POST['address_line4'])){
        $staffID = strip_tags($_POST['staffID']);
        echo $staffID;
        $firstName = strip_tags($_POST['firstName']);
        $lastName = strip_tags($_POST['lastName']);
        $email = strip_tags($_POST['email']);
        $password = strip_tags($_POST['password']);
        $repeatPassword = strip_tags($_POST['repeatPassword']);

        $mobile = strip_tags($_POST['mobile']);
        $addressL1 = strip_tags($_POST['address_line1']);
        $addressL2 = strip_tags($_POST['address_line2']);
        $addressL3 = strip_tags($_POST['address_line3']);
        $addressL4 = strip_tags($_POST['address_line4']);

        $errors = array();

        // if any fields are empty
        if(empty($staffID) || empty($firstName) || empty($lastName) || empty($email) || empty ($password) || empty ($repeatPassword)){
            $errors[] = 'All fields required';
        }
        else{
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false){
                $errors[] = 'Email address not valid';
            }
            if (strlen($staffID) > 8 || strlen($staffID) < 8){
                $errors[] = 'Staff ID must be 8 digits';
            }   

            if (strlen($firstName) > 35){
                $errors[] = 'First name is to long';
            }    

            if (strlen($lastName) > 35){
                $errors[] = 'Last name is to long';
            }    

            if (strlen($email) > 255){
                $errors[] = 'Email address is to long';
            }
            if (strlen($password) > 25 || strlen($password) < 6){
                $errors[] = 'Password must be between <b>6</b> and <b>25</b> characters long';
            }

            // user_exists is from user.func.php
            if (staff_exists($staffID)){
                $errors[] = 'A user with staff ID: <b>'.$staffID.'</b> has already been registered';
            }

            if ($password != $repeatPassword){
                $errors[] = 'Your passwords do not match';
            }
        }

        if (!empty($errors)){
            foreach ($errors as $error){
                echo '<h3><br/>', $error, '<br/></h3>'; // if there are errors contained in the array, print each one seperated by a line break
            }
        }else{
            // Register User
            echo "Register";
            staff_register($staffID, $firstName, $lastName, $email, $password, $mobile, $addressL1, $addressL2, $addressL3, $addressL4);                   
                    
            header("Location: reg_lecturer2.php?staff_id=$staffID");
                
            exit;
        }
    }
    ?>
        </article>
    </section>

    <?php include '../assets/template/footer.php'; ?>