<?php

namespace Model\Courier;

class DefaultCourier {
	function __construct($objConsignment) {
		// Assign the objConsignment for this class' instance
		$this->objConsignment = $objConsignment;
	}

	public function prepareForSend() {
		// Generate the unique number ID for this Courier
		$intGUID = $this::createGUID();
		$this->objConsignment->setGUID($intGUID);

		// log the property values of the consignment
		error_log("Address: " 	. $this->objConsignment->getAddress());
		error_log("Courier: " 	. $this->objConsignment->getCourierName());
		error_log("Email: " 	. $this->objConsignment->getEmail());
		error_log("GUID: " 		. $this->objConsignment->getGUID());
		error_log("Name: " 		. $this->objConsignment->getName());

		error_log("-----");
	}

	public static function send() {
		// default send function, will send an error log
		error_log("IDs to send " . static::COURIER_NAME . ": " . json_encode(static::$arrIDs));
	}
}

?>