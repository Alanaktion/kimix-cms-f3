<?php

namespace Controller;

class User extends Base {

	public function index($f3, $params) {
		$user_id = $this->_requireLogin();

		// Determine page
		$page_length = 30;
		if(empty($params["page"])) {
			$params["page"] = 0;
		} elseif (!is_numeric($params["page"])) {
			$f3->error(404);
			return;
		}

		// Get buddies' posts
		$relation = new \Model\User\Relation;
		$related = $relation->find(array("user_id = ?", $user_id));

		// Include own posts
		$related[] = $user_id;
		$filter = implode(",", $related);

		$post = new \Model\Post\Detail;
		$posts = $post->paginate($params["page"], $page_length, "user_id IN ($filter) or page_id IN ($filter)", array("order" => "created DESC"));

		// Return JSON-encoded posts for AJAX
		if($f3->get("AJAX")) {
			print_json($posts);
			$f3->unload();
			return;
		}

		$f3->set("posts", $posts);
		$f3->set("menuitem", "stream");
		echo \Template::instance()->render("user/index.html");
	}

	public function single($f3, $params) {
		$user = new \Model\User;
		$user->load(array("username = ?", $params["username"]));

		// TODO: Add user public check and 404 if needed
		if(!$user->id) {
			$f3->error(404);
			return;
		}

		// Determine page
		$page_length = 30;
		if(empty($params["page"])) {
			$params["page"] = 0;
		} elseif (!is_numeric($params["page"])) {
			$f3->error(404);
			return;
		}

		$post = new \Model\Post\Detail;
		$posts = $post->paginate($params["page"], $page_length, array("page_id = ?", $user->id), array("order" => "created DESC"));

		// Return JSON-encoded posts for AJAX
		if($f3->get("AJAX")) {
			print_json($posts);
			$f3->unload();
			return;
		}

		$f3->set("posts", $posts);
		$f3->set("user", $user);
		echo \Template::instance()->render("user/single.html");
	}

	public function single_post($f3, $params) {
		$user = new \Model\User;
		$user->load(array("username = ?", $params["username"]));

		$post = new \Model\Post\Detail;
		$post->load(array("page_id = ? AND id = ?", $user->id, $params["id"]));

		// TODO: Add user and post public checks
		if(!$user->id || !$post->id) {
			$f3->error(404);
			return;
		}

		// Return JSON-encoded post for AJAX
		if($f3->get("AJAX")) {
			print_json($post);
			$f3->unload();
			return;
		}

		$f3->set("post", $post);
		echo \Template::instance()->render("user/post.html");
	}

	public function single_buddies($f3, $params) {
		$user = new \Model\User;
		$user->load(array("username = ?", $params["username"]));

		// TODO: Add user and buddy list public checks
		if(!$user->id) {
			$f3->error(404);
			return;
		}

		// Return JSON-encoded post for AJAX
		if($f3->get("AJAX")) {
			print_json($post);
			$f3->unload();
			return;
		}

		$f3->set("post", $post);
		echo \Template::instance()->render("user/post.html");
	}

}
