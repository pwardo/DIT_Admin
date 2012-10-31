<?php
if (logged_in()){
    $user_data = user_data('user_id','username');
    echo 'Hello ', $user_data['username']; // , ' ', $user_data['email'];
} else if (!logged_in()){
    
    ?>
    <section id="main_section">
        <article class="transparent" id="blog_album_page">
            <h2>Log in</h2>
            
            <form action="" method="POST" >
	
            <table class="Table">
                <tr>
                    <td><span class="Bold">User ID: </span></td>
                    <td><input type ='text' name='login_username'></td>
		</tr>
		<tr>
                    <td><span class="Bold">Password: </span></td>
                    <td><input type ='password' name='login_password'></td>
                </tr>
		<tr>
                    <td><input type='submit' name='submit' value='Log in'></td>
                </tr>
                <tr>
                    <td><h2><br/></h2></td>
            </table>
                
            </form>
            <?php
            
            if (isset ($_POST['login_username'], $_POST['login_password'])){
                // store studentID and password strings that were input.
                $login_username = $_POST['login_username'];
                $login_password = $_POST['login_password'];

                $errors = array();

                // check if either field is empty, if either one is empty add string to errors array.
                if (empty ($login_username) || empty ($login_password)){
                    $errors[] = 'Student ID and password required';
                } else {
                    // if both fields filled in, send info to user.func.php - login_check.
                    $login = login_check($login_username, $login_password);

                    if ($login === false){
                        $errors[] = '<br/><h3 align="right">Unable to log you in</h3>';
                    }
                }

                if(!empty($errors)){
                    foreach ($errors as $error){
                        echo $error, '<br />';
                    }

                } else {
                    // log user in if no errors.
                    $_SESSION['user_id'] = $login;
                    // $login contains the user_id returned from user.func login_check

                    header('Location: index.php');
                    exit;
                }
            }            
            ?>
	</article>
    </section>
    <?php

}
?>