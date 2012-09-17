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
        <h2>Register a new user</h2><br/>
            <form action="" method='POST'>
                <table class="Table">
                    <tr>
                        <td> What type of user do wish to register? </td>
                        <td>
                            <select name="user_types">
                                <option value="student">Student</option>
                                <option value="lecturer">Lecturer</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><input type='submit' value='Submit'></td>
                    </tr>
                </table>
            </form>
   
            <?php
            if (isset($_POST['user_types'])){
                $user_type = $_POST['user_types'];
                
                if ($user_type == 'student'){
                    header("location: reg_student.php");        
                } 
                else if ($user_type == 'lecturer'){
                    header("location: reg_lecturer.php");        
                } 
                else if ($user_type == 'admin'){
                    header("location: reg_admin.php");        
                }
            }
            ?>
        
        </article>
    </section>        
        
    <?php include '../assets/template/footer.php'; ?>