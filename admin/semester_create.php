<?php
include '../assets/db/init.php';
include '../assets/db/class.acl.php';
include '../assets/db/user.func.php';
include '../assets/db/semesters.func.php';

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
    <article id="account">
        <h2>Create New Semester </h2>
        
        <table class="Table">			
            <form action="" method='POST'>
                <tr> <!-- change last name -->
                    <td><span class="Bold">Start Date: </span></td>
                    <td></td>
                    <td><input type='date' name='createStartDate'></td><td><b>Example: 2001-01-01</b></td>
                </tr>
        
                <tr> <!-- change last name -->
                    <td><span class="Bold">End Date: </span></td>
                    <td></td>
                    <td><input type='date' name='createEndDate'></td><td><b>Example: 2001-01-01</b></td>
                </tr>
 
                <tr> <!-- change last name -->
                    <td><span class='Bold'>Registrations: </span></td>
                     <td></td>
                     <td colspan="3"><span class='Bold'>New Semesters will be open for registration by default. </span>
                </tr>
                <tr></tr>
                <tr>
                    <td colspan="3"><input type='submit' value='Create New Semester'></td>
                </tr>
            </form>
           
        </table>
                <?php
                if (isset($_POST['createStartDate'], $_POST['createEndDate'])){
                    $submitStartDate = $_POST['createStartDate'];
                    $submitEndDate = $_POST['createEndDate'];

                    if(empty($submitStartDate) || empty($submitEndDate)){
                        $errors[] = 'Start and End Dates are required';
                    }
                    elseif(checkdate($submitStartDate)){
                        $errors[] = 'One or both of your dates are not valid';
                    }
                
                    if (!empty($errors)){
                        foreach ($errors as $error){
                            echo '<h3><br/>', $error, '<br/></h3>'; // if there are errors contained in the array, print each one seperated by a line break
                        }
                    }
                    else{
                        $newSemester = create_semester($submitStartDate, $submitEndDate);    

                        $semester_id = $newSemester;
                        header('Location: semester_edit.php?semester_id='.$semester_id);
                        exit;
                    }
                }

                ?>
    </article>
</section>

    <?php include '../assets/template/footer.php'; ?>