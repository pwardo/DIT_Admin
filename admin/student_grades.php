<?php
include '../assets/db/init.php';
include '../assets/db/class.acl.php';
include '../assets/db/user.func.php';
include '../assets/db/semesters.func.php';

$myACL = new ACL();
if ($myACL->hasPermission('access_admin') != true)
{
	header("location: ../index.php");
} else {



$registration_data = explode(",", $_GET['registrations_id']);

$registration_id = $registration_data['0'];
$student_id = $registration_data['1'];

$student_data = get_student_data($student_id);


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
       <?php $grades = get_student_grades($registration_id);  ?>
            
            
        <h2>Grades for <?php echo $student_data['first_name'], ' ',$student_data['last_name'] ; ?></h2>            
        <p>Student ID: <?php echo $student_data['student_id']; ?></p>            
            <table class="Table">
                <tr>
                    <td><b>Module Code </b></td>
                    <td><b>Module Title </b></td>
                    <td></td>
                    <td><b>Grade</b></td>
                </tr>                    

                <?php foreach ($grades AS $grade){
                
                    ?>
                
                    <tr>
                        <td><?php echo $grade['Modules_code']; ?></td>
                        <td><?php echo $grade['Modules_title'];  ?></td>
                        <td></td>
                        
                        <?php
                        if(empty($grade['grade'])){
                            echo "<td>No grade assigned</td>";
                        } else{
                            echo '<td>',$grade['grade'],'</td>';
                        }
                        ?>
                    </tr>
                        <?php
                    }       
                ?>
                
        </table>            
            
        </article>
    </section>
    

<?php
}
    include '../assets/template/footer.php'; ?>