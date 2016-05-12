<?php

$pluginName = "wv_newsletter";
$PU = new PU($pluginName, "set_only_".$pluginName);
$PU->addMainMenuLinkAdmin($pluginName."_admin", "Wave Newsletter");
$PU->addRouteAdmin($pluginName."_admin", "admin", "WVNewsletterAdmin");
$PU->addRoute("newsletter_suscribed", "suscribed");
$PU->addRoute("newsletter_suscribe", "suscribe", "WVNewsletterSuscribe");
$PU->addMainMenuLink("newsletter_suscribe", "Newsletter");
$PU->addInitScriptAdmin();
$PU->addInitScript();
$PU->addWidget($pluginName);
$PU->installed();