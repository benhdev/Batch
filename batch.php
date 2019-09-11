<?php

const COURIER_FEDEX 	= "FedEx";
const COURIER_ROYALMAIL = "RoyalMail";

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


class Consignment {

	private $intGUID;
	private $strAddress;
	private $strCourier;
	private $strEmail;
	private $strName;

	function __construct($arrData) {
		// arrData in this contructor will contain the data for the current consignment
		// as an associative array

		if(isset($arrData["Address"])) {
			// check if address exists, then assign private variable
			$this->strAddress = $arrData["Address"];
		}

		if(isset($arrData["Courier"])) {
			// check the courier exists, then assign private variable
			$this->strCourier = $arrData["Courier"];
		}

		if(isset($arrData["Email"])) {
			// check if email exists, then assign private variable
			$this->strEmail = $arrData["Email"];
		}

		if(isset($arrData["Name"])) {
			// check if name exists, then assign private variable
			$this->strName = $arrData["Name"];
		}

		// attempt to get a class for the courier, if it doesn't exist
		// it will throw an exception
		try {
			$this->objCourier = $this->getCourier();
			$this->objCourier->prepareForSend();
		} catch(Exception $e) {
			// we encountered an error.. stop running
			die($e->getMessage());
		}
	}

	private function getCourier() {
		// Return a new instance of a Courier class for this Consignment
		// Throw an Exception if this does not exist
		$strCourier = $this->strCourier;

		if(!class_exists($strCourier)) {
			throw new Exception("Invalid Courier - $strCourier\n");	
		}

		return new $strCourier($this);
	}

	public function getAddress() {
		// return the Address for this Consignment
		return $this->strAddress;
	}

	public function getCourierName() {
		// return the Courier Name for this Consignment
		return $this->strCourier;
	}

	public function getEmail() {
		// return the email for this Consignment
		return $this->strEmail;
	}

	public function getGUID() {
		// return the GUID for this Consignment
		return $this->intGUID;
	}

	public function getName() {
		// return the name for this Consignment
		return $this->strName;
	}


	public function setGUID($intGUID) {
		// set the intGUID variable to the argument provided
		$this->intGUID = $intGUID;
	}


}

class Batch {
	function __construct($arrData = null) {
		// if a default array of consignments is provided
		// set the arrBatchData variable
		if(isset($arrData)) {
			$this->arrBatchData = $arrData;
		}
	}

	function add($arrConsignment) {
		if(!isset($this->arrBatchData)) {
			$this->arrBatchData = [];
		}

		// Add a consignment array into the batch data array
		$this->arrBatchData[] = $arrConsignment;
	}

	function send() {
		// Array for each Consignment PER Courier
		$arrConsignmentsPerCourier = [];
		foreach($this->arrBatchData as $arrConsignment) {
			$objConsignment = new Consignment($arrConsignment);
			$strCourier = $objConsignment->getCourierName();

			if(!array_key_exists($strCourier, $arrConsignmentsPerCourier)) {
				$arrConsignmentsPerCourier[$strCourier] = [];
			}

			// add this consignment into the array for its Courier
			$arrConsignmentsPerCourier[$strCourier][] = $objConsignment;
		}

		foreach($arrConsignmentsPerCourier as $strCourier => $arrConsignments) {
			foreach($arrConsignments as $objConsignment) {
				// Log the address being used for each courier
				error_log("Address for $strCourier: " . $objConsignment->getAddress());
			}

			// send the IDs to each used Courier
			$strCourier::send();
		}

		error_log('complete');
	}
}

// start a new batch with or without consignments already
$objBatch = new Batch([
	[
		"Address" => "123 Testing Road",
		"Courier" => COURIER_ROYALMAIL,
		"Email" => "me@benhirst.co.uk",
		"Name" => "Ben Hirst"
	],
	[
		"Address" => "172 Testing Road",
		"Courier" => COURIER_FEDEX,
		"Email" => "me@benhirst.co.uk",
		"Name" => "Ben Hirst"
	],
	[
		"Address" => "198 Testing Road",
		"Courier" => COURIER_FEDEX,
		"Email" => "me@benhirst.co.uk",
		"Name" => "Ben Hirst"
	],
	[
		"Address" => "821 Testing Road",
		"Courier" => COURIER_ROYALMAIL,
		"Email" => "me@benhirst.co.uk",
		"Name" => "Ben Hirst"
	]
]);

// When a Consignment is added
$objBatch->add([
	"Address" => "387 Testing Road",
	"Courier" => COURIER_ROYALMAIL,
	"Email" => "me@benhirst.co.uk",
	"Name" => "Ben Hirst"
]);

// When the batch ends, send it
$objBatch->send();


?>