<?php

namespace Controller;

class Admin extends Base {

	public function index($f3, $params) {
		$user_id = $this->_requireAdmin();

		$f3->set("menuitem", "admin");
		echo \Template::instance()->render("admin/index.html");
	}

}
