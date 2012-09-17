<?
class ACL
{
    var $perms = array();		//Array : Stores the permissions for the user
    var $user_id = 0;			//Integer : Stores the ID of the current user
    var $userRoles = array();	//Array : Stores the roles of the current user

    function __constructor($user_id = '')
    {
        if ($user_id != '')
        {
            $this->user_id = floatval($user_id);
        } else {
            $this->user_id = floatval($_SESSION['user_id']);
        }
        $this->userRoles = $this->getUserRoles('ids');
        $this->buildACL();
    }

    function ACL($user_id = '')
    {
        $this->__constructor($user_id);
        //crutch for PHP4 setups
    }

    function buildACL()
    {
        //first, get the rules for the user's role
        if (count($this->userRoles) > 0)
        {
                $this->perms = array_merge($this->perms,$this->getRolePerms($this->userRoles));
        }
        //then, get the individual user permissions
        $this->perms = array_merge($this->perms,$this->getUserPerms($this->user_id));
    }

    function getPermKeyFromID($permID)
    {
        $strSQL = "SELECT `perm_key` FROM `Permissions` WHERE `perm_id` = " . floatval($permID) . " LIMIT 1";
        $data = mysql_query($strSQL);
        $row = mysql_fetch_array($data);
        return $row[0];
    }

    function getPermNameFromID($permID)
    {
        $strSQL = "SELECT `perm_name` FROM `Permissions` WHERE `perm_id` = " . floatval($permID) . " LIMIT 1";
        $data = mysql_query($strSQL);
        $row = mysql_fetch_array($data);
        return $row[0];
    }

    function getRoleNameFromID($role_id)
    {
        $strSQL = "SELECT `role_name` FROM `Roles` WHERE `role_id` = " . floatval($role_id) . " LIMIT 1";
        $data = mysql_query($strSQL);
        $row = mysql_fetch_array($data);
        return $row[0];
    }

    function getUserRoles()
    {
        $strSQL = "SELECT * FROM `Users_has_Roles` WHERE `users_user_id` = " . floatval($this->user_id) . " ORDER BY `addDate` ASC";
        $data = mysql_query($strSQL);
        $resp = array();
        while($row = mysql_fetch_array($data))
        {
            $resp[] = $row['roles_role_id'];
        }
        return $resp;
    }

    function getAllRoles($format='ids')
    {
        $format = strtolower($format);
        $strSQL = "SELECT * FROM `Roles` ORDER BY `role_name` ASC";
        $data = mysql_query($strSQL);
        $resp = array();
        while($row = mysql_fetch_array($data))
        {
            if ($format == 'full')
            {
                $resp[] = array("ID" => $row['role_id'],"Name" => $row['role_name']);
            } else {
                $resp[] = $row['role_id'];
            }
        }
        return $resp;
    }

    function getAllPerms($format='ids')
    {
        $format = strtolower($format);
        $strSQL = "SELECT * FROM `Permissions` ORDER BY `perm_name` ASC";
        $data = mysql_query($strSQL);
        $resp = array();
        while($row = mysql_fetch_assoc($data))
        {
            if ($format == 'full')
            {
                $resp[$row['perm_key']] = array('ID' => $row['perm_id'], 'Name' => $row['perm_name'], 'Key' => $row['perm_key']);
            } else {
                $resp[] = $row['perm_id'];
            }
        }
        return $resp;
    }

    function getRolePerms($role)
    {
        if (is_array($role))
        {
                $roleSQL = "SELECT * FROM `Roles_has_Permissions` WHERE `roles_role_id` IN (" . implode(",",$role) . ") ORDER BY `id` ASC" or mysql_error();
        } else {
                $roleSQL = "SELECT * FROM `Roles_has_Permissions` WHERE `permissions_perm_id` = " . floatval($role) . " ORDER BY `id` ASC" or mysql_error();
        }
        $data = mysql_query($roleSQL);
        $perms = array();
        
        while($row = mysql_fetch_assoc($data))
        {
            $pK = strtolower($this->getPermKeyFromID($row['permissions_perm_id']));
            if ($pK == '') { continue; }
            if ($row['value'] === '1') {
                $hP = true;
            } else {
                $hP = false;
            }
            $perms[$pK] = array('perm' => $pK,'inheritted' => true,'value' => $hP,'Name' => $this->getPermNameFromID($row['permissions_perm_id']),'ID' => $row['permissions_perm_id']);
        }
        return $perms;
    }

    function getUserPerms($user_id)
    {
        $strSQL = "SELECT * FROM `Users_has_Permissions` WHERE `users_user_id` = " . floatval($user_id) . " ORDER BY `addDate` ASC";
        $data = mysql_query($strSQL);
        $perms = array();
        while($row = mysql_fetch_assoc($data))
        {
            $pK = strtolower($this->getPermKeyFromID($row['perm_id']));
            if ($pK == '') { continue; }
            if ($row['value'] == '1') {
                $hP = true;
            } else {
                $hP = false;
            }
            $perms[$pK] = array('perm' => $pK,'inheritted' => false,'value' => $hP,'Name' => $this->getPermNameFromID($row['permissions_perm_id']),'ID' => $row['permissions_perm_id']);
        }
        return $perms;
    }

    function userHasRole($role_id)
    {
        foreach($this->userRoles as $k => $v)
        {
            if (floatval($v) === floatval($role_id))
            {
                return true;
            }
        }
        return false;
    }

    function hasPermission($permKey)
    {
        $permKey = strtolower($permKey);
        if (array_key_exists($permKey,$this->perms))
        {
            if ($this->perms[$permKey]['value'] === '1' || $this->perms[$permKey]['value'] === true)
            {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function getUsername($user_id)
    {
        $strSQL = "SELECT `username` FROM `Users` WHERE `user_id` = " . floatval($user_id) . " LIMIT 1" ;
        $data = mysql_query($strSQL);
        $row = mysql_fetch_array($data);
        return $row[0];
    }
}
?>