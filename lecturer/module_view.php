<?php
include '../assets/db/init.php';
include '../assets/db/class.acl.php';
include '../assets/db/user.func.php';
include '../assets/db/semesters.func.php';

$myACL = new ACL();
if ($myACL->hasPermission('access_lecturer') != true)
{
	header("location: ../index.php");
}

$user_id = $_SESSION['user_id'];
$staff_data = get_staff_data_by_user_id($user_id);
$staff_id = $staff_data['staff_id'];

$module_id = $_GET['module_id'];
$module_data = get_module_data($module_id);

$course_data = get_course_data($module_data['Courses_id']);
$course_code = $course_data['code'];
$course_title = $course_data['title'];
    
?>

<!DOCTYPE HTML>
<head>
      <title>DIT Admin - Module</title>
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
        <p>Course: <?php echo $course_code,', ',$course_title; ?>
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
            
            <form action="" method='POST'>

                <tr>
                    <td><b>Module enrolment status:</b></td> 
                    
                    <?php if($module_data['open_closed'] == 1){                       
                       echo "
                            <td></td>
                        <td colspan='2'><b>Open</b></td>
  
                            ";
                        
                        }elseif($module_data['open_closed'] == 0) {
                        echo "
                            <td></td>

                        <td>Closed</td>
                        ";                        
                        }
                    ?>
                </tr>
            </form>            
        </table>
    </article>
    
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
    <?php $students = get_enrolment_data($module_id);
        
    ?>        
    
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
                             ?>
                        </td>
                        <td>
                            <?php if($module_data['open_closed'] == 1) {
                             echo '<a href="student_grade.php?registrations_id=', $student['Registrations_id'],',',$student['Student_id'],',',$module_id,'">Edit Grade</a>';
                         }?>
                        </td>
                    </tr>
                    <?php
                    }             
                ?>
                
        </table>
    </article>
</section>

    <?php include '../assets/template/footer.php'; ?>