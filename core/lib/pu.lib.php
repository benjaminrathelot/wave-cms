<?php
// PU : Plugin Utilities /// Agencys WAVE CMS
// (c) 2016 Agencys :: Benjamin Rathelot - In order to make plugin development easier
require_once 'core/lib/jdb.lib.php';

class PU {

	protected $name;
	protected $dbname;
	protected $installed;
	protected $db;

	public function checkInstalled() {
		if(file_exists("plugins/".$this->name."/installed")) {
			$this->installed = true;
		}
		else
		{
			$this->installed = false;
		}
		return $this->installed;
	}

	public function __construct($name, $dbName = false, $needDBFolder = true) {
		if($needDBFolder) {
			@mkdir("JsonDB/$name/");
		}
		$this->name = $name;
		$this->dbname = ($dbName)?$dbName:$name;
		$this->checkInstalled();
		$this->db = new JDB;
	}

	public function installed($bool=true) {
		if($bool) {
			@touch("plugins/".$this->name."/installed");
		}
		else
		{
			@unlink("plugins/".$this->name."/installed");
		}
	}

	public function addRoute($url, $templateName, $controllerName="Default", $admin=false) {
		$adm = ($admin)?"_admin":"";
		$this->db->insert("wave:routes$adm", ["$url"=>array("url"=>"/$url", "templateUrl"=>"plugins/".$this->name."/templates/$templateName.html", "controller"=>$controllerName."Ctrl")]);
	}

	public function addRouteAdmin($url, $templateName, $controllerName="Default") {
		$this->addRoute($url, $templateName, $controllerName, true);
	}

	public function addMainMenuLink($url, $label, $admin=false) {
		$adm = ($admin)?"_admin":"";
		$this->db->insert("wave:main_menu$adm", [$url=>$label]);
	}

	public function addMainMenuLinkAdmin($url, $label) {
		$this->addMainmenuLink($url, $label, true);
	}

	public function addInitScript($name="controller", $admin=false){
		$adm = ($admin)?"_admin":"";
		$script = (array) $this->db->get("wave:init_script$adm");
		$script[] = "plugins/".$this->name."/js/$name.js";
		$this->db->update("wave:init_script$adm", $script);
	}

	public function addInitScriptAdmin($name="controller_admin"){
		$this->addInitScript($name, true);
	}

	public function addInitCSS($name, $admin=false){
		$adm = ($admin)?"_admin":"";
		$script = (array) $this->db->get("wave:init_css$adm");
		$script[] = "plugins/".$this->name."/css/$name.css";
		$this->db->update("wave:init_css$adm", $script);
	}

	public function addInitCSSAdmin($name){
		$this->addInitCSS($name, true);
	}

	public function addWidget($name, $templateName="widget") {
		$script = (array) $this->db->get("wave:menu_items");
		$script[$name] = 'plugins/'.$this->name."/templates/$name.html";
		$this->db->update("wave:menu_items", $script);
	}
}