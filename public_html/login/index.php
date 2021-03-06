<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
    if($logged_in_user !== 'not logged in') {
        header('Location: /');
    }
	
	$return = "//" . $_SERVER['HTTP_HOST'] . (isset($_GET['return']) ? $_GET['return'] : '/');
	et_regenerateToken();
	$token = $_SESSION['token'];
?>
<!DOCTYPE html>
<html>
<head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
    <?php include '../Header.html'; ?>
	<script>
		OpenSprites.view = <?php echo json_encode(array("return" => $return, "token" => $token)); ?>;
	</script>
</head>
<body>
    <!--Imports site-wide main styling-->
    <link href='/main-style.css' rel='stylesheet' type='text/css'>
    
    <!--Imports navigation bar-->
    <?php include "../navbar.php"; ?>
    
    <!-- Main wrapper -->
    <div class="container main" style="height:440px;background:transparent;box-shadow:none !important;">
        <div class="main-inner">
            <form enctype="multipart/form-data" id="login-form">
				<p id="login-message"></p>
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				
				<div class="login-box depth-1">
					<div class="sprite-login">Log In</div>
					<div class="form-box">
						<label for="username" class="sprite-username">Username <a class='small' href='/register' tabindex='4'>Create Account</a></label>
						<input type="text" class="login-field" id="username" tabindex='1' autofocus />
						<label for="password" class="sprite-password">Password <a class='small' href='/forums/?p=user/forgot' tabindex='5'>Forgot Password?</a></label>
						<input type="password" class="login-field" id="password" tabindex='2' />
						<button type='submit' class="btn" tabindex="3">Log in</button>
					</div>
				</div>
				<br style="break:both" />
			</form>
        </div>
    </div>
	
	<script>
		var allowed = true;
		var waitSecs = 0;
		var interval = 0;
	
		$("#login-form").on('submit', function(e){
			e.preventDefault();
			if(!allowed) return false;
			$.post("/site-api/login.php", {token: OpenSprites.view.token, username: $("#username").val(), password: $("#password").val()}, function(data){
				if(data.status == "success"){
					location.href = OpenSprites.view.return;
				} else {
					$("#login-message").text(data.message);
					if(data.hasOwnProperty("wait")){
						allowed = false;
						clearInterval(interval);
						waitSecs = data.wait;
						interval = setInterval(function(){
							waitSecs--;
							$("#login-message").text("Wrong username or password, wait " + waitSecs + " seconds before trying again");
							if(waitSecs <= 0){
								$("#login-message").text("Wrong username or password");
								allowed = true;
								clearInterval(interval);
							}
						}, 1000);
					}
				}
			}).fail(function(){
				$("#login-message").text("Whoops! We couldn't get a response from OpenSprites servers. Try again later.");
			});
			return false;
		});
	</script>
    
    <!-- footer -->
    <?php include "../footer.html"; ?>
	<script>
		$('head').append('<link href="login-' + OpenSprites.theme.toLowerCase() + '.css" rel="stylesheet" type="text/css" />');
	</script>
</body>
</html>
