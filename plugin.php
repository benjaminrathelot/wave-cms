<?php
session_start();
require_once 'core/lib/ajs.lib.php';
require_once 'core/lib/jdb.lib.php';
require_once 'core/lib/pu.lib.php';


$echo = new AJS;
$db = new JDB;
$_POST = json_decode(file_get_contents('php://input'),true);

if(isset($_SESSION['admin'])) {
	//Admin features

	if(isset($_GET['admin']) AND file_exists("plugins/".str_replace(['/', '.', ':', '$'], "", $_GET['admin'])."/admin.php")) {
		$admin = 1;
		include("plugins/".str_replace(['/', '.', ':', '$'], "", $_GET['admin'])."/admin.php");
		exit;
	}

	if(isset($_GET['list'])) {
		$plugs = glob("plugins/*",GLOB_ONLYDIR);
		$r = [];
		foreach($plugs as $dir) {
			if(file_exists($dir."/plugin.json")) {
				$g = file_get_contents($dir."/plugin.json");
				if($g) {
					$r[] = json_decode($g);
					if(!file_exists($dir."/installed")) {
						include($dir."/install.php");
					}
				}
			}
		}
		$echo->data($r);
	exit;
	}

}
else
{
	//User features
	if(isset($_GET['module']) AND file_exists("plugins/".str_replace(['/', '.', ':', '$'], "", $_GET['module'])."/module.php")) {
		include("plugins/".str_replace(['/', '.', ':', '$'], "", $_GET['module'])."/module.php");
		exit;
	}
}