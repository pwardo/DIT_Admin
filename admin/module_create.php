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

$course_id = $_GET['course_id'];
$course_data = get_course_data($course_id);
$course_code =  $course_data['code'];
$course_title = $course_data['title'];
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
        <h2>Add a New module to course, <?php echo $course_code,', ', $course_title; ?> </h2>
        
        <table class="Table">			
            <form action="" method='POST'>
                <tr> <!-- change last name -->
                    <td><span class="Bold">Module Code: </span></td>
                    <td></td>
                    <td><input type='text' name='createModuleCode'></td><td><b>Example: M001</b></td>
                </tr>
        
                <tr> <!-- change last name -->
                    <td><span class="Bold">Course Title: </span></td>
                    <td></td>
                    <td><input type='text' name='createModuleTitle'></td><td><b>Example: Computing Fundamentals</b></td>
                </tr>
                <tr>
                    <td colspan="3"><input type='submit' value='Create New Module'></td>
                </tr>                
            </form>
           
        </table>
                <?php
                if (isset($_POST['createModuleCode'], $_POST['createModuleTitle'])){
                    $submitModuleCode = $_POST['createModuleCode'];
                    $submitModuleTitle = $_POST['createModuleTitle'];

                    if(empty($submitModuleCode) || empty($submitModuleTitle)){
                        $errors[] = 'Nodule Code and Module Title are required';
                    }
                    if (!empty($errors)){
                        foreach ($errors as $error){
                            echo '<h3><br/>', $error, '<br/></h3>'; // if there are errors contained in the array, print each one seperated by a line break
                        }
                    }
                    else{
                        $newModule = create_module($submitModuleCode, $submitModuleTitle, $course_id);    

                        $module_id = $newModule;
                        header('Location: module_edit.php?module_id='.$module_id);
                        exit;
                    }
                }

                ?>
    </article>
</section>

    <?php include '../assets/template/footer.php'; ?>