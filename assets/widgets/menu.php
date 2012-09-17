<?php

if (!logged_in()){
    
?>
    <?php
} else {
    
    $myACL = new ACL();
    if ($myACL->hasPermission('access_system_admin') == true)
    {
        echo '
        <h1>Main Navigation</h1>
        <ul = "sidebar_content">
            <li><a href="account.php/">My Details</a></li>
            <li><a href="sys_admin/">System Admin</a></li>
            <li><a href="logout.php">Logout </a></li>
        </ul>
        ';
    }
    
    if ($myACL->hasPermission('access_admin') == true)
    {
        echo '
        <h1>Main Navigation</h1>
        <ul = "sidebar_content">
            <li><a href="admin/">Admin Screen</a></li>
            <li><a href="logout.php">Logout </a></li>
        </ul>
        ';
    }  
    
    else if ($myACL->hasPermission('access_lecturer') == true)
    {
        echo '
        <h1>Main Navigation</h1>
        <ul = "sidebar_content">
            <li><a href="lecturer/">Lecturer Screen</a></li>
            <li><a href="logout.php">Logout </a></li>
        </ul>
        ';
    }
    
    else if ($myACL->hasPermission('access_student') == true)
    {
        echo '
        <h1>Main Navigation</h1>
        <ul = "sidebar_content">
            <li><a href="student/">Student Screen</a></li>
            <li><a href="logout.php">Logout </a></li>
        </ul>
        ';
    }
    
}
?>
