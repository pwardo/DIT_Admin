<?php
include '../assets/db/init.php';
include '../assets/db/class.acl.php';
include '../assets/db/user.func.php';
include '../assets/db/semesters.func.php';

$myACL = new ACL();
if ($myACL->hasPermission('access_lecturer') != true)
{
	header("location: ../index.php");
} else {



$registration = explode(",", $_GET['registrations_id']);

$registration_id = $registration['0'];
$student_id = $registration['1'];
$module_id = $registration['2'];

$student_data = get_student_data($student_id);
$module_data = get_module_data($module_id);

?>

<!DOCTYPE HTML>
<head>
      <title>DIT Admin - Grades</title>
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
       <?php $grades = get_student_grades($registration_id);  ?>
            
            
        <h2>Grades for <?php echo $student_data['first_name'], ' ',$student_data['last_name'] ; ?></h2>            
        <p>Student ID: <?php echo $student_data['student_id']; ?></p>            
        <p>Module: <?php echo '<a href="module_view.php?module_id=', $module_data['id'],'">',$module_data['code'],', ',$module_data['title'],'</a>'; ?></p>            
        
        <table class="Table">			
                <form action="" method='POST'>
                      <tr> <!-- change last name -->
                        <td><span class="Bold">Grade: </span></td>
                        <td></td>
                        <td><input type='text' name='assignGrade'></td>
                    </tr>
                    <tr>
                        <td colspan="3"><input type='submit' value='Assign Grade'></td>
                    </tr>                
                </form>

            </table>         
                <?php
                if (isset($_POST['assignGrade'])){
                    $submitGrade = $_POST['assignGrade'];
                    $submitGrade = (int)$submitGrade;

                    if(empty($submitGrade)){
                        $errors[] = 'Grade must be an integer value between 0 and 100 inlusive.';
                    }
                    if(($submitGrade > 100) || ($submitGrade < 0)){
                        $errors[] = 'Grade must be an integer value between 0 and 100 inlusive.';
                    }
                    if (!empty($errors)){
                        foreach ($errors as $error){
                            echo '<h3><br/>', $error, '<br/></h3>'; // if there are errors contained in the array, print each one seperated by a line break
                        }
                    }
                    else{
                        mysql_query("UPDATE Registrations_has_Modules 
                            SET grade = '$submitGrade'
                                WHERE Registrations_id = '$registration_id'
                                AND Modules_id = '$module_id'
                            ") or die("display_db_query:" . mysql_error());
                        header("Location: student_grade.php?registrations_id=".$registration_id.",".$student_id.",".$module_id);
                        exit;
                    }
                }

                ?>
        </article>
    </section>
    

<?php
}
    include '../assets/template/footer.php'; ?>