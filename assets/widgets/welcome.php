<?php
if (!logged_in()){ 
?>

<aside id="side_options">
    <header><h2>Welcome</h2></header>
    <h3>Once you're registered, you can: </h3>
    <ul>
        <li><a href="">Create blog posts.</a></li>
        <li><a href="">Create photo albums.</a></li>
        <li><a href="">Update your profile</a></li>
        <li><a href="">View your classmates posts and photos</a></li>
    </ul>
</aside>

<?php
}
?>