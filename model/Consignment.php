<?php

namespace Model;

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
		$strCourier = Helper::getCourierClassName($this->strCourier);
		if(!class_exists($strCourier)) {
			throw new \Exception("Invalid Courier - $strCourier\n");	
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

?>