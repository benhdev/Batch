<?php

namespace Model;

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

		foreach($arrConsignmentsPerCourier as $strIndex => $arrConsignments) {

			$strCourier = Helper::getCourierClassName($strIndex);
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

?>