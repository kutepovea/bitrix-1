<?

Class ReCaptchaAjax {

	public $apiurl = "http://www.google.com/recaptcha/api/verify";
	public $privatekey = "6LcoI-ASAAAAAIeCfcCT4ypYxxx9h30CZSVHE2De";
	public $remoteip = $_SERVER['REMOTE_ADDR'];
	public $challenge = $_POST['recaptcha_challenge_field'];
	public $response = $_POST['recaptcha_response_field'];

	function getResult() {
		$postdata = http_build_query(
			array(
				'privatekey' => $this->privatekey,
				'remoteip' => $this->remoteip,
				'challenge' => $this->challenge,
				'response' => $this->response
			)
		);
		$opts = array('http' =>	array('method'  => 'POST', 'header'  => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));
		$context  = stream_context_create($opts);
		$result = file_get_contents($this->apiurl, false, $context);
		return $result;
	}
	
	function parseResult() {
		
	}
}
?>