<?php
session_start();
require_once 'core/lib/ajs.lib.php';
require_once 'core/lib/jdb.lib.php';

$echo = new AJS;
$db = new JDB;
if(isset($_POST['u'], $_POST['p'])) { 
	$g = $db->get("wave:admins");
	$u = str_replace([":",".","/"],"",$_POST['u']);
	if(isset($g->$u)) {
		if($g->$u==sha1($_POST['p'])) {
			$_SESSION['admin'] = $u;
		}
		else
		{
			$err = 1;
		}
	}
	else
	{
		$err=1;
	}
}
if(isset($_GET['logout'], $_SESSION['admin'])) {
	unset($_SESSION['admin']);
}
if(isset($_SESSION['admin'])) {
?>
<!DOCTYPE html>
<html lang="fr" ng-app="Wave">
    <head>
        <meta charset="utf-8" />
        <title>Wave CMS Admin : <?php echo $db->get("wave:site_info:name"); ?> / Agencys</title>

        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
        <!-- Default CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/admin_style.css">
        <link rel="stylesheet" href="css/summernote.css">
        <!-- -->   
        <?php
        $init_css = $db->get("wave:init_css_admin");
        if($init_css) {
            foreach($init_css as $l) {
                echo '<link rel="stylesheet" href="'.htmlspecialchars(str_replace("://","", $l))."\"/>\n";
            }
        }
        ?>

        <!-- Default Scripts -->
        <script src="js/angular.js"></script>
        <script src="js/angular-ui-router.js"></script>
        <script src="js/angular-sanitize.js"></script>       
        <script src="js/controllers.js.php?admin"></script>
        <script src="js/services.js.php?admin"></script>
        <script src="js/directives.js.php?admin"></script>
        <script src="js/app.js.php?admin"></script>
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/summernote.js"></script>
        <!-- -->
        <?php
        $init_script = $db->get("wave:init_script_admin");
        if($init_script) {
            foreach($init_script as $l) {
                echo '<script src="'.htmlspecialchars(str_replace("://","", $l))."\"></script>\n";
            }
        }
        ?>
    </head>
    <body>
<div id="container">
    <div id="header">
        <div id="header-1"><?php 
        $site_info = $db->get("wave:site_info");
        if($site_info) {
            echo "<h1>".$site_info->name." Administration</h1>".$site_info->subname;
        }
         ?></div>
        <div id="header-2"></div>
        <div id="header-3"></div>
        <div id="header-4"></div>
        <div id="header-5">
            <ul id="main-menu" class="nav nav-pills">

            </ul>
        </div>
    </div>

    <div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
            <li ui-sref-active='active'><a ui-sref='main'>Home</a></li>
            <li ui-sref-active='active'><a ui-sref='articles'>Articles</a></li>
            <li ui-sref-active='active'><a ui-sref='pages'>Pages</a></li>
        <?php if(!preg_match("#^\-\-#", $_SESSION['admin'])) { ?>
            <li ui-sref-active='active'><a ui-sref='params'>Settings</a></li>
        <?php
        $main_menu = $db->get("wave:main_menu_admin");
        if($main_menu) {
            foreach($main_menu as $k=>$l) {
                echo "<li ui-sref-active='active'><a ui-sref='$k'>$l</a></li>\n";
            }
        }
    }
        ?>
        <li ui-sref-active='active'><a ui-sref='user'><?php echo $_SESSION['admin']; ?></a></li>
        <li><a href="?logout">Log out</a></li>
            </ul>
        </div>
        <div class="col-md-10">
                <div ui-view id="view">

                </div>
        </div>
    </div>
</div>

	<footer>Wave CMS by <a href="http://benjaminrathelot.com/">Benjamin Rathelot</a></footer>

    </body>
</html>
<?php
}
else
{
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Wave CMS : Administration Log In</title>
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/admin_login.css" />
	</head>
<body>
<div id="fullscreen_bg" class="fullscreen_bg"/>

<div class="container">
<?php if(isset($err)) { ?><center style="color:white">Invalid credentials.</center><br /><?php } ?>

	<form class="form-signin" action="?" method="post">
		<h1 class="form-signin-heading text-muted">Administration</h1><br />
		<input type="text" name="u" class="form-control" placeholder="Username" required="true" autofocus=""><br />
		<input type="password" name="p" class="form-control" placeholder="Passwird" required="true"><br />
		<button class="btn btn-lg btn-primary btn-block" type="submit">
			Log In
		</button><br />
		<center><a href="./">Back to site</a></center>
	</form>

</div>
<br />
<footer>Wave CMS by <a href="http://benjaminrathelot.com/">Benjamin Rathelot</a></footer>
</body>
</html>
<?php
}
?>
