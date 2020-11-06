<?php
namespace App\Classes;

class BzgS3 {
	
	/**
	* Get a url
	*/
	public static function get_url($filename) {
		return 'https://' . \Config::get('app.s3_bucket') . '.' . \Config::get('app.s3_host') . '/' . $filename;
	}
	
	/**
	* Get a file
	*/
	public static function get($filename) {
		return file_get_contents(self::get_url($filename));
	}
	
	/**
	* Upload a file to the s3
	*/
	public static function put($filename, $payload) {
		
		if (!\Config::get('app.s3_host')) return false;
		
		$method = 'PUT';
		$time = time();
		$payload_hash = hash('sha256', $payload);
		
		$headers = self::get_canonical_request_headers($time, $payload_hash);
		$signed_headers = self::get_signed_headers($headers);
		$canonical_request = self::get_canonical_request('PUT', $filename, '', $headers, $signed_headers, $payload_hash);
		
		$string_to_sign = 'AWS4-HMAC-SHA256' . "\n" . gmdate('Ymd\THis\Z', $time) . "\n" . self::get_scope($time) . "\n" . hash('sha256', $canonical_request);
		$signing_key = self::get_signing_key($time);
		$signature = hash_hmac('sha256', $string_to_sign, $signing_key, false);
		
		$credential = \Config::get('app.s3_api_key') . '/' . self::get_scope($time);
		
		$headers[] = 'Authorization: AWS4-HMAC-SHA256 Credential=' . $credential . ',SignedHeaders=' . implode(';', $signed_headers) . ',Signature=' . $signature;
		$headers[] = 'Content-type: ' . self::get_mime_type_for_filename($filename);
		
		return self::curl_request('PUT', self::get_url($filename), $payload, $headers);
	}
	
	/**
	* Make a curl request
	*/
	public static function curl_request($method, $url, $payload, $headers) {
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($curl_handle, CURLOPT_CAINFO, \Config::get('app.pem_file'));	
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
		//curl_setopt($curl_handle, CURLOPT_HEADER, true);
		//curl_setopt($curl_handle, CURLINFO_HEADER_OUT, true);
		
		$result = curl_exec($curl_handle);
		$error = curl_error($curl_handle);
		$info = curl_getinfo($curl_handle);
		curl_close($curl_handle);
		
		if ($error) return Array('error' => $error);
		else if ($info['http_code'] != 200) return Array('error' => 'Error in request', 'result' => json_decode(json_encode(simplexml_load_string($result)), true));
		else return true;
	}
	
	/**
	* Get the headers that are used in the signature, the other headers are added later
	*/
	private static function get_canonical_request_headers($time, $payload_hash) {
		$headers = Array(
			'date:' . date('r', $time),
			'host:' . \Config::get('app.s3_bucket') . '.' . \Config::get('app.s3_host'),
			'x-amz-content-sha256:' . $payload_hash,
			'x-amz-date:' . gmdate('Ymd\THis\Z', $time),
			'x-amz-storage-class:STANDARD',
			'x-amz-acl:public-read'
		);
		sort($headers);
		return $headers;
	}
	
	/**
	* Get the signed headers from a list of headers
	*/
	private static function get_signed_headers($headers) {
		$signed_headers = Array();
		foreach ($headers as $header) {
			$signed_headers[] = substr($header, 0, strpos($header, ':'));
		}
		return $signed_headers;
	}
	
	/**
	* Get the canonical_request
	*/
	private static function get_canonical_request($method, $filename, $query_string, $headers, $signed_headers, $payload_hash) {
		return $method . "\n/" . urlencode($filename) . "\n" . $query_string .  "\n" . implode("\n", $headers) . "\n\n" . implode(';', $signed_headers) . "\n" . $payload_hash;
	}
	
	/**
	* Get the signing key
	*/
	private static function get_signing_key($time) {
		$date_key = hash_hmac('sha256', date('Ymd', $time), 'AWS4' . \Config::get('app.s3_api_secret'), true);
		$date_region_key = hash_hmac('sha256', \Config::get('app.s3_region'), $date_key, true);
		$date_region_service_key = hash_hmac('sha256', 's3', $date_region_key, true);
		return hash_hmac('sha256', 'aws4_request', $date_region_service_key, true);
	}
	
	/**
	* Get the scope
	*/
	private static function get_scope($time) {
		return date('Ymd', $time) . '/' . \Config::get('app.s3_region') . '/' . 's3' . '/' . 'aws4_request';
	}
	
	public static function get_mime_type_for_filename($filename) {
		$ext = explode('.', $filename);
		$ext = strtolower(end($ext));
		if ($ext == 'jpg' || $ext == 'jpeg') return 'image/jpeg';
		if ($ext == 'tiff' || $ext == 'tif') return 'image/tiff';
		if ($ext == 'png') return 'image/png';
		if ($ext == 'gif') return 'image/gif';
		if ($ext == 'pdf') return 'application/pdf';
		return '';
	}
	
}

?>