<?php include 'includes/admin_header.php' ?>

<div id="wrapper">

	<!-- Navigation -->

	<?php include 'includes/admin_navigation.php' ?>

	<div id="page-wrapper">

		<div class="container-fluid">

			<!-- Page Heading -->
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">
						Welcome to admin
						<small>Author</small>
					</h1>

					<div class='col-xs-6'>

						<?php

						if (isset($_POST['submit'])) {

							$category_title = $_POST['category_title'];

							if ($category_title == '' || empty($category_title)) {
								echo 'This field should not be empty';
							} else {

								$query = "INSERT INTO category(category_title) ";
								$query .= "VALUE('{$category_title}') ";

								$create_category_query = mysqli_query($connection, $query);

								if (!$create_category_query) {
									die('Query failed' . mysqli_error($connection));
								}
							}

						}

						?>

						<form action='' method='post'>
							<div class='form-group'>
								<label for='cat'>Add Category</label>
								<input class='form-control' type='text' name='category_title' id='cat'>
							</div>
							<div class='form-group'>
								<input class='btn btn-primary' type='submit' name='submit' value='Add Category'>
							</div>

						</form>

						<form action='' method='post'>
							<div class='form-group'>
								<label for='cat'>Edit Category</label>

								<?php

								if (isset($_GET['edit'])) {
									$category_id = $_GET['edit'];

									$query = "SELECT * FROM category WHERE category_id = '{$category_id}'";
									$select_categories_id = mysqli_query($connection, $query);

									while ($row = mysqli_fetch_assoc($select_categories_id)) {
										$category_id = $row['category_id'];
										$category_title = $row['category_title'];

										?>

										<input
											value='<?php if (isset($category_title)) {
												echo $category_title; } ?>' class='form-control' type='text'
											name='category_title' id='cat'>

									<?php }
								} ?>

							</div>
							<div class='form-group'>
								<input class='btn btn-primary' type='submit' name='submit' value='Edit Category'>
							</div>

						</form>
					</div>

					<div class='col-lg-6'>
						<table class='table table-bordered table-hover'>
							<thead>
							<tr>
								<th>Id</th>
								<th>Category Title</th>
							</tr>
							</thead>

							<tbody>

							<?php

							$query = 'SELECT * FROM category';
							$select_categories = mysqli_query($connection, $query);

							while ($row = mysqli_fetch_assoc($select_categories)) {
								$category_id = $row['category_id'];
								$category_title = $row['category_title'];

								echo "<tr>";
								echo "<td>{$category_id}</td>";
								echo "<td>{$category_title}</td>";
								echo "<td><a href='categories.php?delete={$category_id}'>Delete</a></td>";
								echo "<td><a href='categories.php?edit={$category_id}'>Edit</a></td>";
								echo "</tr>";

							}

							?>

							<?php

							if (isset($_GET['delete'])) {
								$the_category_id = $_GET['delete'];

								$query = "DELETE FROM category WHERE category_id = '{$the_category_id}'";
								$delete_query = mysqli_query($connection, $query);
								header('Location: categories.php');
							}

							?>

							</tbody>
						</table>
					</div>

				</div>
			</div>
			<!-- /.row -->

		</div>
		<!-- /.container-fluid -->

	</div>
	<!-- /#page-wrapper -->

	<?php include 'includes/admin_footer.php' ?>
