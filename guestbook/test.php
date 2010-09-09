<?php
class sql
{
	var $host;
	var $pass;
	var $user;
	var $db;
	var $query=array();
	var $connected;
	var $connection;
	var $log=array();
	var $query_count=0;
	var $query_time=0;
	var $debug;
	
	function sql($sqlinfo="",$debugging=true) {
		$this->debug=$debugging;
		if (is_array($sqlinfo)) {
			$this->host = $sqlinfo['host'];
			$this->user = $sqlinfo['user'];
			$this->pass = $sqlinfo['pass'];
			$this->db   = $sqlinfo['db'];
			$this->log_message("参数找到！");
			$this->init();
		}
		else
		{
			$this->connected = false;
			$this->log_error("没有找到登录参数！.");
		}
	}
	
	function init() {
		$dbConnect = mysql_connect($this->host,$this->user,$this->pass);
		$dbSelect = mysql_select_db($this->db,$dbConnect);
		if (!$dbConnect || !$dbSelect) {
			$this->connected = false;
			$this->log_error("SQL连接不成功！<br>\nMysql said: ".mysql_error());
		}
		else {
			$this->connected = true;
			$this->connection = $dbConnect;
			$this->log_message("连接成功！");
		}
	}
}
?>
