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

$semester_id = $_GET['semester_id'];
$semester_data = get_semester_data($semester_id);
    
    $changeTo = $_POST['changeTo']; // If edit button is pressed and new value is entered.
    
    $submitStartDate = $_POST['updateStartDate'];
    if ($submitStartDate && $changeTo){       
	mysql_query("UPDATE Semesters SET Semesters.start_date = '$changeTo' WHERE Semesters.id = ".$semester_id) or die("display_db_query:" . mysql_error());
        header('Location: semester_edit.php?semester_id='.$semester_id);
    }

    $submitEndDate = $_POST['updateEndDate'];
    if ($submitEndDate && $changeTo){
	mysql_query("UPDATE Semesters SET Semesters.end_date = '$changeTo' WHERE Semesters.id = ".$semester_id) or die("display_db_query:" . mysql_error());
        header('Location: semester_edit.php?semester_id='.$semester_id);
    }

    $submitOpenCloseReg = $_POST['openCloseReg'];
    $radio_selected = $_POST['radio'];
    if ($submitOpenCloseReg){
        if ($radio_selected == 'Closed'){
            mysql_query("UPDATE Semesters SET Semesters.value = 0 WHERE id = ".$semester_id) or die("display_db_query:" . mysql_error());
        } else if($radio_selected == 'Open'){
            mysql_query("UPDATE Semesters SET Semesters.value = 1 WHERE id = ".$semester_id) or die("display_db_query:" . mysql_error());
        }
         
        header('Location: semester_edit.php?semester_id='.$semester_id);
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
        <h2>Edit Semester <?php echo $semester_id; ?></h2>
        
        <table class="Table">			
            <form action="" method='POST'>
                <tr> <!-- change last name -->
                    <td><span class="Bold">Start Date: </span></td>
                    <td></td>
                    <td><span class="Bold"> Change to: </span></td>
                    <td>
                        <input type='date' name='changeTo' value='<?php echo $semester_data['start_date']; ?>'></td>
                    <td>
                        <input type='submit' name='updateStartDate' value='Update'></td>
                </tr>
            </form>        
        
            <form action="" method='POST'>
                <tr> <!-- change last name -->
                    <td><span class="Bold">End Date: </span></td>
                    <td></td>
                    <td><span class="Bold"> Change to: </span></td>
                    <td><input type='date' name='changeTo' value='<?php echo $semester_data['end_date']; ?>'></td>
                    <td><input type='submit' name='updateEndDate' value='Update'></td>
                </tr>
            </form> 

            <form action="" method='POST'>
                <tr> <!-- change last name -->
                    <td><span class='Bold'>Registrations: </span></td>
                
                   <?php if($semester_data['value'] == 0){
                        echo "                    
                            <td></td>
                        <td><span class='Bold'>Open: </span>
                        <input type='radio' name='radio' value='Open'></td>

                        <td><span class='Bold'>Closed: </span>
                        
                        <input type='radio' name='radio' value='Closed' checked></td>
                        <td><input type='submit' name='openCloseReg' value='Update'></td>";
                        
                        } 
                        elseif ($semester_data['value'] == 1) {
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
    <article id="account">
        <h2>Course to run in Semester <?php echo $semester_id; ?></h2>
                    
        <?php $courses = get_courses(); ?>
        
        <!---------------------------------------------------------------------------------------------------->
        
            <form action="" method='POST'>
                <table class="Table">
                    <tr>
                        <td><span class='Bold'>Course Code </span></td>
                        <td><span class='Bold'>Course Title </span></td>
                        <td colspan="2"><span class='Bold'>Running in Semester </span></td>
                    </tr>                    
                    
                    <?php
                
                    foreach ($courses as $course){
                        $id = $course['id'];
                        $course['code'];
                        $course['title'];
                        
                        $semester_course = get_semester_courses($course['id'], $semester_id)
                        ?> 

                        <tr>
                            <td><?php echo $course['code']?></td>
                            <td><?php echo $course['title']?></td>
                            
                            <?php 
                            if (empty ($semester_course)) {
                                echo '
                                
                                <td><input type="checkbox" name="courseSelection" value="Selected"/></td>
                                <td><input type="submit" name="',$id,'" value="Update"></td>
                                ';
                                
                                $submitCourseList = $_POST[$id];
                                $checked = $_POST['courseSelection'];
                                if ($submitCourseList){
                                    
                                    if($checked == 'Selected'){
                                        mysql_query("INSERT INTO Semesters_has_Courses VALUES ('$semester_id', '$id')") or die("display_db_query:" . mysql_error());
                                        header('Location: semester_edit.php?semester_id='.$semester_id);
                                    } else{
                                        mysql_query("DELETE FROM Semesters_has_Courses WHERE Semesters_id='$semester_id' 
                                                AND Courses_id='$id'") or die("display_db_query:" . mysql_error());
                                        header('Location: semester_edit.php?semester_id='.$semester_id);                                    
                                        }
                                }  
                            } else{
                                echo '
                                    
                                <td><input type="checkbox" name="courseSelection" value="Selected2" checked/></td>
                                <td><input type="submit" name="',$id,'" value="Update"></td>
                                ';
                                
                                $submitCourseList = $_POST[$id];
                                $checked = $_POST['courseSelection'];
                                if ($submitCourseList){
                                    if($checked == 'Selected2'){
                                        mysql_query("DELETE FROM Semesters_has_Courses WHERE Semesters_id='$semester_id' 
                                                AND Courses_id='$id'") or die("display_db_query:" . mysql_error());
                                        header('Location: semester_edit.php?semester_id='.$semester_id);                                         
                                          
                            } else{      
                                        mysql_query("DELETE FROM Semesters_has_Courses WHERE Semesters_id='$semester_id' 
                                                AND Courses_id='$id'") or die("display_db_query:" . mysql_error());
                                        header('Location: semester_edit.php?semester_id='.$semester_id);                                         
                                        
                                        
                                        }
                                }                            
                                    
                            }                  
                            
                            ?>
                        </tr>
                        <?php
                        
                }
                ?>
                
             </form>
           </table>
            
    </article>
    

</section>

    <?php include '../assets/template/footer.php'; ?>