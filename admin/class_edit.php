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

$class_id = $_GET['class_id'];
$class_data  = get_class_data($class_id);

$module_data = get_module_data($class_data['module_id']);
$module_id = $module_data['id'];

    $submitUpdateClass = $_POST['updateClass'];
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
        <h2>Edit Class <?php echo $class_data['id']; ?></h2>
        <p>Module: <?php echo '<a href="module_edit.php?module_id=', $module_data['id'],'">',$module_data["code"],', ', $module_data["title"],'</a>' ?>
        </p>
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
                                
                    <?php 
                    if(!empty($classes_day_id)){
                        
                        foreach ($days as $day) {
                            if($day['id'] === $classes_day_id){
                                echo '<option value="',$day['id'],'" selected="selected">',$day["day"],'</option>';
                            } else{
                                echo '<option value="',$day['id'],'">',$day["day"],'</option>
                                     ';
                            }
                        }                        
                    }
                                        
                    ?>
                        </select>                           
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
                                
                    <?php
                    
                    if(!empty($classes_room_id)){
                        
                        foreach ($rooms as $room) {
                            if($room['id'] === $classes_room_id){
                                echo '<option value="',$room['id'],'" selected="selected">',$room["name"],' cap.',$room["capacity"],'</option>';
                            } else{
                                echo '<option value="',$room['id'],'">',$room["name"],' cap.',$room["capacity"],'</option>
                                     ';
                            }
                        }                        
                    }
                                        
                    ?>
                        </select>
                </tr>
                <tr>
                    <td><input type='submit' name='updateClass' value='Update Class'></td>
                    <td colspan="3">
                        
                    <?php
                    if ($submitUpdateClass){
                        if (class_scheduled($timeslot_selected, $day_selected,$room_selected)){
                            echo '<b>A class has already been schediled for this time, day and room.</b>';
                        }
                        else{
                            mysql_query("UPDATE Classes SET Days_id = '$day_selected',
                                Time_Slots_id = '$timeslot_selected',
                                Rooms_id = '$room_selected'
                                WHERE Class_id = '$class_id'
                                    ") or die("display_db_query:" . mysql_error());

                            header('Location: module_edit.php?module_id='.$module_id);
                        }                        
                    }
                    ?>
                        
                    </td>                
            </form>
        </table>
    </article>


</section>

    <?php include '../assets/template/footer.php'; ?>