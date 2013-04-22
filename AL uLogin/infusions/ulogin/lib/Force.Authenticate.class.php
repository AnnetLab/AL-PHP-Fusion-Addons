<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: Force.Authenticate.class.php
| Author: Rush
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/ 
 if (!defined("IN_FUSION")) { die("Access Denied"); }

$fusion_domain = (strstr($settings['site_host'], "www.") ? substr($settings['site_host'], 3): $settings['site_host']);


class ForceAuth {

 public function __construct($user_id) {
		$this->_force_auth($user_id);
	} 



 private function _force_auth($user_id) {
		global $locale, $settings;

		$result = dbquery("	SELECT * FROM ".DB_USERS." WHERE user_id='".$user_id."' LIMIT 1");

		if (dbrows($result) == 1) {
		    $user = dbarray($result);

ForceAuth::setUserCookie($user['user_id'], $user['user_salt'], $user['user_algo'], true, true);
         }
	} 

 public static function setUserCookie($userID, $salt, $algo, $remember = false, $userCookie = true) {

		global $_COOKIE;

		$cookiePath = COOKIE_PATH; $cookieName = COOKIE_USER;

		if ($remember) {
		    $cookieExpiration = time() + 1209600; // 14 days
		} else {
		    $cookieExpiration = time() + 172800; // 48 hours
		}

		if (!$userCookie) {
			$cookiePath = COOKIE_PATH."administration/";
			$cookieName = COOKIE_ADMIN;
			$cookieExpiration = time() + 3600; // 1 hour
		}

		$key = hash_hmac($algo, $userID.$cookieExpiration, $salt);
		$hash = hash_hmac($algo, $userID.$cookieExpiration, $key );

		$cookieContent = $userID.".".$cookieExpiration.".".$hash;

		//header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
		ForceAuth::_setCookie($cookieName, $cookieContent, $cookieExpiration, $cookiePath, COOKIE_DOMAIN, false, true);

	} 


 private static function _setCookie ($cookieName, $cookieContent, $cookieExpiration, $cookiePath, $cookieDomain, $secure = false, $httpOnly = false) {
		if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
			setcookie($cookieName, $cookieContent, $cookieExpiration, $cookiePath, $cookieDomain, $secure, $httpOnly);
		} else {
			setcookie($cookieName, $cookieContent, $cookieExpiration, $cookiePath, $cookieDomain, $secure);
		}
	} 
}

?>
