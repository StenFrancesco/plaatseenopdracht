<?php
namespace App\Classes;

class DefaultObject {
	
	public function val($key, $default = '') {
		
		return getval($this->data, $key, $default);
	}
}

function getval(&$array, $key, $default = '') {
	if (!is_array($array)) return $default;
	if (is_array($key)) {
		$temp = $array;
		foreach ($key as $part) {
			
			// Filter an array on a value from a sub-object
			if (is_array($temp) && strpos($part, '{') === 0 && preg_match('/{(.*):(.*)}/', $part, $match)) {
				foreach ($temp as $sub) {
					if (getval($sub, $match[1]) == $match[2]) {
						$temp = $sub;
						break;
					}
				}
			}
			else if (is_array($temp) && isset($temp[$part])) {
				$temp = $temp[$part];
			}
			else {
				return $default;
			}
		}
		return $temp;
	}
	if (isset($array[$key])) return $array[$key];
	else if (strpos($key, '/')) return getval($array, explode('/', $key), $default);
	else return $default;
}

?>