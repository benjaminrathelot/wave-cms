<?php

$pluginName = "wv_contact_form";
$PU = new PU($pluginName);
if(!$PU->checkInstalled()) {
	$PU->addMainMenuLinkAdmin($pluginName, "Contact Form");
	$PU->addRouteAdmin($pluginName, "admin", "WVContactFormAdmin");
	$PU->AddInitScriptAdmin("controller_admin");
	$PU->addRoute("contact_message_sent", "sent");
	$PU->addRoute("contact_form", "form", "ContactForm");
	$PU->addMainMenuLink("contact_form", "Contact Us");
	$PU->addInitScript("controller");
	$db->insert($pluginName.":config", [
		"mail"=>"yourmail@example.com",
		"subject"=>"New message from your website",
		"fields"=>[ ["id"=>"first_name", "label"=>"First Name"], ["id"=>"last_name", "label"=>"Last Name"], ["id"=>"email_address", "label"=>"Email Address"]],
		"customHeaders"=>"",
		"fromMail"=>"contact_form@yourdomain.com"
		]);
	$PU->installed();
}