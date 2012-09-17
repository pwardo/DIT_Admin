<?php
include '../assets/db/init.php';
include '../assets/db/class.acl.php';
include '../assets/db/user.func.php';

$myACL = new ACL();
if (isset($_POST['action']))
{
    switch($_POST['action'])
    {
        case 'saveRole':
            $strSQL = sprintf("REPLACE INTO `Roles` SET `role_id` = %u, `role_name` = '%s'",$_POST['role_id'],$_POST['role_name']);
            
            mysql_query($strSQL);
            if (mysql_affected_rows() > 1)
            {
                $role_id = $_POST['role_id'];
            } else {
                $role_id = mysql_insert_id();
            }
            foreach ($_POST as $k => $v)
            {
                if (substr($k,0,5) == "perm_")
                {
                    $perm_id = str_replace("perm_","",$k);
                    if ($v == 'X')
                    {
                        $strSQL = sprintf("DELETE FROM `Roles_has_Permissions` WHERE `roles_role_id` = %u AND `permissions_perm_id` = %u",$role_id,$perm_id or mysql_error());
                        mysql_query($strSQL);
                        continue;
                    }
                    $strSQL = sprintf("REPLACE INTO `Roles_has_Permissions` SET `roles_role_id` = %u, `permissions_perm_id` = %u, `value` = %u, `addDate` = '%s'",$role_id,$perm_id,$v,date ("Y-m-d H:i:s") or mysql_error());
                    mysql_query($strSQL);
                }
            }
            header("location: roles.php");
        break;
        
        case 'delRole':
            $strSQL = sprintf("DELETE FROM `Roles` WHERE `role_id` = %u LIMIT 1",$_POST['role_id']);
            mysql_query($strSQL);
            $strSQL = sprintf("DELETE FROM `Users_has_Roles` WHERE `roles_role_id` = %u",$_POST['role_id']);
            mysql_query($strSQL);
            $strSQL = sprintf("DELETE FROM `Roles_has_Permissions` WHERE `roles_role_id` = %u",$_POST['role_id']);
            mysql_query($strSQL);
            header("location: roles.php");
        break;
    }
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
                <h1>Main Navigation</h1>
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
    	<h2>Select a Role to Manage:</h2>
        <? 
            $roles = $myACL->getAllRoles('full');
            foreach ($roles as $k => $v)
            {
                echo "<p><a href=\"?action=role&role_id=" . $v['ID'] . "\">" . $v['Name'] . "</a><br /></p>";
            }
            if (count($roles) < 1)
            {
                echo "No roles yet.<br />";
            } ?>
        <br/>
        <input type="button" name="New" value="New Role" onclick="window.location='?action=role'" />
    <? } 
    
    if ($_GET['action'] == 'role') { 
        if ($_GET['role_id'] == '') { 
		?>
    	<h2>New Role:</h2>
        <? } else { ?>
    	
        <h2>Manage Role: (<?= $myACL->getRoleNameFromID($_GET['role_id']); ?>)</h2><? } ?>
        
        <form action="roles.php" method="post">
            <label for="role_name">Name:</label>
            
                <input type="text" name="role_name" id="role_name" value="<?= $myACL->getRoleNameFromID($_GET['role_id']); ?>" />

            <table border="0" cellpadding="5" cellspacing="0">
                <tr>
                    <th></th>
                    <th>Allow</th>
                    <th>Deny</th>
                    <th>Ignore</th>
                </tr>
            <? 
            $rPerms = $myACL->getRolePerms($_GET['role_id']);
            $aPerms = $myACL->getAllPerms('full');

            foreach ($aPerms as $k => $v)
            {
                echo "<tr><td><label>" . $v['Name'] . "</label></td>";
                
                echo "<td>
                    <input type=\"radio\" name=\"perm_" . $v['ID'] . "\" id=\"perm_" . $v['ID'] . "_1\" value=\"1\"";
                
                if ($rPerms[$v['Key']]['value'] === true && $_GET['role_id'] != '') { echo " checked=\"checked\""; }
                
                echo " /></td>";
                
                echo "<td>
                    <input type=\"radio\" name=\"perm_" . $v['ID'] . "\" id=\"perm_" . $v['ID'] . "_0\" value=\"0\"";
                
                if ($rPerms[$v['Key']]['value'] != true && $_GET['role_id'] != '') { echo " checked=\"checked\""; }
                
                echo " /></td>";
				
                echo "<td>
                    <input type=\"radio\" name=\"perm_" . $v['ID'] . "\" id=\"perm_" . $v['ID'] . "_X\" value=\"X\"";
                
                if ($_GET['role_id'] == '' || !array_key_exists($v['Key'],$rPerms)) { echo " checked=\"checked\""; }
                echo " /></td>";
                echo "</tr>";
            }
        ?>
    	</table>
    	<input type="hidden" name="action" value="saveRole" />
        <input type="hidden" name="role_id" value="<?= $_GET['role_id']; ?>" />
    	<input type="submit" name="Submit" value="Submit" />
    </form>
    
        <form action="roles.php" method="post">
         <input type="hidden" name="action" value="delRole" />
         <input type="hidden" name="role_id" value="<?= $_GET['role_id']; ?>" />
    	<input type="submit" name="Delete" value="Delete" />
    </form>
    
    <form action="roles.php" method="post">
    	<input type="submit" name="Cancel" value="Cancel" />
    </form>
    <? } ?>
        </article>
    </section>
<?php include '../assets/template/footer.php' ?>
    
