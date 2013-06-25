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

$module_id = $_GET['module_id'];
$module_data = get_module_data($module_id);

$course_data = get_course_data($module_data['Courses_id']);
$course_code = $course_data['code'];
$course_title = $course_data['title'];

$module_lecturer_data = get_module_lecturer($module_id);
$modules_lecturer_id = $module_lecturer_data['Staff_staff_id'];

    $changeTo = $_POST['changeTo']; // If edit button is pressed and new value is entered.
    
    $submitStartDate = $_POST['updateStartDate'];
    if ($submitStartDate && $changeTo){       
	mysql_query("UPDATE Modules SET Modules.start_date = '$changeTo' WHERE Modules.id = ".$module_id) or die("display_db_query:" . mysql_error());
        header('Location: module_edit.php?module_id='.$module_id);
    }
    
    $submitEndDate = $_POST['updateEndDate'];
    if ($submitEndDate && $changeTo){
        $changeTo = mysql_real_escape_string($changeTo);
	mysql_query("UPDATE Modules SET Modules.end_date = '$changeTo' WHERE id = ".$module_id) or die("display_db_query:" . mysql_error());
        header('Location: module_edit.php?module_id='.$module_id);
    }

    $submitOpenCloseReg = $_POST['openCloseReg'];
    $radio_selected = $_POST['radio'];
    if ($submitOpenCloseReg){
        if ($radio_selected == 'Closed'){
            mysql_query("UPDATE Modules SET Modules.open_closed = 0 WHERE id = ".$module_id) or die("display_db_query:" . mysql_error());
        } else if($radio_selected == 'Open'){
            mysql_query("UPDATE Modules SET Modules.open_closed = 1 WHERE id = ".$module_id) or die("display_db_query:" . mysql_error());
        }
         
        header('Location: module_edit.php?module_id='.$module_id);
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
        <h2>Edit module <?php echo $module_data['code']; ?></h2>
        <p>Course: <?php echo '<a href="course_edit.php?course_id=', $course_data['id'],'">',$course_code,', ', $course_title,'</a>' ?>
        </p>
        <table class="Table">			
            <form action="" method='POST'>
                <tr> <!-- change last name -->
                    <td><span class="Bold">Title: </span></td>
                    <td></td>
                    <td><span class="Bold"> Change to: </span></td>
                    <td>
                        <input type='date' name='changeTo' value='<?php echo $module_data['title']; ?>'></td>
                    <td>
                        <input type='submit' name='updateStartDate' value='Update'></td>
                </tr>
            </form>        
<!-------------------------------------------------------------------------------------->
    <?php $lecturers = get_lecturers(); ?>

           <form action="" method='POST'>
                <tr> <!-- change last name -->
                    <td><span class="Bold">Lecturer: </span></td>
                    <td></td>
                    <td><span class="Bold"> Change to: </span></td>
                    <td>
                        <select name="lecturer">                   
                                
                    <?php 
                    if(empty($modules_lecturer_id)){
                        echo '<option value="empty" selected>No Lecturer Assigned</option>';
                        
                        foreach ($lecturers as $lecturer) {
                        $staff_id = $lecturer['staff_id'];
                        echo '<option value="',$staff_id,'">',$lecturer["first_name"],' ',$lecturer["last_name"],'</option>
                            ';                        
                        }
                    }
                    if(!empty($modules_lecturer_id)){
                        
                        foreach ($lecturers as $lecturer) {
                        $staff_id = $lecturer['staff_id'];
                        
                            if($modules_lecturer_id === $staff_id){
                                echo '<option value="',$staff_id,'" selected="selected">',$lecturer["first_name"],' ',$lecturer["last_name"],'</option>';
                            } else{
                                echo '<option value="',$staff_id,'">',$lecturer["first_name"],' ',$lecturer["last_name"],'</option>
                                     ';
                            }
                        }
                        
                        echo '<option value="unassignLecturer">Un-Assign Lecturer</option>';
                    }
                                        
                    ?>
                        </select>
                            
                            
                    <td>
                        <input type='submit' name='updateLecturer' value='Update'>
                    </td>
                    <?php
                    
                    
                    $submitLecturer = $_POST['updateLecturer'];
                    $lecturer_selected = $_POST['lecturer'];
                    if ($submitLecturer){
                       
                        if ($lecturer_selected == 'unassignLecturer') {
                            mysql_query("DELETE FROM Modules_has_Staff WHERE Modules_id = ".$module_id) or die("display_db_query:" . mysql_error());
                            header('Location: module_edit.php?module_id='.$module_id);                      
                        } 
                        elseif (!($lecturer_selected === $modules_lecturer_id) && (!empty($modules_lecturer_id))){
                            $teachingAssignment_data = check_lecturer_module_count($lecturer_selected);
                            $assignment_count = $teachingAssignment_data['module_count'];

                            if ($assignment_count > 2){
                                $l_data = get_staff_data($lecturer_selected);
                                $first_name = $l_data['first_name'];
                                $last_name = $l_data['last_name'];
                                echo '<tr><td colspan="5"><b>', $first_name,' ',$last_name,'</b> is already assigned to teach 3 modules.</td></tr>';
                            } else{
                                mysql_query("UPDATE Modules_has_Staff SET Staff_staff_id = ".$lecturer_selected." WHERE Modules_id = ".$module_id) or die("display_db_query:" . mysql_error());
                                header('Location: module_edit.php?module_id='.$module_id);                                
                            }
                        }                        
                        elseif(empty($modules_lecturer_id)){
                            $teachingAssignment_data = check_lecturer_module_count($lecturer_selected);
                            $assignment_count = $teachingAssignment_data['module_count'];

                            if ($assignment_count > 2){
                                $l_data = get_staff_data($lecturer_selected);
                                $first_name = $l_data['first_name'];
                                $last_name = $l_data['last_name'];
                                echo '<tr><td colspan="5"><b>', $first_name,' ',$last_name,'</b> is already assigned to teach 3 modules.</td></tr>';
                            } else{
                                mysql_query("INSERT INTO Modules_has_Staff VALUES('$module_id', '$lecturer_selected')") or die("display_db_query:" . mysql_error());
                                header('Location: module_edit.php?module_id='.$module_id);                                
                            }                            
                        }
                    }  
                    ?>
                    
                </tr>
            </form>
            
<!-------------------------------------------------------------------------------------->
                
            <form action="" method='POST'>
                <tr> <!-- change last name -->
                    <td><span class='Bold'>Enrolment: </span></td>
                
                   <?php if($module_data['open_closed'] == 0){
                        echo "                    
                            <td></td>
                        <td><span class='Bold'>Open: </span>
                        <input type='radio' name='radio' value='Open'></td>

                        <td><span class='Bold'>Closed: </span>
                        
                        <input type='radio' name='radio' value='Closed' checked></td>
                        <td><input type='submit' name='openCloseReg' value='Update'></td>";
                        
                        } 
                        elseif ($module_data['open_closed'] == 1) {
                        echo "                    
                            <td></td>
                        <td><span class='Bold'>Open: </span>
                        <input type='radio' name='radio' value='Open' checked></td>

                        <td><span class='Bold'>Closed: </span>
                        <input type='radio' name='radio' value='Closed'></td>
                        <td><input type='submit' name='openCloseReg' value='Update'></td>";                    
                        
                        }
                        ?> 
                </tr>
            </form>
        </table>
        
    </article>
    
    <!------------------------------------------------------------------------->
    <!---------------------------------------------------------------------------------------------------->
    
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

                <?php foreach ($classes as $class){ 
                        
                    $class_id = $class['class_id'];
                    
                    if(!empty($class_id)){
                    $class_data = get_class_data($class_id);
                    ?>                    

                        <tr>
                            <td><?php echo $class_data['day'] ?></td>
                            <td><?php echo $class_data['startTime'] ?></td>
                            <td><?php echo $class_data['endTime'] ?></td>
                            <td><?php echo $class_data['roomName'] ?></td>
                            <td><?php echo $class_data['roomCapacity'] ?></td>
                            <td><?php echo '<a href="class_edit.php?class_id=', $class_id,'">Edit</a>' ?></td>
                        </tr>
                        
                <?php
                        }
                    }             
                ?>
                   <tr>
                        <td><?php echo '<a href="class_create.php?module_id=', $module_id,'">Schedule a new class</a>' ?></td>
                   </tr>                  
        </table>
    </article>
    
    
    <?php $students = get_enrolment_data($module_id); ?>        
    
    <article id="account">
        <h2>Students enrolled for <?php echo $module_data['code']; ?></h2>            
            <table class="Table">
                <tr>
                    <td><b>Student Number </b></td>
                    <td><b>Name </b></td>
                    <td></td>
                    <td><b>Grade</b></td>
                </tr>                    

                <?php foreach ($students as $student){
                    
                    ?>
                
                    <tr>
                        <td><?php echo $student['Student_id']; ?></td>
                        <td><?php echo $student['first_name'],' ',$student['last_name'];  ?></td>
                        <td></td>
                        <td>
                            <?php                           
                            if (empty($student['grade'])){
                                echo 'No Grade Assigned';
                            } else{
                                echo '<b>',$student['grade'],'</b>';
                            }  
                        ?></td>
                        <td><?php echo '<a href="student_grades.php?registrations_id=', $student['Registrations_id'],',',$student['Student_id'],'">View All Grades</a>' ?></td>
                    </tr>
                    <?php
                    }             
                ?>
                
        </table>
    </article>
</section>

    <?php include '../assets/template/footer.php'; ?>