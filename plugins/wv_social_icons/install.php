<?php

$pluginName = "wv_social_icons";
$PU = new PU($pluginName);
if(!$PU->checkInstalled()) {
	$PU->addMainMenuLinkAdmin($pluginName, "Social Icons");
	$PU->addRouteAdmin($pluginName, "admin", "WVSocialIconsAdmin");
	$PU->addInitScriptAdmin("controller_admin");
	$PU->addInitScript("controller");
	$PU->addInitCSS("social_icons");
	$db->insert($pluginName.":links", [
		"rss"=>"",
		"facebook"=>"",
		"twitter"=>"",
		"google"=>"",
		"linkedin"=>""
		]);
	$PU->installed();
}