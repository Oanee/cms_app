<?php

if (isset($_GET['edit_user'])) {
	$the_user_id = $_GET['edit_user'];

	$query = "SELECT * FROM users WHERE user_id = {$the_user_id}";
	$select_post_by_id = mysqli_query($connection, $query);

	while ($row = mysqli_fetch_assoc($select_post_by_id)) {
		$user_firstname = $row['user_firstname'];
		$user_lastname = $row['user_lastname'];
		$user_role = $row['user_role'];
		$username = $row['username'];
		$user_email = $row['user_email'];
		$user_password = $row['user_password'];
	}
}

if (isset($_POST['edit_user'])) {
	$user_firstname = $_POST['user_firstname'];
	$user_lastname = $_POST['user_lastname'];
	$user_role = $_POST['user_role'];
	$username = $_POST['username'];
	$user_email = $_POST['user_email'];
	$user_password = $_POST['user_password'];

	$query = "
	UPDATE users SET 
		username = '{$username}', 
		user_password = '{$user_password}', 
		user_firstname = '{$user_firstname}', 
		user_lastname = '{$user_lastname}', 
		user_email = '{$user_email}', 
		user_role = '{$user_role}'
	WHERE user_id = '{$the_user_id}'";

	$edit_user = mysqli_query($connection, $query);

	confirm($edit_user);

}

?>

<form action='' method='post' enctype='multipart/form-data'>
	<div class='form-group'>
		<label for='user_firstname'>Firstname</label>
		<input
			type='text' class='form-control' name='user_firstname' id='user_firstname'
			value='<?php echo $user_firstname ?>'>
	</div>

	<div class='form-group'>
		<label for='user_lastname'>Lastname</label>
		<input
			type='text' class='form-control' name='user_lastname' id='user_lastname'
			value='<?php echo $user_lastname ?>'>
	</div>

	<div class='form-group'>
		<select name='user_role' id='user_role'>
			<option value='<?php echo $user_role ?>'><?php echo $user_role ?></option>

			<?php

			if ($user_role == 'admin') {
				echo "<option value='subscriber'>Subscriber</option>";
			} else {
				echo "<option value='admin'>Admin</option>";
			}

			?>

		</select>
	</div>

	<!--	<div class='form-group'>-->
	<!--		<label for='userimage'>Image</label>-->
	<!--		<input type='file' class='form-control' name='image' id='userimage'>-->
	<!--	</div>-->

	<div class='form-group'>
		<label for='username'>Username</label>
		<input type='text' class='form-control' name='username' id='username' value='<?php echo $username ?>'>
	</div>

	<div class='form-group'>
		<label for='user_email'>Email</label>
		<input type='email' class='form-control' name='user_email' id='user_email' value='<?php echo $user_email ?>'>
	</div>

	<div class='form-group'>
		<label for='user_password'>Password</label>
		<input
			type='password' class='form-control' name='user_password' id='user_password'
			value='<?php echo $user_password ?>'>
	</div>

	<div class='from_group'>
		<input class='btn btn-primary' type='submit' name='edit_user' value='Update user'>
	</div>
</form>
