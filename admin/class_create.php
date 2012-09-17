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
$module_code =  $module_data['code'];
$module_title = $module_data['title'];

    $submitCreateClass = $_POST['createClass'];
    $timeslot_selected = $_POST['timeslot'];
    $day_selected = $_POST['day'];
    $room_selected = $_POST['room'];

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
        <h2>Schedule a new Class for Module: <?php echo '<a href="module_edit.php?module_id=', $module_data['id'],'">',$module_code,'</a>' ?></h2>
        <p><?php echo '<a href="module_edit.php?module_id=', $module_data['id'],'">', $module_title,'</a>' ?></p>
        <table class="Table">			
     
    <?php 
    $timeslots = get_timeslots(); 
    $classes_timeslot_id = $class_data['Time_slot_id'];
    
    ?>

           <form action="" method='POST'>
                <tr> <!-- change last name -->
                    <td><span class="Bold">Start Time: </span></td>
                    <td></td>
                    <td>
                        <select name="timeslot">                   
                                
                    <?php 
                        
                        foreach ($timeslots as $timeslot) {
                            if($timeslot['id'] === $classes_timeslot_id){
                                echo '<option value="',$timeslot['id'],'" selected="selected">',$timeslot["startTime"],'</option>';
                            } else{
                                echo '<option value="',$timeslot['id'],'">',$timeslot["startTime"],'</option>
                                     ';
                            }
                        }
                    ?>
                        </select>
                </tr>
            
<!------------------------------------------->
<?php 
    $days = get_days(); 
    $classes_day_id = $class_data['day_id'];
    
    
?>

                <tr> <!-- change last name -->
                    <td><span class="Bold">Day: </span></td>
                    <td></td>
                    <td>
                        <select name="day">                   
                                
                    <?php foreach ($days as $day) {
                            if($day['id'] === $classes_day_id){
                                echo '<option value="',$day['id'],'" selected="selected">',$day["day"],'</option>';
                            } else{
                                echo '<option value="',$day['id'],'">',$day["day"],'</option>
                                     ';
                            }
                        }                                        
                    ?>
                        </select>                           
                    <td>
                </tr>

<!------------------------------------------->
<?php 
    $rooms = get_rooms(); 
    $classes_room_id = $class_data['room_id'];
    
?>
                <tr> <!-- change last name -->
                    <td><span class="Bold">Room: </span></td>
                    <td></td>
                    <td>
                        <select name="room">                   
                                
                    <?php foreach ($rooms as $room) {
                            if($room['id'] === $classes_room_id){
                                echo '<option value="',$room['id'],'" selected="selected">',$room["name"],' cap.',$room["capacity"],'</option>';
                            } else{
                                echo '<option value="',$room['id'],'">',$room["name"],' cap.',$room["capacity"],'</option>
                                     ';
                            }
                    }
                                        
                    ?>
                        </select>
                    
                </tr>
                <tr>
                    <td><input type='submit' name='createClass' value='Schedule class'></td>
                    <td colspan="3">
                        
                    <?php
                    if ($submitCreateClass){
                        if (class_scheduled($timeslot_selected, $day_selected,$room_selected)){
                            echo '<b>A class has already been schediled for this time, day and room.</b>';
                        }
                        else{
                            mysql_query("INSERT INTO Classes (Days_id, Rooms_id, Time_slots_id, Modules_id)
                                VALUES ('$day_selected','$room_selected','$timeslot_selected','$module_id')
                                ") or die("display_db_query:" . mysql_error());

                            header('Location: module_edit.php?module_id='.$module_id);
                        }
                    }
                    ?>
                        
                    </td>
                </tr>
            </form>
        </table>
        

            
        
        
    </article>
</section>

    <?php include '../assets/template/footer.php'; ?>