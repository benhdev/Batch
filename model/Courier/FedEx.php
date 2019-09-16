<?php

namespace Model\Courier;
use \Config as Config;

class FedEx extends DefaultCourier {

	const COURIER_NAME = COURIER_FEDEX;

	protected static $arrIDs = [];
	private static $intOrders = 0;

	// custom function to generate the unique number ID for this
	// consignment (FedEx)
	// Increments by 2 for each consignment using this courier
	protected static function createGUID() {
		self::$intOrders = self::$intOrders + 2;
		$intNewID = self::$intOrders;
		self::$arrIDs[] = $intNewID;

		return $intNewID;
	}

	public static function send() {
		parent::send(); // log the ids for this courier

		// initialise curl request
		$newCurl = curl_init(Config::getFedexApi());

		$arrPostData = [
			"content" => json_encode(self::$arrIDs)
		];

		// set http method to post
	    curl_setopt($newCurl, CURLOPT_POST, true);

	    // set the post fields to arrPostData
	    curl_setopt($newCurl, CURLOPT_POSTFIELDS, http_build_query($arrPostData));

	    // return response as a string
	    curl_setopt($newCurl, CURLOPT_RETURNTRANSFER, true);

	    $strResponse = curl_exec($newCurl);

	    // close the connection
	    curl_close($newCurl);
	}

}

?>