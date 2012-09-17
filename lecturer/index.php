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

    $user_data = user_data('user_id','username');
    $user_id = $_SESSION['user_id'];
       
    $staff_data = get_staff_data_by_user_id($user_id);
    $staff_id = $staff_data['staff_id'];
      

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
                    <li><a href="account.php">My Account</a></li>
                    <li><a href="../logout.php">Logout </a></li>
                </ul>
            </nav>
	</section>
    </header>

    <section id="main_section2">
        <article id="blog_album_page">
            <h3>Hi <?php echo $staff_data['first_name'];?>, welcome .... </h3>
            
                
        </article>
 <?php $modules = get_my_assigned_modules($staff_id); 

    ?>

    <article>
        <h3>My Modules</h3>
            <table class="Table">
                <tr>
                    <td><b>Module Code </b></td>
                    <td><b>Module Title </b></td>
                    <td><b>Course</b></td>
                </tr>                    

                <?php foreach ($modules as $module){
                    $module_data = get_module_data ($module['Modules_id']);              
                    $course_data = get_course_data($module_data['Courses_id']);
                       ?>
                        <tr>
                            <td><?php echo $module_data['code']?></td>
                            <td><?php echo $module_data['title']?></td>
                            <td><?php echo $course_data['code'],', ', $course_data['title']?></td>

                            <td></td>
                            <td>                      
                                <?php echo '<a href="module_view.php?module_id=', $module_data['id'],'">View</a>' ?>
                            </td>
                        <tr/>                            
                        <?php                   
                 }
                ?>
 
           </table>
    </article>
        
   </section>

    <?php include '../assets/template/footer.php'; ?>