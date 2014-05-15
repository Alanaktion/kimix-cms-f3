<?php

namespace Controller;

class Index extends Base {

	public function index($f3, $params) {
		if($f3->get("user_id")) {
			$f3->reroute("/user/dashboard");
		} else {
			$f3->set("menuitem", "home");
			echo \Template::instance()->render("index/landing.html");
		}
	}

	public function login($f3, $params) {
		if($f3->get("user_id")) {
			if(!$f3->get("GET.to")) {
				$f3->reroute("/user/dashboard");
			} else {
				$f3->reroute($f3->get("GET.to"));
			}
		} else {
			if($f3->exists("GET.demo")) {
				$f3->set("login.error", 'Log in with username and password "demo".');
			}
			if($f3->get("GET.to")) {
				$f3->set("to", $f3->get("GET.to"));
			}
			echo \Template::instance()->render("index/login.html");
		}
	}

	public function loginpost($f3, $params) {
		$user = new \Model\User();

		// Load user by username or email address
		if(strpos($f3->get("POST.username"), "@")) {
			$user->load(array("email=? AND deleted IS NULL", $f3->get("POST.username")));
		} else {
			$user->load(array("username=? AND deleted IS NULL", $f3->get("POST.username")));
		}

		// Verify password
		$crypt = \Bcrypt::instance();
		if($crypt->verify($f3->get("POST.password"), $user->password)) {
			$f3->set("SESSION.user_id", $user->id);
			if(!$f3->get("POST.to")) {
				$f3->reroute("/user/dashboard");
			} else {
				$f3->reroute($f3->get("POST.to"));
			}
		} else {
			if($f3->get("POST.to")) {
				$f3->set("to", $f3->get("POST.to"));
			}
			$f3->set("login.error", "Incorrect login details, try again.");
			echo \Template::instance()->render("index/login.html");
		}
	}

	public function logout($f3, $params) {
		$f3->clear("SESSION.user_id");
		$f3->reroute("/");
	}

}
