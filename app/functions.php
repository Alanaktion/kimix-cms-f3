<?php // Global core functions

// Get a Gravatar URL from email address and size, uses global Gravatar configuration
function gravatar($email, $size = 80) {
	$f3 = Base::instance();
	$rating = $f3->get("gravatar.rating") ? $f3->get("gravatar.rating") : "pg";
	$default = $f3->get("gravatar.default") ? $f3->get("gravatar.default") : "mm";
	return "//gravatar.com/avatar/" . md5(strtolower($email)) .
			"?s=" . intval($size) .
			"&d=" . urlencode($default) .
			"&r=" . urlencode($rating);
}

// HTML escape shortcode
function h($str) {
	return htmlspecialchars($str);
}

// Get current time and date in a MySQL NOW() format
function now($time = true) {
	return $time ? date("Y-m-d H:i:s") : date("Y-m-d");
}

// Output object as JSON and set appropriate headers
function print_json($object) {
	if(!headers_sent()) {
		header("Content-type: application/json");
	}
	echo json_encode($object);
}

// Use normal string functions if multibyte are unavailable
if(!function_exists('mb_strlen')) {
	function mb_strlen($str,$encoding='UTF-8') {
		return strlen($str);
	}
}
if(!function_exists('mb_substr')) {
	function mb_substr($str,$start,$length,$encoding='UTF-8') {
		return substr($str,$start,$length);
	}
}

function alphanum_salt($length = 22) {
	$character_list = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$salt = "";
	for($i = 0; $i < $length; $i++) {
		$salt .= $character_list{mt_rand(0, (strlen($character_list) - 1))};
	}
	return $salt;
}
function base64_salt($length = 22) {
	$character_list = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+/";
	$salt = "";
	for($i = 0; $i < $length; $i++) {
		$salt .= $character_list{mt_rand(0, (strlen($character_list) - 1))};
	}
	return $salt;
}
