<?php

const COURIER_FEDEX 	= "FedEx";
const COURIER_ROYALMAIL = "RoyalMail";

include_once('includes.php');

use \Model\Batch as ModelBatch;

// start a new batch with or without consignments already
$modelBatch = new ModelBatch([
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
$modelBatch->add([
	"Address" => "387 Testing Road",
	"Courier" => COURIER_ROYALMAIL,
	"Email" => "me@benhirst.co.uk",
	"Name" => "Ben Hirst"
]);

// When the batch ends, send it
$modelBatch->send();


?>