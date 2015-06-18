<!DOCTYPE html>
<html data-wf-site="5582f9e5792714e458bb85b8" data-wf-page="5582f9e5792714e458bb85b9">
<head>
	<meta charset="utf-8">
	<title>Simplify Test Payment Form</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="generator" content="Webflow">
	<link rel="stylesheet" type="text/css" href="css/normalize.css">
	<link rel="stylesheet" type="text/css" href="css/webflow.css">
	<link rel="stylesheet" type="text/css" href="css/simplify-test.webflow.css">
	<script src="//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
	<script>
		WebFont.load({
			google: {
				families: ["Inconsolata:400,400italic,700,700italic"]
			}
		});
	</script>
	<script type="text/javascript" src="js/modernizr.js"></script>
	<link rel="apple-touch-icon" href="//daks2k3a4ib2z.cloudfront.net/img/webclip.png">
	<style>
		body {
			font-family: 'Avenir Next', Avenir, 'Helvetica Neue', Helvetica, Arial, sans-serif;
			text-rendering: optimizeLegibility;
		}

		h1 {
			font-family: 'Avenir Next', Avenir, 'Helvetica Neue', Helvetica, Arial, sans-serif;
		}

		.footer-section {
			margin-top: 10px;
		}

		.error {
			color: red;
		}

		.message {
			margin-top: 50px;
		}
		.w-button {
			background-color: #f60;
			border-radius: 3px;
		}
	</style>
	<?php
	$publicKey = getenv('SIMPLIFY_API_PUBLIC_KEY');
	?>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//www.simplify.com/commerce/v1/simplify.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			var selYear = $('#cc-exp-year');

			var currentYear = new Date().getFullYear();
			for (var year = currentYear; year < currentYear + 10; year++) {
				selYear.append("<option " + ((year === (currentYear + 1)) ? " selected " : "") + " value='" + year.toString().substr(2) + "'>" + year + "</option>");
			}

			$("#process-payment-btn").click(function () {
				// Disable the submit button
				$("#process-payment-btn").attr("disabled", "disabled");
				// Generate a card token & handle the response
				SimplifyCommerce.generateToken({
					key: "<?echo $publicKey?>",
					card: {
						number: $("#cc-number").val(),
						cvc: $("#cc-cvc").val(),
						expMonth: $("#cc-exp-month").val(),
						expYear: $("#cc-exp-year").val()
					}
				}, simplifyResponseHandler);
			});

		});

		function simplifyResponseHandler(data) {
			var $error = $(".error");
			$error.html("");
			$("#process-payment-btn").removeAttr("disabled");
			if (data.error) {
				console.error("Error creating card token", data);
				if (data.error.code == "validation") {
					var fieldErrors = data.error.fieldErrors,
						fieldErrorsLength = fieldErrors.length,
						errorMessage = "";
					for (var i = 0; i < fieldErrorsLength; i++) {
						errorMessage += " Field: '" + fieldErrors[i].field +
							"' is invalid - " + fieldErrors[i].message;
					}
					$error.text(errorMessage);
				}
			} else {
				var token = data["id"];
				var amount = $('#amount').val();
				var request = $.ajax({
					url: "/charge.php",
					type: "POST",
					data: { simplifyToken: token, amount: amount},
					dataType: "html"
				});

				request.done(function (msg) {
					alert("Payment successfully processed!")
				});

				request.fail(function (jqXHR, textStatus) {
					console.error('Payment processing failed = ', jqXHR, textStatus);
				});
			}
		}
	</script>
</head>
<body>
<div class="w-container message">
	<div>
		<h1 class="main-message">Run Test Payments on Simplify Commerce</h1>
	</div>
	<form role="form" class="w-form" id="simplify-payment-form">
		<table width="100%">
			<tr>
				<td><label class="text">Amount in cents (i.e. 50 = $0.50): </label></td>
				<td><label class="text"><input id="amount" class="w-input" type="text" maxlength="10" autocomplete="off"
											   value="100" autofocus
											   placeholder="Enter Amount"/>
					</label></td>
			</tr>
			<tr>
				<td><label class="text">Credit Card Number: </label></td>
				<td>
					<input id="cc-number" type="text" class="w-input" maxlength="20" autocomplete="off"
						   value="5555555555554444"/>
					</label></td>
			</tr>
			<tr>
				<td><label class="text">CVC: </label></td>
				<td>
					<input id="cc-cvc" type="text" class="w-input" maxlength="4" autocomplete="off" value="123"/>
					</label></td>
			</tr>
			<tr>
				<td><label class="text">Expiry Date: </label></td>
				<td>
					<select id="cc-exp-month" class="w-select">
						<option value="01">Jan</option>
						<option value="02">Feb</option>
						<option value="03">Mar</option>
						<option value="04">Apr</option>
						<option value="05">May</option>
						<option value="06">Jun</option>
						<option value="07">Jul</option>
						<option value="08">Aug</option>
						<option value="09">Sep</option>
						<option value="10">Oct</option>
						<option value="11">Nov</option>
						<option value="12">Dec</option>
					</select>
					<select id="cc-exp-year" class="w-select">
					</select>
			</tr>
			<tr>
				<td></td>
				<td>
					<button id="process-payment-btn" class="w-button">Process Payment</button>
				</td>
			</tr>
		</table>
		<div class="footer-section">
			<div class="text">For more test cards, please checkout this <a class="link" target="_new" href="https://www.simplify.com/commerce/docs/tutorial/index#testing">page</a>.</div>
			<div class="error"></div>
		</div>
	</form>
</div>
<div class="w-section footer-section">
	<div class="logo-container"><img class="logo" src="images/simplifyLogo@2x.png" width="102">
	</div>
</div>
</body>
</html>