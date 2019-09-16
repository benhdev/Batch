<?php

namespace Model\Courier;

class RoyalMail extends DefaultCourier {

	const COURIER_NAME = COURIER_ROYALMAIL;
	const COURIER_BATCH_EMAIL = "me@benhirst.co.uk";

	protected static $arrIDs = [];
	private static $intOrders = 0;

	// custom function to generate the unique number ID for this
	// consignment (RoyalMail)
	// Increments by 1 for each consignment using this courier
	protected static function createGUID() {
		// increment the intOrders by 1 and return it
		$intNewID = ++self::$intOrders;
		self::$arrIDs[] = $intNewID;

		return $intNewID;
	}

	public static function send() {
		parent::send(); // log the ids for this courier
		mail(self::COURIER_BATCH_EMAIL, "Batch IDs", json_encode(self::$arrIDs));
	}
}

?>