<?php

class Envato {

	var $CI;

	var $personal_token;

	var $api_url = "https://api.envato.com/v3/market/author/";

	var $results;

	public function __construct() 
	{
		$this->CI =& get_instance();
		$this->personal_token = $this->CI->settings->info->envato_personal_token;
	}

	public function check_item_code($code) 
	{
		$url = $this->api_url . "sale?code=" . $code;

		return $this->execute($url)->results();
	}

	public function execute($url) 
	{
		$header = array();
		$header[] = 'Authorization: Bearer '.$this->personal_token;
		$header[] = 'Content-Type: application/json; charset=utf-8';
		$header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:41.0) Gecko/20100101 Firefox/41.0';
		$header[] = 'timeout: 20';
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
		$this->results = curl_exec($curl);
		curl_close($curl);
		return $this;
	}

	public function results() 
	{
		return json_decode($this->results);
	}

}

?>