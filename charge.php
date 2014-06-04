<?php
require_once("./lib/Simplify.php");
Simplify::$publicKey = 'YOUR_PUBLIC_API_KEY';
Simplify::$privateKey = 'YOUR_PRIVATE_API_KEY';
$token = $_POST['simplifyToken'];
$payment = Simplify_Payment::createPayment(array(
    'amount' => '1000',
    'token' => $token,
    'description' => 'prod description',
    'currency' => 'USD'
));
if ($payment->paymentStatus == 'APPROVED') {
    echo "Payment approved\n";
}
?>