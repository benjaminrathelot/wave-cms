angular.module('Wave.directives', [])

<?php
require_once '../core/lib/jdb.lib.php';
$db = new JDB("../JsonDB/");
if(isset($_GET['admin'], $_SESSION['admin'])) {
	$g = $db->get("wave:directives_admin");
}
else
{
	$g = $db->get("wave:directives");
}
if($g) {
	foreach($g as $url) {
		echo file_get_contents("../".str_replace("://","",$url));
	}
}
?>

;