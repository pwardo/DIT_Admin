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
    $semesters = get_semesters();

    if (empty ($semesters)){
        echo '
            <article id="semester_page"
                <h2>There are no semesters yet.</h2>
            </article>
        ';
        } else {
        foreach ($semesters as $semester){
            
            if ($semester['value'] == 1){
                $value = 'Open to registrations';
            } else{
                $value = 'Closed to registrations';
            }
            
            ?>
            <article id="blog_semester_page">
                <?php
                    echo '
                        <hgroup>
                        <h2><a href="semester_edit.php?semester_id=', $semester['id'] ,'">Semester ID: ', $semester['id'], '</a></h2>
                        </hgroup>
                            <p>Start Date: ', $semester['start_date'] ,'...</p>
                            <p>End Date: ', $semester['end_date'] ,'...</p><br />
                            <p><b>', $value ,'</b></p><br />
                        <footer>
                            <a href="semester_edit.php?semester_id=', $semester['id'],'">Edit</a>
                        </footer>                        
                        ';

                    $semester_id = $semester['id'];                                    
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
            <li><a href="semester_create.php">Create New Semester</a></li>
        </ul>
    </aside>
    
    <?php include '../assets/template/footer.php'; ?>