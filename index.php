<?php
session_start();
require_once 'core/lib/ajs.lib.php';
require_once 'core/lib/jdb.lib.php';

$echo = new AJS;
$db = new JDB;
?>
<!DOCTYPE html>
<html lang="fr" ng-app="Wave">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $db->get("wave:site_info:name")." — ".$db->get("wave:site_info:subname"); ?></title>
        <meta name="description" content="<?php echo $db->get("wave:site_info:description"); ?>">
        <meta name="keywords" content="<?php echo $db->get("wave:site_info:keywords"); ?>">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
        <meta name="generator" content="Wave CMS 1.0" /><!-- http://benjaminrathelot.com/ ; https://github.com/benjaminrathelot/wave-cms -->
        <!-- Default CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/summernote.css">
        <!-- -->   
        <?php
        $init_css = (Array)$db->get("wave:init_css");
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
        <script src="js/controllers.js.php"></script>
        <script src="js/services.js.php"></script>
        <script src="js/directives.js.php"></script>
        <script src="js/app.js.php"></script>
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
        <!-- -->
        <?php
        $init_script = (Array)$db->get("wave:init_script");
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
            echo "<h1>".$site_info->name."</h1>".$site_info->subname;
        }
         ?></div>
        <div id="header-2"></div>
        <div id="header-3"></div>
        <div id="header-4"></div>
        <div id="header-5">
            <ul id="main-menu" class="nav nav-pills">
        <?php
        $main_menu = (Array)$db->get("wave:main_menu");
        if($main_menu) {
            foreach($main_menu as $k=>$l) {
                echo "<li><a ui-sref-active='active-link' ui-sref='$k'>$l</a></li>\n";
            }
        }
        ?>
            </ul>
        </div>
    </div>
<div class="container-fluid">
<div class="row">
    <div id="view" ui-view class="col-md-9">
    
    </div>
    <div id="menu" class="col-md-3">
    <div class="container" ng-include="'templates/menu.html'">

    </div>
        <?php
        $menu_items = (Array)$db->get("wave:menu_items");
        if($menu_items) {
            foreach($menu_items as $l) {
                if(file_exists($l)) {
                    echo file_get_contents($l);
                }
            }
        }
        ?>

    </div>
</div>
</div>
<div class="container" ng-include="'templates/bottom.html'">

</div>
    <footer>Powered by Wave CMS by <a href="http://benjaminrathelot.com/">Benjamin Rathelot</a></footer>
    </body>
</html>
