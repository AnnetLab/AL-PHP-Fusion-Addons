<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: uloginAPI2.class.php
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

    class uloginAPI2 {

        private $_profile;
        private $_translate = array(
            'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'е'=>'e', 'ж'=>'g', 'з'=>'z',
            'и'=>'i', 'й'=>'y', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p',
            'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'ы'=>'i', 'э'=>'e', 'А'=>'A',
            'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D', 'Е'=>'E', 'Ж'=>'G', 'З'=>'Z', 'И'=>'I',
            'Й'=>'Y', 'К'=>'K', 'Л'=>'L', 'М'=>'M', 'Н'=>'N', 'О'=>'O', 'П'=>'P', 'Р'=>'R',
            'С'=>'S', 'Т'=>'T', 'У'=>'U', 'Ф'=>'F', 'Ы'=>'I', 'Э'=>'E', 'ё'=>"yo", 'х'=>"h",
            'ц'=>"ts", 'ч'=>"ch", 'ш'=>"sh", 'щ'=>"shch", 'ь'=>"", 'ъ'=>"", 'ю'=>"yu", 'я'=>"ya",
            'Ё'=>"YO", 'Х'=>"H", 'Ц'=>"TS", 'Ч'=>"CH", 'Ш'=>"SH", 'Щ'=>"SHCH", 'Ь'=>"", 'Ъ'=>"",
            'Ю'=>"YU", 'Я'=>"YA"
        );

        public function __construct($token) {
            $this->getProfile($token);
        }

        private function getProfile($token) {
            $this->_profile = json_decode(@file_get_contents('http://ulogin.ru/token.php?token='.$token.'&host='.$_SERVER['HTTP_HOST']));
        }

        public function genNickname() {
            if ($this->genFullName()) {
                return $this->modifyNickname($this->normalize($this->genFullName()));
            } elseif (@$this->_profile->nickname && strlen($this->_profile->nickname) >= 3 ) {
                return $this->modifyNickname($this->normalize($this->_profile->nickname));
            } elseif (!empty($this->_profile->email) && preg_match('/^(.+)\@/i', $this->_profile->email, $nickname)) {
                return $this->modifyNickname($nickname[1]);
            }
            return false;
        }

        public function genEmail () {
            if (!empty($this->_profile->email)) {
                return $email = $this->_profile->email;
            }
            return false;
        }

        public function genUserIdentity(){
            return $this->_profile->identity;
        }
        public function genUserNetwork(){
            return $this->_profile->network;
        }

        public function genDisplayName() {
            if ($this->genFullName()) {
                return $this->genFullName();
            } elseif ($this->genNickname()) {
                return $this->genNickname();
            } else {
                $identity_component = parse_url($this->_profile->identity);
                $result = $identity_component['host'];
                if ($identity_component['path'] != '/') {
                    $result .= $identity_component['path'];
                }
                return $result.$identity_component['query'];
            }
        }

        public function genFullName () {
            if (@$this->_profile->first_name || @$this->_profile->last_name) {
                //return trim($this->conv2Cp($this->_profile->first_name).' '.$this->conv2Cp($this->_profile->last_name));
                return trim($this->_profile->first_name.' '.$this->_profile->last_name);
            }
            return false;
        }

        private function conv2Cp($value) {
            return iconv("UTF-8","Windows-1251",$value);
        }

        public function checkExist($field,$value) {
            $result = dbquery("SELECT * FROM ".DB_USERS." WHERE ".$field."='".$value."'");
            if (dbrows($result)) {
                return true;
            } else {
                return false;
            }
        }

        public function genRandomPassword($len=12, $char_list='a-z,0-9') {
            $chars = array();
            $chars['a-z'] = 'qwertyuiopasdfghjklzxcvbnm';
            $chars['A-Z'] = strtoupper($chars['a-z']);
            $chars['0-9'] = '0123456789';
            $chars['~'] = '~!@#$%^&*()_+=-:";\'/\\?><,.|{}[]';
            $charset = '';
            $password = '';
            if (!empty($char_list)) {
                $char_types = explode(',', $char_list);
                foreach ($char_types as $type) {
                    if (array_key_exists($type, $chars)) {
                        $charset .= $chars[$type];
                    } else {
                        $charset .= $type;
                    }
                }
            }
            for ($i=0; $i<$len; $i++) {
                $password .= $charset[ rand(0, strlen($charset)-1) ];
            }
            return $password;
        }

        private function normalize($string, $delimer='-') {
            $string = strtr($string, $this->_translate);
            return trim(preg_replace('/[^\w]+/i', $delimer, $string), $delimer);
        }

        public function modifyNickname($nickname) {
            if ($this->checkExist("user_name",$nickname)) {
                $i=1;
                while ($this->checkExist("user_name",$nickname)) {
                    $nickname .= $nickname.'_'.$i;
                    $i++;
                }
            } else {
                return $nickname;
            }
        }

    }

?>