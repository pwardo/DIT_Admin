<?php
include '../assets/db/init.php';
include '../assets/db/class.acl.php';
include '../assets/db/user.func.php';

$myACL = new ACL();
if (isset($_POST['action']))
{
    switch($_POST['action'])
    {
        case 'saveRoles':
            $redir = "?action=user&user_id=" . $_POST['user_id'];
            foreach ($_POST as $k => $v)
            {
                if (substr($k,0,5) == "role_")
                {
                    $roleID = str_replace("role_","",$k);
                    if ($v == '0' || $v == 'x') {
                            $strSQL = sprintf("DELETE FROM `Users_has_Roles` WHERE `users_user_id` = %u AND `roles_role_id` = %u",$_POST['user_id'],$roleID);
                    } else {
                            $strSQL = sprintf("REPLACE INTO `Users_has_Roles` SET `users_user_id` = %u, `roles_role_id` = %u, `addDate` = '%s'",$_POST['user_id'],$roleID,date ("Y-m-d H:i:s"));
                    }
                    mysql_query($strSQL);
                }
            }

        break;
        case 'savePerms':
            $redir = "?action=user&user_id=" . $_POST['user_id'];
            foreach ($_POST as $k => $v)
            {
                if (substr($k,0,5) == "perm_")
                {
                    $perm_id = str_replace("perm_","",$k);
                    echo $perm_id;
                    
                    if ($v == 'x')
                    {
                        $strSQL = sprintf("DELETE FROM `Users_has_Permissions` WHERE `Users_user_id` = %u AND `Permissions_perm_id` = %u",$_POST['user_id'],$perm_id or mysql_error());
                    } else {
                        $strSQL = sprintf("REPLACE INTO `Users_has_Permissions` SET `Users_user_id` = %u, `Permissions_perm_id` = %u, `value` = %u, `addDate` = '%s'",$_POST['user_id'],$perm_id,$v,date ("Y-m-d H:i:s") or mysql_error());
                    }
                    mysql_query($strSQL);
                }
            }
        break;
    }
    header("location: users.php" . $redir);
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
            <h1><a href="index.php">DIT Admin</a></h1>
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
	<? if ($_GET['action'] == '' ) { ?>
    	<h2>Select a User to Manage:</h2>
        <? 
            $strSQL = "SELECT * FROM `Users` ORDER BY `Username` ASC";
            $data = mysql_query($strSQL);
            while ($row = mysql_fetch_assoc($data))
            {
                echo "<p><a href=\"?action=user&user_id=" . $row['user_id'] . "\">" . $row['username'] . "</a><br /></p>";
            }
    } ?>
    <?
    if ($_GET['action'] == 'user' ) { 
		$userACL = new ACL($_GET['user_id']);
	?>
    	<h2>Managing <?= $myACL->getUsername($_GET['user_id']); ?>:</h2>
            <p>Select from below.</p>
        <h3>Roles for user:   (<a href="users.php?action=roles&user_id=<?= $_GET['user_id']; ?>">Manage Roles</a>)</h3>
        <ul>
        <? $roles = $userACL->getUserRoles();
            foreach ($roles as $k => $v)
            {
                echo "<li>" . $userACL->getRoleNameFromID($v) . "</li>";
            }
            ?>
        </ul>
        
        <h3>Permissions for user:   (<a href="users.php?action=perms&user_id=<?= $_GET['user_id']; ?>">Manage Permissions</a>)</h3>
        <ul>
        <? $perms = $userACL->perms;
            foreach ($perms as $k => $v)
            {
                if ($v['value'] === false) { continue; }
                echo "<li>" . $v['Name'];
                if ($v['inheritted']) { echo "  (inheritted)"; }
                echo "</li>";
            }
            ?>
        </ul>
     <? } ?>
     
     <? if ($_GET['action'] == 'roles') { ?>
     
        <h2>Manage User Roles: (<?= $myACL->getUsername($_GET['user_id']); ?>)</h2>
     
        <form action="users.php" method="post">
        
            <table border="0" cellpadding="5" cellspacing="0">
        
                <tr>
                    <th></th>
                    <th>Member</th>
                    <th>Not Member</th>
                </tr>
            <? 
                    
            $roleACL = new ACL($_GET['user_id']);
            $roles = $roleACL->getAllRoles('full');
            
            foreach ($roles as $k => $v)
            {
                echo "<tr><td><label>" . $v['Name'] . "</label></td>";
                echo "<td><input type=\"radio\" name=\"role_" . $v['ID'] . "\" id=\"role_" . $v['ID'] . "_1\" value=\"1\"";

                if ($roleACL->userHasRole($v['ID'])) { echo " checked=\"checked\""; }
                echo " /></td>";
                echo "<td><input type=\"radio\" name=\"role_" . $v['ID'] . "\" id=\"role_" . $v['ID'] . "_0\" value=\"0\"";

                if (!$roleACL->userHasRole($v['ID'])) { echo " checked=\"checked\""; }
                echo " /></td>";
                echo "</tr>";
            }
        ?>
            </table>
            
            <input type="hidden" name="action" value="saveRoles" />
            <input type="hidden" name="user_id" value="<?= $_GET['user_id']; ?>" />
            <input type="submit" name="Submit" value="Submit" />
        </form>
        
        <form action="users.php" method="post">
            <input type="button" name="Cancel" onclick="window.location='?action=user&user_id=<?= $_GET['user_id']; ?>'" value="Cancel" />
        </form>
        <? } ?>
        <?
        if ($_GET['action'] == 'perms' ) { 
            ?>
            <h2>Manage User Permissions: (<?= $myACL->getUsername($_GET['user_id']); ?>)</h2>
            <form action="users.php" method="post">
                <table border="0" cellpadding="5" cellspacing="0">
                <tr><th></th><th></th></tr>
                <? 
                $userACL = new ACL($_GET['user_id']);

                $rPerms = $userACL->perms;
                $aPerms = $userACL->getAllPerms('full');

                foreach ($aPerms as $k => $v)
                {
                    echo "<tr><td>" . $v['Name'] . "</td>";
                    echo "<td><select name=\"perm_" . $v['ID'] . "\">";
                    echo "<option value=\"1\"";

                    if ($userACL->hasPermission($v['Key']) && $rPerms[$v['Key']]['inheritted'] != true) { echo " selected=\"selected\""; }

                    echo ">Allow</option>";
                    echo "<option value=\"0\"";

                    if ($rPerms[$v['Key']]['value'] === false && $rPerms[$v['Key']]['inheritted'] != true) { echo " selected=\"selected\""; }

                    echo ">Deny</option>";
                    echo "<option value=\"x\"";

                    if ($rPerms[$v['Key']]['inheritted'] == true || !array_key_exists($v['Key'],$rPerms))
                    {
                        echo " selected=\"selected\"";
                        if ($rPerms[$v['Key']]['value'] === true )
                        {
                                $iVal = '(Allow)';
                        } else {
                                $iVal = '(Deny)';
                        }
                    }
                    echo ">Inherit $iVal</option>";
                    echo "</select></td></tr>";
                }
            ?>
            </table>
                <input type="hidden" name="action" value="savePerms" />
                <input type="hidden" name="user_id" value="<?= $_GET['user_id']; ?>" />
                <input type="submit" name="Submit" value="Submit" />
            </form>
            
            <form action="users.php" method="post">
                <input type="button" name="Cancel" onclick="window.location='?action=user&user_id=<?= $_GET['user_id']; ?>'" value="Cancel" />
            </form>
        <? } ?>
        
        </article>
    </section>
<?php include '../assets/template/footer.php' ?>