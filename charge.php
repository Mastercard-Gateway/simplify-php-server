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

header('Content-Type: application/json');
error_reporting(E_ALL);

require_once("./sdk/lib/Simplify.php");

Simplify::$publicKey = getenv('SIMPLIFY_API_PUBLIC_KEY');
Simplify::$privateKey = getenv('SIMPLIFY_API_PRIVATE_KEY');


if (!isset($_POST["amount"]) || !isset($_POST['simplifyToken'])) {
	echo "Please submit POST values with amount & simplifyToken params!";
	return;
}

$token = $_POST['simplifyToken'];
$payment = $_POST["amount"];

$paymentPayload = array(
	'amount' => $payment,
	'token' => $token,
	'description' => 'payment description',
	'currency' => 'USD'
);
try {
	$payment = Simplify_Payment::createPayment($paymentPayload);
	if ($payment->paymentStatus == 'APPROVED') {
		//return payment id
		echo $payment->{'id'};
		return;
	}
	header('HTTP/1.1 400 Payment failed with status = ' . $payment->paymentStatus . '!');
} catch (Exception $e) {
	//	echo ' Caught exception: ', $e->getMessage(), "\n", $e;
	header('HTTP/1.1 400 Payment failed with status = ' . $e->getMessage() . ' ' . $e);
}
?>
