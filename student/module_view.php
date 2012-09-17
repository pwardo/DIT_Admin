<?php
include '../assets/db/init.php';
include '../assets/db/class.acl.php';
include '../assets/db/user.func.php';
include '../assets/db/semesters.func.php';

$myACL = new ACL();
if ($myACL->hasPermission('access_student') != true)
{
	header("location: ../index.php");
}

$user_id = $_SESSION['user_id'];
$student_data = get_student_data_userid($user_id);
$student_id = $student_data['student_id'];
$reg_data = get_registration_data($student_id);
$reg_id = $reg_data['id'];

$module_id = $_GET['module_id'];
$module_data = get_module_data($module_id);

$course_data = get_course_data($module_data['Courses_id']);
$course_code = $course_data['code'];
$course_title = $course_data['title'];

$module_lecturer_data = get_module_lecturer($module_id);
$modules_lecturer_id = $module_lecturer_data['Staff_staff_id'];
    
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
        <h2><?php echo $module_data['code'], ': ',$module_data['title']; ?></h2>
        <p>Course: <?php echo '<a href="course_view.php?course_id=', $course_data['id'],'">',$course_code,', ', $course_title,'</a>' ?>
        </p>
        <table class="Table">			
            <form action="" method='POST'>
                <tr> <!-- change last name -->
                    <td><b>Title: </b></td>
                    <td></td>
                    <td>
                        <?php echo $module_data['title']; ?></td>
                    <td>
                </tr>
            </form>        
<!-------------------------------------------------------------------------------------->
    <?php $lecturers = get_lecturers(); ?>

           <form action="" method='POST'>
                <tr> <!-- change last name -->
                    <td><b>Lecturer: </b></td>
                    <td></td>
                    <td>
                                
                    <?php 
                    if(empty($modules_lecturer_id)){
                        echo 'No Lecturer Assigned</option';                     
                    }
                    if(!empty($modules_lecturer_id)){
                        
                        foreach ($lecturers as $lecturer) {
                        $staff_id = $lecturer['staff_id'];
                        
                            if($modules_lecturer_id === $staff_id){
                                echo $lecturer["first_name"],' ',$lecturer["last_name"];
                            }
                        }
                    }
                    ?>
                            
                    <td>
                </tr>
            </form>
            
<!-------------------------------------------------------------------------------------->
                
            <form action="" method='POST'>
                <tr> <!-- change last name -->
                    <td><b>Your enrolment status:</b></td> 
                    <td></td>
                    <td>
                    <?php 
                    if(enrolled($reg_id, $module_id)){
                        echo 'Enrolled!';
                    } else{
                        echo 'Not Enrolled!';
                    }
                    ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Module enrolment status:</b></td> 
                    
                    <?php if(($module_data['open_closed'] == 1) && (enrolled($reg_id, $module_id))){                       
                       echo "
                            <td></td>
                        <td colspan='2'><b>Open</b></td>
                            <tr></tr>
                            <tr>
                            <td><input type='submit' name='unenrol' value='Un-Enrol'></td>
                            </tr>
                            ";
                        
                        }elseif(($module_data['open_closed'] == 1) && (!enrolled($reg_id, $module_id))){
                        echo "
                                <td></td>
                            <td colspan='2'><b>Open</b></td>
                            <tr></tr>
                            <tr>
                            <td><input type='submit' name='enrol' value='Enrol'></td>
                            </tr>
                            ";
                            
                        }
                        
                        elseif($module_data['open_closed'] == 0) {
                        echo "
                            <td></td>

                        <td>Closed</td>
                        ";                        
                        }
                        
                        $submitEnrol = $_POST['enrol'];
                        if ($submitEnrol){
                            $enrolment_data = check_enrolment_count($reg_id);
                            $enrolled_count = $enrolment_data['enrolled_count'];

                            if ($enrolled_count > 3){
                                echo '<td colspan="4"><b>You are already enrolled for 4 modules.</b></td>';
                            }
                            else {

                                mysql_query("
                                    INSERT INTO registrations_has_modules(Registrations_id, Modules_id) 
                                    VALUES('$reg_id', '$module_id')
                                    ") or die(mysql_error());

                                header('Location: module_view.php?module_id='.$module_id);
                            }
                        }
                        
                        $submitUnEnrol = $_POST['unenrol'];
                        if ($submitUnEnrol){
                                mysql_query("
                                    DELETE FROM registrations_has_modules
                                        WHERE Registrations_id = '$reg_id' 
                                        AND Modules_id = '$module_id'
                                    ") or die(mysql_error());

                                header('Location: module_view.php?module_id='.$module_id);
                        }                        
                        ?> 
                </tr>
            </form>
        </table>
        
    </article>
    
    
    <?php 
   
    $classes = get_module_clases($module_id); ?>        
    


    <article id="account">
        <h2>Class Schedule for <?php echo $module_data['code']; ?></h2>            
            <table class="Table">
                <tr>
                    <td><b>Day </b></td>
                    <td><b>Start Time </b></td>
                    <td><b>Finish Time</b></td>
                    <td><b>Room </b></td>
                    <td><b>Room Capacity </b></td>
                </tr>                    

                
                <?php 
                if(empty($classes)){
                    ?>
                <tr>
                    <td colspan="4">No Classed Scheduled.</td>
                </tr>
                    <?php
                }
  
                foreach ($classes as $class){ 
                    $class_id = $class['class_id'];
                    
                    $class_data = get_class_data($class_id);
                    ?>
                <tr>
                    <td><?php echo $class_data['day'] ?></td>
                    <td><?php echo $class_data['startTime'] ?></td>
                    <td><?php echo $class_data['endTime'] ?></td>
                    <td><?php echo $class_data['roomName'] ?></td>
                    <td><?php echo $class_data['roomCapacity'] ?></td>
                </tr>
                        
                <?php
                }             
                 ?>
                <tr>
                    

        </table>
    </article>

</section>

    <?php include '../assets/template/footer.php'; ?>