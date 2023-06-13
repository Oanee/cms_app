<?php

if (isset($_POST['create_user'])) {

	$user_firstname = $_POST['user_firstname'];
	$user_lastname = $_POST['user_lastname'];
	$user_role = $_POST['user_role'];
	$username = $_POST['username'];
	$user_email = $_POST['user_email'];
	$user_password = $_POST['user_password'];
//	$user_image = $_POST['user_image'];

//	move_uploaded_file($user_image_temp, "../images/$user_image");

	$query = "INSERT INTO users(username, user_password, user_firstname, user_lastname, user_email, user_role) ";

	$query .= "VALUES('{$username}', '{$user_password}', '{$user_firstname}', '{$user_lastname}' ,'{$user_email}', '{$user_role}') ";

	$create_user_query = mysqli_query($connection, $query);

	confirm($create_user_query);

	echo "User created: ". " ". "<a href='users.php'>View Users</a>" ;

}

?>



<form action='' method='post' enctype='multipart/form-data'>
	<div class='form-group'>
		<label for='user_firstname'>Firstname</label>
		<input type='text' class='form-control' name='user_firstname' id='user_firstname'>
	</div>

	<div class='form-group'>
		<label for='user_lastname'>Lastname</label>
		<input type='text' class='form-control' name='user_lastname' id='user_lastname'>
	</div>

	<div class='form-group'>
		<select name='user_role' id='user_role'>
			<option value='subscriber'>Select option</option>
			<option value='admin'>Admin</option>
			<option value='subscriber'>Subscriber</option>
		</select>
	</div>

<!--	<div class='form-group'>-->
<!--		<label for='userimage'>Image</label>-->
<!--		<input type='file' class='form-control' name='image' id='userimage'>-->
<!--	</div>-->

	<div class='form-group'>
		<label for='username'>Username</label>
		<input type='text' class='form-control' name='username' id='username'>
	</div>

	<div class='form-group'>
		<label for='user_email'>Email</label>
		<input type='email' class='form-control' name='user_email' id='user_email'>
	</div>

	<div class='form-group'>
		<label for='user_password'>Password</label>
		<input type='password' class='form-control' name='user_password' id='user_password'>
	</div>

	<div class='from_group'>
		<input class='btn btn-primary' type='submit' name='create_user' value='Add user'>
	</div>
</form>
