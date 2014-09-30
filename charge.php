<?php

/*
 * Copyright (c) 2013, MasterCard International Incorporated
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list of
 * conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 * Neither the name of the MasterCard International Incorporated nor the names of its
 * contributors may be used to endorse or promote products derived from this software
 * without specific prior written permission.
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT
 * SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 * TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
 * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER
 * IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING
 * IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */

/*    Instructions:
  *    1. Replace public key and private key with your respective API keys
  *    2. This sample code charges $10 to the card token submitted. You can pass the charge parameter by uncommenting
  *       the charge parameter
  */
header('Content-Type: application/json');
error_reporting(E_ALL);

require_once("./lib/Simplify.php");

Simplify::$publicKey = getenv('SIMPLIFY_API_PUBLIC_KEY');
Simplify::$privateKey = getenv('SIMPLIFY_API_PRIVATE_KEY');


$token = $_POST['simplifyToken'];
//You can get the charge from the client by uncommenting line below
// $charge = $_POST['charge'];

$charge = 1000;

if (isset($_POST["amount"]) && !empty($_POST["amount"])) {
	$charge = $_POST["amount"];
}

$c = array(
	'amount' => $charge,
	'token' => $token,
	'description' => 'product description',
	'currency' => 'USD'

);
try {
	$charge = Simplify_Payment::createPayment($c);

	$chargeId = $charge->{'id'};
	echo $charge->{'paymentStatus'} . " charged :" . $charge->{'amount'} / 100;

} catch (Exception $e) {
	echo ' Caught exception: ', $e->getMessage(), "\n", $e;
}

?>
