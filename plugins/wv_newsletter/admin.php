<?php
if($admin) {
	if(isset($_GET['list'])) {
		$g = $db->get("set_only_wv_newsletter");
		$echo->data($g);
		exit;
	}
}