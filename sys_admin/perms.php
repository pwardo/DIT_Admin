<?php
include '../assets/db/init.php';
include '../assets/db/class.acl.php';
include '../assets/db/user.func.php';

$myACL = new ACL();
if (isset($_POST['action']))
{
    switch($_POST['action'])
    {
        case 'savePerm':
            $strSQL = sprintf("REPLACE INTO `Permissions` SET `perm_id` = %u, `perm_name` = '%s', `perm_key` = '%s'",$_POST['perm_id'],$_POST['perm_name'],$_POST['perm_key']);
            mysql_query($strSQL);
        break;
        case 'delPerm':
            $strSQL = sprintf("DELETE FROM `Permissions` WHERE `perm_id` = %u LIMIT 1",$_POST['perm_id']);
            mysql_query($strSQL);
        break;
    }
    header("location: perms.php");
}
if ($myACL->hasPermission('access_system_admin') != true)
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
            <h2><a href="../sys_admin/">Back to System Admin</a></h2>		
	</section>
	<section id="top_header_right">
            <nav id="top_menu">
                <h1>System Admin Options</h1>
                <ul>
                    <li><a href="users.php">Manage Users</a></li>
                    <li><a href="roles.php">Manage Roles</a></li>
                    <li><a href="perms.php">Manage Permissions</a></li>
                    <li><a href="../logout.php">Logout </a></li>
                </ul>
            </nav>
	</section>
    </header>
    
    
    <section id="main_section">
        <article id="blog_album_page">

	<? if ($_GET['action'] == '') { ?>
    	<h2>Select a Permission to Manage:</h2>
    <? 
            $roles = $myACL->getAllPerms('full');
            foreach ($roles as $k => $v)
            {
                    echo "<p><a href=\"?action=perm&perm_id=" . $v['ID'] . "\">" . $v['Name'] . "</a><br /></p>";
            }
            if (count($roles) < 1)
            {
                    echo "No permissions yet.<br />";
            } ?>
            <br/>

        <input type="button" name="New" value="New Permission" onclick="window.location='?action=perm'" />
    <? } 
    if ($_GET['action'] == 'perm') { 
        if ($_GET['perm_id'] == '') {
            ?>
            <h2>New Permission:</h2>
            <? 
            
            } else { ?>
            <h2>Manage Permission: (<?= $myACL->getPermNameFromID($_GET['perm_id']); ?>)</h2><? } ?>
            
            <form action="perms.php" method="post">
                <label for="perm_name">Name:</label>
                    <input type="text" name="perm_name" id="perm_name" value="<?= $myACL->getPermNameFromID($_GET['perm_id']); ?>" maxlength="30" /><br />
                
                <label for="perm_key">Key:</label>
                    <input type="text" name="perm_key" id="perm_key" value="<?= $myACL->getPermKeyFromID($_GET['perm_id']); ?>" maxlength="30" /><br />

                <input type="hidden" name="action" value="savePerm" />
                <input type="hidden" name="perm_id" value="<?= $_GET['perm_id']; ?>" />
                <input type="submit" name="Submit" value="Submit" />
            </form>

            <form action="perms.php" method="post">
                <input type="hidden" name="action" value="delPerm" />
                <input type="hidden" name="perm_id" value="<?= $_GET['perm_id']; ?>" />
                <input type="submit" name="Delete" value="Delete" />
            </form>

            <form action="perms.php" method="post">
                <input type="submit" name="Cancel" value="Cancel" />
            </form>
        <? 
    } ?>
        </article>
    </section>
<?php include '../assets/template/footer.php' ?>
    