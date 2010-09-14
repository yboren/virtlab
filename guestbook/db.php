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
	
	function sql($sqlinfo="",$debugging=false) {
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
	
	function query($query) {
		$start=$this->getmicrotime(); // start time of query
		
		$this->query_count++;
		$this->query[$this->query_count] = mysql_query($query,$this->connection);
		
		$end=$this->getmicrotime(); // end time of query
		
		$this->query_time+=($end - $start);
		
		if (!$this->query[$this->query_count]) {
			$this->log_error("失败： <em>$query</em><br>Mysql Said: ".mysql_error());
			return false;
		}
		$this->log_message("成功： <em>$query</em>",$tpassed);
		return $this->query_count;
	}
	
	function set_db($db) {
		$this->db = $db;
		$this->log_message("数据库： $db.");
		$this->init();
	}
	
	function set_info($host,$user,$pass) {
		$this->host = $hose;
		$this->user = $user;
		$this->pass = $pass;
		$this->log_message("新用户设置： ($host,$user,$pass)");
		$this->init();
	}
	
	function num_rows($index=null)
	{
		$index=is_null($index)?$this->query_count:$index;
		if ($this->query[$index])
			return mysql_num_rows($this->query[$index]);
		else
			$this->log_error("num_rows() 未执行！");
	}
	
	function affected_rows()
	{
		if ($this->query[$this->query_count])
			return mysql_affected_rows();
	}
	
	function fetch_array($index=null)
	{
		$index=is_null($index)?$this->query_count:$index;
		if ($this->query[$index])
			return mysql_fetch_array($this->query[$index]);
		else
			$this->log_error("fetch_array() 未执行！");
	}
	
	function fetch_object($index=null)
	{
		$index=is_null($index)?$this->query_count:$index;
		if ($this->query[$index])
			return mysql_fetch_object($this->query[$index]);
		else
			$this->log_error("fetch_object() 未执行！");
	}
	
	function log_message($message,$time=0)
	{
		$message="[NOTICE] (".$time."s) $message";
		if ($this->debug)
			echo $message."<br>\n";
		$this->log[] = $message;
	}
	
	function log_error($error)
	{
		$error="[ERROR] ".$error;
		echo "$error<br>\n";
		$this->log[] = $error;
	}
	
	function debug($status)
	{
		echo ("Debugging: ");
		echo ($status?"on":"off");
		echo ("<br>\n");
		$this->debug=$status;
	}
	
	function show_log($amount="all")
	{
		if ($amount=="all")
		{
			foreach($this->log as $log_item)
			{
				echo "$log_item<br>\n";
			}
		}
		else if ($amount=="last")
		{
			echo $this->log[count($this->log)-1]."<br>\n";
		}
	}
	
	function getmicrotime(){ 
    	list($usec, $sec) = explode(" ",microtime()); 
    	return ((float)$usec + (float)$sec); 
    }
	
	/*/////////////////////////////////////////////
		These functions are all deprecated
	/////////////////////////////////////////////*/
	function numResults() {
		return $this->num_rows();
	}
	
	function affectedRows() {
		return $this->affected_rows();
	}
	
	function resultArray() {
		return $this->fetch_array();
	}
	
	function resultObject() {
		return $this->fetch_object();
	}
}
?>
