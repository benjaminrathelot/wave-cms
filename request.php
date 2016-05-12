<?php
session_start();
require_once 'core/lib/ajs.lib.php';
require_once 'core/lib/jdb.lib.php';

$echo = new AJS;
$db = new JDB;
$_POST = json_decode(file_get_contents('php://input'),true);
//Get data
if(isset($_GET['get'], $_GET['rq'], $_GET['where'], $_GET['order'], $_GET['limit']) AND is_numeric($_GET['order']) AND is_numeric($_GET['limit'])) {
	if((preg_match("#^wave#", $_GET['rq']) OR preg_match("#^admin_#", $_GET['rq']) OR preg_match("#^set_only#", $_GET['rq'])) AND !isset($_SESSION['admin'])) {
		$echo->error("Admin only feature.");
		exit;
	}
	$data = $db->get($_GET['rq']);
	if($data) { 
		if(is_array($data)) {
			if($_GET['where']!="" AND $_GET['where']!="null") {
				$where = json_decode(urldecode($_GET['where']), 1);
				if(!$where) {
					$where = ["invalid-request"=>1];
				}
			}
			else
			{
				$where = [];
			}
			$results = [];
			$i = 0;
			if($_GET['limit']==0) {
				$nolimit=1;
			}
			else
			{
				$nolimit=0;
			}
			foreach($data as $k=>$line) { 
				if(!$nolimit AND $i>=$_GET['limit'])
				{

				}
				else
				{

					$err = 0;
					foreach($where as $k=>$v) {
						if(isset($line[$k])) {
							if($line[$k]!=$v) {
								$err++;
							}
						}
						else
						{
							$err++;
						}
					}
					if($err==0) {
						$results[$k] = $line;
					}
					$i++;
				}
			}	
			if($_GET['order']==-1){
				$results = array_reverse ($results);
			}
			$echo->data($results);
		
		}
		else
		{
			if($_GET['where']!="" AND $_GET['where']!="null") {
				$where = json_decode(urldecode($_GET['where']), 1);
				if(!$where) {
					$where = ["invalid-request"=>1];
				}
			}
			else
			{
				$where = [];
			}
			$dx = (array) $data;
			$err =0 ;
			foreach($where as $k=>$v) {
				if(isset($dx[$k])) {
					if($dx[$k]!=$v) {
						$err++;
					}
				}
				else
				{
					$err++;
				}
			}
			if($err==0) {
				$echo->data($data);
			}
			else
			{
				$echo->error("No data.");
			}
		}
	}
	else
	{
		$echo->error("No data found.");
	}

	exit;
}

// Set data
if(isset($_POST['set'], $_GET['rq'], $_GET['where'])) {
	if(!preg_match("#^public#", $_GET['rq']) AND !preg_match("#^set_only#", $_GET['rq']) AND !isset($_SESSION['admin'])) {
		$echo->error("Admin only feature.");
		exit;
	}
	$data = $db->get(str_replace(".","", str_replace("/","",$_GET['rq'])));
	if($data) {
		$set = json_decode(urldecode($_POST['set']), 1);
		if(!$set) {
			$set = ["invalid-request"=>1];
		}

		if(is_array($data)) {
			if($_GET['where']!="" AND $_GET['where']!="null") {
				$where = json_decode(urldecode($_GET['where']), 1);
				if(!$where) {
					$where = ["invalid-request"=>1];
				}
			}
			else
			{
				$where = [];
			}
			$results = [];

			foreach($data as $k=>$line) { 
					$err = 0;
					foreach($where as $k=>$v) {
						if(isset($line[$k])) {
							if($line[$k]!=$v) {
								$err++;
							}
						}
						else
						{
							$err++;
						}
					}
					if($err==0) {
						$new = array_merge($line, $set);
						$db->update(str_replace(".","", str_replace("/","",$_GET['rq'])).":".$k, $new);
					}

					$i++;
				}

			$echo->success("Update ok.");
		
		}
		else
		{
			if($_GET['where']!="" AND $_GET['where']!="null") {
				$where = json_decode(urldecode($_GET['where']), 1);
				if(!$where) {
					$where = ["invalid-request"=>1];
				}
			}
			else
			{
				$where = [];
			}
			$dx = (array) $data;
			$err =0 ;
			foreach($where as $k=>$v) {
				if(isset($dx[$k])) {
					if($dx[$k]!=$v) {
						$err++;
					}
				}
				else
				{
					$err++;
				}
			}
			if($err==0) {
				$obj =  (array) $data;
				$new = array_merge($obj, $set);
				$db->update(str_replace(".","", str_replace("/","",$_GET['rq'])),$new);
				$echo->success("Update ok.");
			}
			else
			{
				$echo->error("No data.");
			}
		}
	}
	else
	{
		$echo->error("No data found.");
	}

	exit;
}
//Insert

if(isset($_GET['insert'], $_GET['rq'], $_POST['data'])) {
	if(!preg_match("#^public#", $_GET['rq']) AND !preg_match("#^set_only#", $_GET['rq']) AND !isset($_SESSION['admin'])) {
		$echo->error("Admin only feature.");
		exit;
	}
	if($_POST['data']!="" AND $_POST['data']!="null") {
		$insert = json_decode(urldecode($_POST['data']), 1);
		if(!$insert) {
			$insert = ["invalid-request"=>1];
			$echo->error("No data?");
			exit;
		}
		else
		{
			if(!is_array($insert)) {
				$k = json_decode($insert,1);
			}
			else
			{
				$k = $insert;
			}
			$db->insert($_GET['rq'], $k);
			$echo->ok("Data saved.");
		}
	}
	else
	{
		$echo->error("No data?");
		exit;
	}

	exit;
}

//Delete
if(isset($_GET['delete'], $_GET['rq'])) {
	if(!preg_match("#^public#", $_GET['rq']) AND !isset($_SESSION['admin'])) {
		$echo->error("Admin only feature.");
		exit;
	}

	$db->delete($_GET['rq']);
	$echo->ok("Deleted.");
	exit;
}

//EditTemplate
if(isset($_GET['editTemplate'], $_POST['content'])) {
	if(!isset($_SESSION['admin'])) {
		$echo->error("Admin only feature.");
		exit;
	}
	$template = str_replace(["/",".",":","$","~",""],"", $_GET['editTemplate']);
	if(!file_exists("templates/$template.html")) {
		$arr = (array) $db->get("wave:routes");
		$route = array_merge($arr, array($template=>["url"=>"/$template", "templateUrl"=>"templates/$template.html", "controller"=>"DefaultCtrl"]));
		$db->insert("wave:routes", $route);
	}
	file_put_contents("templates/$template.html", urldecode($_POST['content']));
	$echo->ok("Page saved.");
}

//GetTemplates
if(isset($_GET['getTemplates'], $_SESSION['admin'])) {
	$get = glob("templates/*.html");
	$r = [];
	foreach($get as $file) {
		$file = str_replace(["templates/",".html"],"",$file);
		$r[] = ["id"=>$file, "content"=>file_get_contents("templates/$file.html")];
	}
	if(count($r)>0) {
		$echo->data($r);
	}
	else
	{
		$echo->error("No Page.");
	}
}

//DeleteTemplate
if(isset($_GET['deleteTemplate'], $_SESSION['admin'])) {
	$template = str_replace(["/",".",":","$","~",""],"", $_GET['deleteTemplate']);
	if(file_exists("templates/$template.html")) {
		unlink("templates/$template.html");
		$ar = (array) $db->get("wave:routes");
		unset($ar[$template]);
		$db->update("wave:routes", $ar);
		$echo->ok("File deleted.");
	}
	else
	{
		$echo->err("Error");
	}
}

if(isset($_GET['hash'])) {
	$echo->msg(sha1(urldecode($_GET['hash'])));
}

//Upload image
if(isset($_GET['upload'], $_FILES['file'], $_SESSION['admin'])) {
	require_once 'core/func/uploadFile.func.php';
	$s = "";
	if(isset($_GET['sub'])) {
		$s = str_replace(['.','/',':','$'], '', $_GET['sub']);
	}
	$r = uploadFile('file', $s, true, true);
	if(isset($r['error'])) {
		$echo->data($r);
	}
	else
	{
		echo $r;
	}
}

//Remove image
if(isset($_GET['remove'], $_GET['file'], $_SESSION['admin'])) {
	$s = str_replace(['..',':','$'], '', $_GET['file']);
	if(file_exists("img/$s")) {
		@unlink("img/$s");
		$echo->ok("File deleted.");
	}
	else
	{
		$echo->ok("Unknow file.");
	}
}