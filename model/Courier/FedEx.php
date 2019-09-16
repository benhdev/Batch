<?php

namespace Model\Courier;

class FedEx extends DefaultCourier {

	const COURIER_NAME = COURIER_FEDEX;
	const COURIER_API = "https://discordapp.com/api/webhooks/621402327286415370/sfxlIKB_0RFr24RhzExKoE6nnqA-DC3ifcq9OcwQWm8l2cz6LlvOJR6Oxi_OLFNUNM6K";

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
		$newCurl = curl_init(self::COURIER_API);

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