<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
    
    // get username
    error_reporting(0);
    $raw_json = file_get_contents("http://dev.opensprites.gwiddle.co.uk/site-api/user.php?userid=" . $_GET['id']);
    if($raw_json == 'FALSE') {
        $user_exist = false;
    } else {
        $user_exist = true;
        $user = json_decode($raw_json, true);
        $username = $user['username'];
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        echo file_get_contents('../Header.html'); //Imports the metadata and information that will go in the <head> of every page
    ?>
    
    <link href='/users/user_style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <?php
        include "../navbar.php"; // Imports navigation bar
    ?>
    
    <?php if($user_exist && ($raw_json['usertype'] != "suspended" || $is_admin)) {?>
    
    <script>
	var OpenSprites = OpenSprites || {};
        OpenSprites.view = {user: <?php echo json_encode($user); ?>};
        OpenSprites.view.user.id = <?php echo json_encode($user['userid']); ?>;
        OpenSprites.view.user.name = <?php echo json_encode($user['username']); ?>;
    </script>
    
    <!-- Main wrapper -->
    <?php
        // background-image is a blurred avatar image
        echo "<div id='background-img' style='background-image:url(\"" . $user['avatar'] . "\");'></div>";
    ?>
    <div id='dark-overlay'><div id='overlay-inner'>
        <div id="user-pane-right">
            <?php if($user_exist) { ?>
            <div id='username'>
                <?php
                if($username==$logged_in_user) {echo 'You';} else {echo $username;}
                ?>
            </div>
            <div id='description'>
                <?php
                    echo ucwords($user['usertype']);
                ?>
            </div>
            <div id='follow'>
                <a href="https://scratch.mit.edu/users/<?php echo $username; ?>" target="blank">View Scratch Page</a>
            </div>
            <div id='report'>
                Report
            </div>
                <?php
					if($is_admin == true and $username !== $logged_in_user) {
						if($user['usertype'] == 'member'){
				?>
						<div id='adminban'>Suspend (Admin)</div>
                <?php
						} else if($user['usertype'] == 'suspended'){ ?>
						<div id='adminban'>Unsuspend (Admin)</div>
				<?php
						}
					} ?>
				
				
				
            <?php } else { ?>
            <div id='username'>
                User not found!
            </div>
            <?php } ?>
        </div>
        <div id="user-pane-left">
            <?php
                if($user_exist) {
                    echo '<img class="user-avatar x100" src="' . $user['avatar'] . '">';
                    if($username == $logged_in_user) {
                        echo '<div id="change-image">Change...</div>';
                    }
                }
            ?>
        </div>
    </div></div>

    <?php if($user_exist) { ?>
    <div class="container main" id="about">
        <div class="main-inner">
            <h1>About Me</h1>
            Todo.
        </div>
    </div>
    
    <div class="container main" id="collections">
        <div class="main-inner">
            <h1 class='heading'>Loading...</h1>
            <div class='content'></div>
            <script src='../user.js'></script>
        </div>
    </div>
    <?php } ?>
    
    <?php } else {?>
    <div class="container main" style='margin-top: 40px;'>
        <div class="main-inner">
            <h1 id="opensprites-heading">Our server is a little confused...</h1>
            <div id="about">
                <img src='/assets/images/404.png' style='position: absolute; margin: auto; left: 0; right: 0;'>
                <div style='width: 100%; height: 470px;'>&nbsp;</div>
                <p style='position: absolute; margin: auto; top: 480px; left: 0; right: 0; width: 50%; text-align: center; font-size: 18px;'>We couldn't find the user you're looking for.<br>You may want to <a href='/'>go back to the main page</a>.</p>
            </div>
        </div>
    </div>
    <?php }?>
    
    <script>
        $('#change-image').click(function() {
            // display modal for changing profile pic
            $('#img-modal').fadeIn(200);
        });
		
		//////////// TODO: ajax-ify. Also fix server scripts
        $('#adminban').click(function() {
            if(confirm('Are you SURE you want to suspend ' + OpenSprites.view.user.name + '?')) {
                window.location = "/users/adminban.php?type=ban&username=" + OpenSprites.view.user.name;
            }
        });
		
		$('#adminunban').click(function() {
            if(confirm('Are you SURE you want to un-suspend ' + OpenSprites.view.user.name + '?')) {
                window.location = "/users/adminban.php?type=unban&username=" + OpenSprites.view.user.name;
            }
        });
		
		
		// let's only suspend, not delete
        /*$('#admindelete').click(function() {
            if(confirm('Are you SURE you want to pernamently DELETE ' + OpenSprites.view.user.name + '!?')) {
                window.location = "/users/admindelete.php?username=" + OpenSprites.view.user.name;
            }
        });*/
    </script>
    
    <!-- footer -->
    <?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
