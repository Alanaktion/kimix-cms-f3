<?php

namespace Model;

class User extends Base {

	protected $_table_name = "user";

	// Load currently logged in user, if any
	public function loadCurrent() {
		$f3 = \Base::instance();
		if($user_id = $f3->get("SESSION.user_id")) {
			$this->load($user_id);
			if($this->id) {
				$f3->set("user_id", $this->get("id"));
				$f3->set("user", $this);
			}
		}
		return $this;
	}

	// Hash a password with bcrypt
	public function hash($pass) {
		$crypt = \Bcrypt::instance();
		return $crypt->hash($pass, alphanum_salt());
	}

}

