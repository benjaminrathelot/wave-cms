<?php
// Agencys JSON System

class AJS {

	public function message($m,$ok="x",$err="x") {
		$r['msg']=$m;
		if(is_numeric($ok) AND $ok==1) {
			$r['ok']=1;
		}
		else if(is_numeric($err) AND $err==1) {
			$r['error']=1;
		}
		echo json_encode($r);
	}
	public function msg($m) {
		$this->message($m);
	}


	public function error($m) {
		$this->message($m,0,1);
	}
	public function err($m) {
		$this->error($m);
	}


	public function success($m) {
		$this->message($m,1);
	}
	public function ok($m) {
		$this->success($m);
	}


	public function data($d) {
		echo json_encode($d);
	}

	public function mongo($m) {
		$this->data(iterator_to_array($m));
	}
	
	public function msgdata($m,$d) {
		$d['msg'] = $m;
		$this->data($d);
	}
}
