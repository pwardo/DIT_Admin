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
    $user_data = user_data('user_id','username');
    $user_id = $_SESSION['user_id'];

    $course_id = $_GET['course_id'];
    $course_data = get_course_data($course_id);
    
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
        <h2>Your Course Details:</h2>
        <p></p>
        
        <table class="Table">			
            <tr> <!-- change last name -->
                <td><span class="Bold">Course Code: </span></td>
                <td></td>
                <td><b><?php echo $course_data['code']; ?></b><td>
            </tr>

            <tr> <!-- change last name -->
                <td><span class="Bold">Course Title: </span></td>
                <td></td>
                <td><b><?php echo $course_data['title']; ?></b></td>
            </tr>
        </table>
    </article>
       

        
    <!---------------------------------------------------------------------------------------------------->
    <?php $modules = get_course_modules($course_id); 

    ?>

    <article>
            <table class="Table">
                <tr>
                    <td><b>Module Code </b></td>
                    <td><b>Module Title </b></td>
                    <td><b>Module Lecturer </b></td>
                </tr>                    

                <?php foreach ($modules as $module){ 
                    $module_lecturer_data = get_module_lecturer($module['id']);
                    $lecturer_id = $module_lecturer_data['Staff_staff_id'];
                    
                    if(empty($lecturer_id)){
                       ?>
                        <tr>
                            <td><?php echo $module['code']?></td>
                            <td><?php echo $module['title']?></td>

                            <td>No Lecturer has been assigned</td>                    
                            <td></td>
                            <td>                      
                                <?php echo '<a href="module_view.php?module_id=', $module['id'],'">View</a>' ?>
                            </td>
                        <tr/>                            
                        <?php
                    }
                    else{
                        $lecturer_data = get_staff_data($lecturer_id);
                        
                       ?>
                        <tr>
                            <td><?php echo $module['code']?></td>
                            <td><?php echo $module['title']?></td>

                            <td><?php echo $lecturer_data['first_name'], ' ', $lecturer_data['last_name'] ?></td>                    
                            <td></td>
                            <td>                      
                                <?php echo '<a href="module_view.php?module_id=', $module['id'],'">View</a>' ?>
                            </td>
                        <tr/>                            
                        <?php                    
                   }
                 }
                ?>
 
           </table>
    </article>
</section>

    <?php include '../assets/template/footer.php'; ?>