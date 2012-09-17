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
   <?php
    $courses = get_courses();

    if (empty ($courses)){
        echo '
            <article id="course_page"
                <h2>There are no courses yet.</h2>
            </article>
        ';
        } else {
        foreach ($courses as $course){          
            ?>
            <article id="blog_semester_page">
                <?php
                    echo '
                        <hgroup>
                        <h2><a href="course_edit.php?course_id=', $course['id'] ,'">Course Code: ', $course['code'], '</a></h2>
                        </hgroup>
                            <p>Course Title: ', $course['title'] ,'</p>
                        <footer>
                            <a href="course_edit.php?course_id=', $course['id'],'">Edit</a>
                        </footer>                        
                        ';

                    $course_id = $course['id'];                                    
            ?>
            </article>
            <?php
        }
    }
    ?>
    </section>
    
    <aside id="side_options">
        <header><h2>Options</h2></header>
        <ul id="sidebar_content">
            <li><a href="course_create.php">Create New course</a></li>
        </ul>
    </aside>
    
    <?php include '../assets/template/footer.php'; ?>