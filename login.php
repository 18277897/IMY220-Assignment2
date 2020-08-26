<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Nicole Lou">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form action='login.php' method='post' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />

									<input type='hidden' name='loginEmail' value='".$row['email']."'/>
									<input type='hidden' name='loginPass' value='".$pass."'/>
								</div>
						  	</form>";

				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}

			echo "<h1>Image Gallery</h2>";
			if (isset($_POST["submit"]))
			{
				//check file requirements
				$allowed = array('jpg','jpeg','JPEG','JPG');
				$uploadFile = $_FILES['picToUpload']['name'];
				$size = $_FILES['picToUpload']['size'];
				$ext = pathinfo($uploadFile, PATHINFO_EXTENSION);
				if (!(in_array($ext, $allowed)) || $size > 1048576)
				{
					//if file doesn't meet requirements
					echo "Invalid File.";
				}
				else
				{
					//if file meets requirements
					$temp = $_FILES['picToUpload']['tmp_name'];
					$dest = 'gallery/'.$uploadFile;
					move_uploaded_file($temp, $dest);

					$query = "INSERT INTO tbgallery (user_id, filename) VALUES ('".$row['user_id']."', '$uploadFile');";

					$res = mysqli_query($mysqli, $query) == TRUE;
				}
			}

			//get all filenames from database to display
			$allfn = "SELECT filename, user_id FROM tbgallery WHERE user_id='".$row['user_id']."'";
			$results = $mysqli->query($allfn);

			if ($results->num_rows > 0)
			{
				echo "<div class='row imageGallery'>";
				while ($row = $results->fetch_assoc())
				{
					echo "<div class='col-3' style='background-image: url(gallery/" .$row['filename'] . ")'></div>";
				}
				echo "</div>";
			}

		?>
	</div>
</body>
</html>