<?php

namespace Model;

class Helper {

	public static function getCourierClassName($strClassName) {
		return '\\Model\\Courier\\'.$strClassName;
	}
}

?>