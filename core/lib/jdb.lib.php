	<?php
// JsonDB Script
// Dedicated to small websites
// Agencys (c) 2014

class JDB {

	protected $path = "JsonDB/";

	public function __construct($path="JsonDB/") {
		$this->path=$path;
	}

	public function get($req) {
		$req = str_replace("/", "", $req);
		$r = explode(":",$req);
		if(isset($r[0], $r[1])) {
			if(!file_exists($this->path.str_replace(".","", $r[0])."/".str_replace(".","", $r[1]).".jdf")) {
				return false;
			}
			else
			{
				$f = file_get_contents($this->path.str_replace(".","", $r[0])."/".str_replace(".","", $r[1]).".jdf");
				$d = json_decode($f);
				if(isset($r[2])) { 
					$n = $r[2];
					return $d->$n;
				}
				else
				{
					return $d;
				}
			}
		}
		elseif(isset($r[0]))
		{
		    $files = glob($this->path.str_replace(".","", $r[0])."/*.jdf");
		    $res = [];
			  array_multisort(
				array_map('filemtime', $files),
				SORT_NUMERIC,
				SORT_DESC,
				$files
				);

				foreach($files as $file) {
					$file = str_replace([$this->path, str_replace(".","", $r[0])."/"], "", $file);
					if($file!="." AND $file!=".." AND $file!="id") {
						$res[$file] = json_decode(file_get_contents($this->path.str_replace(".","", $r[0])."/".$file),1);
					}
				}

			return $res;
			}
	}

	public function update($req, $v) {
		$req = str_replace("/", "", $req);
			$r = explode(":",$req);
			if(isset($r[0], $r[1])) {
				if(!file_exists($this->path.str_replace(".","", $r[0])."/".str_replace(".","", $r[1]).".jdf")) {
					return false;
				}
				else
				{
					$f = file_get_contents($this->path.str_replace(".","", $r[0])."/".str_replace(".","", $r[1]).".jdf");
					$d = json_decode($f);
					if(isset($r[2])) {
						$d->$r[2] = $v;
						$o = json_encode($d);
					}
					else
					{
						$o = json_encode($v);
					}
					file_put_contents($this->path.str_replace(".","", $r[0])."/".str_replace(".","", $r[1]).".jdf", $o);
					return true;	
				}
			}
		}

	public function insert($req, $v) {
		$req = str_replace(["/","."], "", $req);
			$r = explode(":", $req);
			if(isset($r[0], $r[1])) {
				if(!file_exists($this->path.str_replace(".","", $r[0])."/id")) {
					file_put_contents($this->path.str_replace(".","", $r[0])."/id","0");
				}

				if($r[1]=="id"){
					$r[1] = file_get_contents($this->path.str_replace(".","", $r[0])."/id")+1;
				}


				$d = json_encode($v);
				if(file_exists($this->path.str_replace(".","", $r[0])."/".str_replace(".","", $r[1]).".jdf")) {
					$g = json_decode(file_get_contents($this->path.str_replace(".","", $r[0])."/".str_replace(".","", $r[1]).".jdf"), 1);
					$g2 = json_decode($d,1);
					$arr = array_merge($g,$g2);
					file_put_contents($this->path.str_replace(".","", $r[0])."/".str_replace(".","", $r[1]).".jdf", json_encode($arr));
				}
				else
				{
					file_put_contents($this->path.str_replace(".","", $r[0])."/".str_replace(".","", $r[1]).".jdf", $d);
				}
				file_put_contents($this->path.str_replace(".","", $r[0])."/id", file_get_contents($this->path.str_replace(".","", $r[0])."/id")+1);
				return true;			
			}
		}	


	public function delete($req) {
		$req = str_replace("/", "", $req);
		$r = explode(":",$req);
		if(isset($r[0], $r[1])) {
			if(!file_exists($this->path.str_replace(".","", $r[0])."/".str_replace(".","", $r[1]).".jdf")) {
				return false;
			}
			else
			{
				if(isset($r[2])) {
					$g = (array) $this->get($r[0].":".$r[1]);
					if(is_array($g) AND isset($g[$r[2]])) {
						unset($g[$r[2]]);
						$this->update($r[0].":".$r[1], $g);
					}
					else
					{
						return false;
					}
				}
				else
				{
				unlink($this->path.str_replace(".","", $r[0])."/".str_replace(".","", $r[1]).".jdf");
				}
			}
		}
		else
		{
			return false;
		}
	}

}