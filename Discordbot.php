<?php 

class Discordbot
{

	private $url;
	private $username;

	public function __construct($url, $username)
	{
		$this->url = $url;
		$this->username = $username;
	}

	public function send($message)
	{

		if(!is_array($message))
			$message = array("content" => $message);
		
		$message = array_merge($message, [
			"username" => $this->username
		]);

		$hookObject = json_encode($message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		
		$headers = [ 'Content-Type: application/json; charset=utf-8' ];
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $hookObject);

		curl_exec($ch);
		curl_close($ch);
	}
}
