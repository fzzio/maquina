<?php
function get_client_ip() {
     $ipaddress = '';
     if (getenv('HTTP_CLIENT_IP'))
         $ipaddress = getenv('HTTP_CLIENT_IP');
     else if(getenv('HTTP_X_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_X_FORWARDED_FOR'&quot);
     else if(getenv('HTTP_X_FORWARDED'))
         $ipaddress = getenv('HTTP_X_FORWARDED');
     else if(getenv('HTTP_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_FORWARDED_FOR');
     else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
     else if(getenv('REMOTE_ADDR'))
         $ipaddress = getenv('REMOTE_ADDR');
     else
         $ipaddress = 'UNKNOWN';

     return $ipaddress; 
}
	function safe_string_escape($str)
	{
		$len=strlen($str);
		$escapeCount=0;
		$targetString='';
		for($offset=0;$offset<$len;$offset++) {
			switch($c=$str{$offset}) {
				case "'":
				// Escapes this quote only if its not preceded by an unescaped backslash
						if($escapeCount % 2 == 0) $targetString.="\\";
						$escapeCount=0;
						$targetString.=$c;
						break;
				case '"':
				// Escapes this quote only if its not preceded by an unescaped backslash
						if($escapeCount % 2 == 0) $targetString.="\\";
						$escapeCount=0;
						$targetString.=$c;
						break;
				case '\\':
						$escapeCount++;
						$targetString.=$c;
						break;
				default:
						$escapeCount=0;
						$targetString.=$c;
			}
		}
		return $targetString;
	}

	function d($d) {
		echo '<pre>';
		print_r($d);
		echo '</pre>';
	}

	/**
	* This function is used to decoding signed_request data
	* more information is here http://developers.facebook.com/docs/authentication/signed_request
	*/
	function parse_signed_request($signed_request, $secret) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2);

		// decode the data
		$sig = base64_url_decode($encoded_sig);
		$data = json_decode(base64_url_decode($payload), true);

		if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
			error_log('Unknown algorithm. Expected HMAC-SHA256');
			return null;
		}

		// check sig
		$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
		if ($sig !== $expected_sig) {
			error_log('Bad Signed JSON signature!');
			return null;
		}

		return $data;
	}

	function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}
?>