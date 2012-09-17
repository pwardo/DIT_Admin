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
                    <li><a href="register_step1.php">Register User</a></li>
                    <li><a href="semesters.php">Semesters</a></li>
                    <li><a href="Courses.php">Courses</a></li>
                    <li><a href="../logout.php">Logout </a></li>
                </ul>
            </nav>
	</section>
    </header>

    <section id="main_section">
        <article id="blog_album_page">
            <h3>Hi <?php echo $user_id, $user_data['user_id'], $user_data['username'];?>, welcome to your DIT Admin portal..... </h3>
                
        </article>
   </section>

    <?php include '../assets/template/footer.php'; ?>