<?php
include '../assets/db/init.php';
include '../assets/db/class.acl.php';
include '../assets/db/user.func.php';

$myACL = new ACL();
if ($myACL->hasPermission('access_lecturer') != true)
{
	header("location: ../index.php");
} else {
    $user_data = user_data('user_id','username');
    $user_id = $_SESSION['user_id'];
    

    $staff_data = staff_data('staff_id', 'first_name', 'last_name', 'address_line1_street', 'address_line2_town', 'address_line3_county', 'address_line4', 'email', 'phone_home', 'phone_mobile', 'SystemLogin_user_id');   
    
    $changeTo = $_POST['changeTo']; // If edit button is pressed and new value is entered.
    
    $submitfirst_name = $_POST['submitfirst_name'];
    if ($submitfirst_name && $changeTo){
        $changeTo = mysql_real_escape_string($changeTo);
	mysql_query("UPDATE staff SET staff.first_name = '$changeTo' WHERE staff_id = ".$staff_data['staff_id']) or die("display_db_query:" . mysql_error());
        header('Location: account.php');
    }
    
    $submitlast_name = $_POST['submitlast_name'];
    if ($submitlast_name && $changeTo)
    {
        $changeTo = mysql_real_escape_string($changeTo);
	mysql_query("UPDATE staff SET staff.last_name = '$changeTo' WHERE staff_id = ".$staff_data['staff_id']) or die("display_db_query:" . mysql_error());
        header('Location: account.php');
    }        
      
    $submitMobileNumber = $_POST['submitHomeNumber'];
    if ($submitMobileNumber && $changeTo)
    {
        $changeTo = mysql_real_escape_string($changeTo);
	mysql_query("UPDATE staff SET staff.phone_home = '$changeTo' WHERE staff_id = ".$staff_data['staff_id']) or die("display_db_query:" . mysql_error());
        header('Location: account.php');
    }   
    
    $submitMobileNumber = $_POST['submitMobileNumber'];
    if ($submitMobileNumber && $changeTo)
    {
        $changeTo = mysql_real_escape_string($changeTo);
	mysql_query("UPDATE staff SET staff.phone_mobile = '$changeTo' WHERE staff_id = ".$staff_data['staff_id']) or die("display_db_query:" . mysql_error());
        header('Location: account.php');
    }
    
    $submitMobileNumber = $_POST['submitHomeNumber'];
    if ($submitMobileNumber && $changeTo)
    {
        $changeTo = mysql_real_escape_string($changeTo);
	mysql_query("UPDATE staff SET staff.phone_home = '$changeTo' WHERE staff_id = ".$staff_data['staff_id']) or die("display_db_query:" . mysql_error());
        header('Location: account.php');
    }    
    
    $submitEmail = $_POST['submitEmail'];
    if ($submitEmail && $changeTo)
    {
        $changeTo = mysql_real_escape_string($changeTo);
	mysql_query("UPDATE staff SET staff.email = '$changeTo' WHERE staff_id = ".$staff_data['staff_id']) or die("display_db_query:" . mysql_error());
        header('Location: account.php');
    }
       
    $submitaddress_line1 = $_POST['submitaddress_line1_street'];
    if ($submitaddress_line1 && $changeTo)
    {
        $changeTo = mysql_real_escape_string($changeTo);
	mysql_query("UPDATE staff SET staff.address_line1_street = '$changeTo' WHERE staff_id = ".$staff_data['staff_id']) or die("display_db_query:" . mysql_error());
        header('Location: account.php');
    }
    
    $submitaddress_line2 = $_POST['submitaddress_line2_town'];
    if ($submitaddress_line2 && $changeTo)
    {
        $changeTo = mysql_real_escape_string($changeTo);
	mysql_query("UPDATE staff SET staff.address_line2_town = '$changeTo' WHERE staff_id = ".$staff_data['staff_id']) or die("display_db_query:" . mysql_error());
        header('Location: account.php');
    }
    
    $submitaddress_line3 = $_POST['submitaddress_line3_county'];
    if ($submitaddress_line3 && $changeTo)
    {
        $changeTo = mysql_real_escape_string($changeTo);
	mysql_query("UPDATE staff SET staff.address_line3_county = '$changeTo' WHERE staff_id = ".$staff_data['staff_id']) or die("display_db_query:" . mysql_error());
        header('Location: account.php');
    }

    $submitaddress_line4 = $_POST['submitaddress_line4'];
    if ($submitaddress_line4 && $changeTo)
    {
        $changeTo = mysql_real_escape_string($changeTo);
	mysql_query("UPDATE staff SET staff.address_line4 = '$changeTo' WHERE staff_id = ".$staff_data['staff_id']) or die("display_db_query:" . mysql_error());
        header('Location: account.php');
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
                    <li><a href="index.php">Back to Welcome Page</a></li>
                    <li><a href="../logout.php">Logout </a></li>
                </ul>
            </nav>
	</section>
    </header>

    <section id="main_section">
    <article id="account">
        <h2>Personal Information</h2>

        <table class="Table">			
            <form action="" method='POST'>
                <tr> <td><h3>Personal Details:</h3></td></tr>
                <tr> <!-- to Change First Name -->
                    <td><span class="Bold">First Name: </span></td>
                    <td></td>
                    <td> <span class="Bold"> Change to: </span></td>
                    <td><input type='text' name='changeTo' value='<?php echo $staff_data['first_name']; ?>'></td>
                    <td><input type='submit' name='submitfirst_name' value='Update'></td>
                </tr>	
            </form>		
            
            <form action="" method='POST'>
		<tr> <!-- change last name -->
                    <td><span class="Bold">Last Name: </span></td>
                    <td></td>
                    <td> <span class="Bold"> Change to: </span> </td>
                    <td> <input type='text' name='changeTo' value='<?php echo $staff_data['last_name']; ?>'> </td>
                    <td><input type='submit' name='submitlast_name' value='Update'></td>
                </tr>
            </form>
           										
            <form action="" method='POST'>
		<tr><td></td></tr>
		<tr><td><h3>Contact Details:</h3></td></tr>
		<tr> <!-- change or add Mobile Number -->
                    <td><span class="Bold">E-mail Address: </span></td>
                    <td></td>
                    <td><span class="Bold"> Change to: </span></td>
                    <td><input type='text' name='changeTo' value='<?php echo $staff_data['email']; ?>'></td>
                    <td><input type='submit' name='submitEmail' value='Update'></td>
		</tr>
            </form>
            
            <form action="" method='POST'>
                <tr> <!-- change or add Mobile Number -->
                    <td><span class="Bold">Mobile Number: </span></td>
                    <td></td>
                    <td><span class="Bold"> Change to: </span></td>
                    <td><input type='text' name='changeTo' value='<?php echo $staff_data['phone_mobile']; ?>'></td>
                    <td><input type='submit' name='submitMobileNumber' value='Update'></td>
                </tr>
            </form>
            
            <form action="" method='POST'>
                <tr> <!-- change or add Mobile Number -->
                    <td><span class="Bold">Home Number: </span></td>
                    <td></td>
                    <td><span class="Bold"> Change to: </span></td>
                    <td><input type='text' name='changeTo' value='<?php echo $staff_data['phone_home']; ?>'></td>
                    <td><input type='submit' name='submitHomeNumber' value='Update'></td>
                </tr>
            </form>
            
            <form action="" method='POST'>
		<tr><td></td></tr>
		<tr><td><h3>Address Details:</h3></td></tr>
		<tr> <!-- change or add Mobile Number -->
                    <td><span class="Bold">Street: </span></td>
                    <td></td>
                    <td><span class="Bold"> Change to: </span></td>
                    <td><input type='text' name='changeTo' value='<?php echo $staff_data['address_line1_street']; ?>'></td>
                    <td><input type='submit' name='submitaddress_line1_street' value='Update'></td>
		</tr>
            </form>
            
             <form action="" method='POST'>
		<tr>
                    <td><span class="Bold">Town: </span></td>
                    <td></td>
                    <td><span class="Bold"> Change to: </span></td>
                    <td><input type='text' name='changeTo' value='<?php echo $staff_data['address_line2_town']; ?>'></td>
                    <td><input type='submit' name='submitaddress_line2_town' value='Update'></td>
		</tr>
            </form>           
            
            <form action="" method='POST'>
		<tr>
                    <td><span class="Bold">County: </span></td>
                    <td></td>
                    <td><span class="Bold"> Change to: </span></td>
                    <td><input type='text' name='changeTo' value='<?php echo $staff_data['address_line3_county']; ?>'></td>
                    <td><input type='submit' name='submitaddress_line3_county' value='Update'></td>
		</tr>
            </form>  
 
            <form action="" method='POST'>
		<tr>
                    <td><span class="Bold">Optional Address Line 4: </span></td>
                    <td></td>
                    <td><span class="Bold"> Change to: </span></td>
                    <td><input type='text' name='changeTo' value='<?php echo $staff_data['address_line4']; ?>'></td>
                    <td><input type='submit' name='submitaddress_line4' value='Update'></td>
		</tr>
            </form>
            
        </table>	
    </article>			
</section>
    

<?php
}
    include '../assets/template/footer.php'; ?>