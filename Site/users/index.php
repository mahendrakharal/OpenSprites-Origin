<?php
	require "../assets/includes/connect.php";  //Connect - includes session_start();
	require "../assets/includes/avatar.php";
	
	// capitalise username correctly
	$raw_json = file_get_contents("/site-api/user.php?userid=" . $_GET['username']);
	$username = json_decode($raw_json, true)['username'];
	$raw_json = file_get_contents("http://scratch.mit.edu/site-api/users/all/" . $username . "/");
	
	// make sure they're registered
	
	
	if(!$raw_json || empty($_GET['username']) || !$user_registered) {
		// user was not found, display error
		header("Location: /404.html");
	} else {
		// procceed
		$user_arr = json_decode($raw_json, true);
		$user = $user_arr["user"];
		$username = $user["username"];
	}
?>
<!DOCTYPE html>
<html>
<head>
	<!--Imports the metadata and information that will go in the <head> of every page-->
	<?php echo file_get_contents('../Header.html'); ?>
	
	<!--Imports styling-->
	<link href='/users/user_style.css' rel='stylesheet' type='text/css'>
</head>
<body>
	<!--Imports navigation bar-->
	<?php include "../navbar.php"; ?>
	
	<!-- Main wrapper -->
	<?php echo "<div id='background-img'></div>" ?>
	<div id='dark-overlay'><div id='overlay-inner'>
		<div id="user-pane-right">
			<div id='username'>
				<?php echo $username; ?>
			</div>
			<div id='description'>
				<?php
					echo 'blah blah blah';
				?>
			</div>
			<div id='follow'>
				View Scratch Page
			</div>
			<div id='report'>
				Report
			</div>
		</div>
		<div id="user-pane-left">
			<?php
				display_user_avatar($username, 'x100', 'client');
			?>
		</div>
	</div></div>

	<div class="container main" id="collections">
		<div class="main-inner">
			<h1>Collections</h1>
		</div>
	</div>
	
	<!-- view scratch page link :D -->
	<script>
	$('#follow').onclick({
		window.open("http://scratch.mit.edu" + window.location.pathname);
	});
	</script>
	
	<!-- footer -->
	<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
