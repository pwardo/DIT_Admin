<?php
include 'assets/db/init.php';
include 'assets/db/class.acl.php';
include 'assets/db/user.func.php';
?>
<!DOCTYPE HTML>
<head>
      <title>DIT Admin - Index</title>
      <link href="assets/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>

<div id="main_wrapper">
  
    <header>
	<section id="top_header_left">
		<h1><a href="index.php">DIT Admin - Index</a></h1>
		<h2></h2>		
	</section>
		
	<section id="top_header_right">
		<nav id="top_menu">
                    <?php include 'assets/widgets/menu.php' ?>
                </nav>
	</section>
    </header>

<?php
if (logged_in()){
    
    $user_data = user_data('user_id','username');
    $user_id = $_SESSION['user_id'];
    
    $myACL = new ACL();
    if ($myACL->hasPermission('access_admin') == true)
    {
            header("location: ../DIT_admin/admin");
    }
    elseif ($myACL->hasPermission('access_student') == true)
    {
            header("location: ../DIT_admin/student/");
    }
    elseif($myACL->hasPermission('access_lecturer') == true)
    {
            header("location: ../DIT_admin/lecturer/");
    }

} else {
    
    include 'assets/widgets/login.php';
    include 'assets/widgets/welcome.php';     
}
?>
    <footer id="the_footer">

            <div id="footer_left">
                    <h2>&copy; 2011 Patrick Ward | D11124386 </h2>			
            </div>

            <div id="footer_right">

            </div>
    </footer>
    
    <script type="text/javascript" src="assets/js/jquery.js"></script>
    <script type="text/javascript" src="assets/js/forms_input.js"></script>
    <script type="text/javascript" src="assets/js/textarea.js"></script>
    <script type="text/javascript" src="assets/js/multi_image_upload.js"></script>
    <script type="text/javascript" src="assets/js/highlighter.js"></script>
    <script type="text/javascript" src="assets/js/fade_effects.js"></script>  
</div> <!-- to close main wrapper -->

</body>
</html>
