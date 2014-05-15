<?php

namespace Helper;

class Image extends \Image {

	protected $colors = array();
	public $last_data;

	function create($width, $height) {
		$this->data = imagecreatetruecolor($width, $height);
		imagesavealpha($this->data, true);
	}

	// Render a line of text
	function text($text, $size = 9.0, $angle = 0, $x = 0, $y = 0, $color = 0x000000, $font = "opensans-regular.ttf", $overlay_color = null, $overlay_transparency = 0.5, $overlay_padding = 2) {

		$f3 = \Base::instance();

		$font = $f3->get("ROOT") . "/fonts/" . $font;
		if(!is_file($font)) {
			$f3->error(500, "Font file not found");
			return false;
		}

		$color = $this->rgb($color);
		$color_id = imagecolorallocate($this->data, $color[0], $color[1], $color[2]);

		$bbox = imagettfbbox($size, $angle, $font, "M");
		$y += $bbox[3] - $bbox[5];

		if(!is_null($overlay_color)) {
			$overlay_bbox = imagettfbbox($size, $angle, $font, $text);
			$overlay_color = $this->rgb($overlay_color);
			$overlay_color_id = imagecolorallocatealpha($this->data, $overlay_color[0], $overlay_color[1], $overlay_color[2], $overlay_transparency * 127);
			imagefilledrectangle(
				$this->data,
				$x - $overlay_padding,
				$y - $overlay_padding,
				$x + $overlay_bbox[2] - $overlay_bbox[0] + $overlay_padding,
				$y + $overlay_bbox[3] - $overlay_bbox[5] + $overlay_padding,
				$overlay_color_id
			);
		}

		$this->last_data = imagettftext($this->data, $size, $angle, $x, $y, $color_id, $font, $text);
		return $this->save();
	}

	// Render fully justified and wrapped text
	function textwrap($text, $size = 9.0, $left = 0, $top = 0, $color = 0x000000, $font = "opensans-regular.ttf", $max_width = 0) {
		$f3 = \Base::instance();

		$color = $this->rgb($color);
		$color_id = imagecolorallocate($this->data, $color[0], $color[1], $color[2]);

		if(!$max_width) {
			$max_width = $this->width();
		}

		$font = $f3->get("ROOT") . "/fonts/" . $font;
		if(!is_file($font)) {
			$f3->error(500, "Font file {$font} not found");
			return false;
		}

		$words = explode(" ", $text);
		$wnum = count($words);
		$text = "";
		foreach($words as $w) {
			$line_width = 0;
			$bbox = imagettfbbox($size, 0, $font, $line);
			$word_width = $bbox[2] - $bbox[0];
			if($line_width < $max_width) {
				$text .= $w . " ";
			} else {
				$text .= PHP_EOL . $w . " ";
			}
		}

		$this->last_data = imagettftext($this->data, $size, 0, $x, $y, $color_id, $font, $text);
		return $this->save();
	}

	function fill($color = 0x000000) {
		$color = $this->rgb($color);
		$color_id = imagecolorallocate($this->data, $color[0], $color[1], $color[2]);
		imagefill($this->data, 0, 0, $color_id);
		return $this->save();
	}

}
