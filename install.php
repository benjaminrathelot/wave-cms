<?php
if(isset($_POST['setup']) && !file_exists("JsonDB/wave")) {
require_once 'core/lib/jdb.lib.php';
@mkdir("JsonDB/");
@mkdir("JsonDB/articles");
@mkdir("JsonDB/categories");
@mkdir("JsonDB/wave");
$db = new JDB;
$db->insert("wave:admins", array("-admin"=>sha1("wave"), "--blogger"=>sha1("wave")));
$db->insert("wave:init_css", (object) null);
$db->insert("wave:init_script", (object) null);
$db->insert("wave:main_menu", array("main"=>"Home", "test"=>"Test Page"));
$db->insert("wave:init_css_admin", (object) null);
$db->insert("wave:init_script_admin", (object) null);
$db->insert("wave:main_menu_admin", (object) null);
$db->insert("wave:routes_admin", (object) null);
$db->insert("wave:routes", array("test"=>["url"=>"/test", "templateUrl"=>"templates/test.html", "controller"=>"TestCtrl"],
"article"=>["url"=>"/article/:id", "templateUrl"=>"templates/article.html", "controller"=>"ArticleCtrl"]
	));
$db->insert("wave:controllers", array("core/inc/test.controller.js"));
$db->insert("wave:site_info", array("name"=>"Wave CMS", "subname"=>"Agencys CMS v1", "description"=>"This site uses Agencys Wave CMS v1.", "keywords"=>"agencys, wave, cms"));
$db->insert("categories:default", [
	"name"=>"Default Category",
	"id"=>"default",
	"content"=>[]
	]);
$db->insert("articles:test_article", [
	"title" => "Test article",
	"id" => "test_article",
	"date" => time(),
	"content" => "<b>This is a test article!</b>",
	"pic_name" => "",
	"author" => "admin"
	]);
$db->insert("wave:menu_items", (object) null);
$db->insert("wave:services", (object) null);
$db->insert("wave:directives", (object) null);
$db->insert("wave:controllers_admin", (object) null);
$db->insert("wave:services_admin", (object) null);
$db->insert("wave:directives_admin", (object) null);

echo "The Wave CMS is ready. ";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Wave CMS Setup Script</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" media="screen" type="text/css" href="css/bootstrap.min.css" />
		<style>
		/*  * Globals  */  /* Links */ a, a:focus, a:hover {   color: #fff; }  /* Custom default button */ .btn-default, .btn-default:hover, .btn-default:focus {   color: #333;   text-shadow: none; /* Prevent inheritence from `body` */   background-color: #fff;   border: 1px solid #fff; }   /*  * Base structure  */  html, body {   height: 100%;   background-color: #333; } body {   color: #fff;   text-align: center;   text-shadow: 0 1px 3px rgba(0,0,0,.5); }  /* Extra markup and styles for table-esque vertical and horizontal centering */ .site-wrapper {   display: table;   width: 100%;   height: 100%; /* For at least Firefox */   min-height: 100%;   -webkit-box-shadow: inset 0 0 100px rgba(0,0,0,.5);           box-shadow: inset 0 0 100px rgba(0,0,0,.5); } .site-wrapper-inner {   display: table-cell;   vertical-align: top; } .cover-container {   margin-right: auto;   margin-left: auto; }  /* Padding for spacing */ .inner {   padding: 30px; }   /*  * Header  */ .masthead-brand {   margin-top: 10px;   margin-bottom: 10px; }  .masthead-nav > li {   display: inline-block; } .masthead-nav > li + li {   margin-left: 20px; } .masthead-nav > li > a {   padding-right: 0;   padding-left: 0;   font-size: 16px;   font-weight: bold;   color: #fff; /* IE8 proofing */   color: rgba(255,255,255,.75);   border-bottom: 2px solid transparent; } .masthead-nav > li > a:hover, .masthead-nav > li > a:focus {   background-color: transparent;   border-bottom-color: #a9a9a9;   border-bottom-color: rgba(255,255,255,.25); } .masthead-nav > .active > a, .masthead-nav > .active > a:hover, .masthead-nav > .active > a:focus {   color: #fff;   border-bottom-color: #fff; }  @media (min-width: 768px) {   .masthead-brand {     float: left;   }   .masthead-nav {     float: right;   } }   /*  * Cover  */  .cover {   padding: 0 20px; } .cover .btn-lg {   padding: 10px 20px;   font-weight: bold; }   /*  * Footer  */  .mastfoot {   color: #999; /* IE8 proofing */   color: rgba(255,255,255,.5); }   /*  * Affix and center  */  @media (min-width: 768px) {   /* Pull out the header and footer */   .masthead {     position: fixed;     top: 0;   }   .mastfoot {     position: fixed;     bottom: 0;   }   /* Start the vertical centering */   .site-wrapper-inner {     vertical-align: middle;   }   /* Handle the widths */   .masthead,   .mastfoot,   .cover-container {     width: 100%; /* Must be percentage or pixels for horizontal alignment */   } }  @media (min-width: 992px) {   .masthead,   .mastfoot,   .cover-container {     width: 700px;   } } 
		</style>
	</head>
	<body>
	    <div class="site-wrapper">
		      <div class="site-wrapper-inner">
		        <div class="cover-container">
		          <div class="inner cover">
		            <h1 class="cover-heading">Wave CMS Setup</h1>
		            <p class="lead">Click on the button below to install Wave CMS.</p>
		            <p class="lead">
		              Default login : -admin<br />
		              Default password : wave
		            </p>
		            <p class="lead">
		            	<form action="?" method="post">
							<input type="submit" name="setup" value="Install" class="btn btn-primary"/>
						</form>
		            </p>
		          </div>
		        </div>
		      </div>
	    </div>
	</body>
</html>
