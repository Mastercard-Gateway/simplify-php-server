<!DOCTYPE html>
<html data-wf-site="5582f9e5792714e458bb85b8" data-wf-page="5582f9e5792714e458bb85b9">
<head>
	<meta charset="utf-8">
	<title>Simplify Test Payment Form</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
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

		.success {
			color: green;
		}

		.message {
			margin-top: 50px;
		}

		.w-button {
			background-color: #f60;
			border-radius: 3px;
		}

		.busy-container {
			display: none;
		}
	</style>
	<?php
	$publicKey = getenv('SIMPLIFY_API_PUBLIC_KEY');
	?>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//www.simplify.com/commerce/v1/simplify.js"></script>
	<script type="text/javascript">
		var $error, $success, $paymentBtn, $busyContainer;
		$(document).ready(function () {
			var $selYear = $('#cc-exp-year');
			$error = $(".error");
			$success = $(".success");
			$paymentBtn = $("#process-payment-btn");
			$busyContainer = $('.busy-container');

			var currentYear = new Date().getFullYear();
			for (var year = currentYear; year < currentYear + 10; year++) {
				$selYear.append("<option " + ((year === (currentYear + 1)) ? " selected " : "") + " value='" + year.toString().substr(2) + "'>" + year + "</option>");
			}

			$paymentBtn.click(function () {
				$busyContainer.fadeIn();
				$error.fadeOut().html("");
				$success.fadeOut().html("");
				// Disable the submit button
				$paymentBtn.attr("disabled", "disabled");
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
			$busyContainer.fadeOut();
			$paymentBtn.removeAttr("disabled");
			if (data.error) {
				console.error("Error creating card token", data);
				if (data.error.code == "validation") {
					var fieldErrors = data.error.fieldErrors,
						fieldErrorsLength = fieldErrors.length,
						errorMessage = "";
					for (var i = 0; i < fieldErrorsLength; i++) {
						errorMessage += " Field: '" + fieldErrors[i].field +
							"' is invalid - " + fieldErrors[i].message + "<br/>";
					}
					$error.html(errorMessage).fadeIn();
				}
			} else {
				var token = data["id"];
				var amount = $('#amount').val();
				var currency = $("#currency").val();
				var request = $.ajax({
					url: "/charge.php",
					type: "POST",
					data: { simplifyToken: token, amount: amount, currency: currency}
				});

				request.done(function (response) {
					console.log("Response = ", response);
					if (response.id) {
						$success.html("Payment successfully processed & payment id = " + response.id + " !").fadeIn();
					}
					else if (response.status) {
						$error.html("Payment failed with status = " + response.status + " !").fadeIn();
					}
					else {
						$error.html("Payment failed with response... <br/> " + JSON.stringify(response)).fadeIn();
					}
				});

				request.fail(function (jqXHR, status) {
					console.error('Payment processing failed = ', jqXHR, status);
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
				<td><label class="text">Currency: </label></td>
				<td>
					<select id="currency">
						<option value="AED">AED (UAE Dirham)</option>
						<option value="AFN">AFN (Afghani)</option>
						<option value="ALL">ALL (Lek)</option>
						<option value="AMD">AMD (Armenian Dram)</option>
						<option value="ANG">ANG (Netherlands Antillean Guilder)</option>
						<option value="AOA">AOA (Kwanza)</option>
						<option value="ARS">ARS (Argentine Peso)</option>
						<option value="AUD">AUD (Australian Dollar)</option>
						<option value="AWG">AWG (Aruban Florin)</option>
						<option value="AZN">AZN (Azerbaijanian Manat)</option>
						<option value="BAM">BAM (Convertible Mark)</option>
						<option value="BBD">BBD (Barbados Dollar)</option>
						<option value="BDT">BDT (Taka)</option>
						<option value="BGN">BGN (Bulgarian Lev)</option>
						<option value="BHD">BHD (Bahraini Dinar)</option>
						<option value="BIF">BIF (Burundi Franc)</option>
						<option value="BMD">BMD (Bermudian Dollar)</option>
						<option value="BND">BND (Brunei Dollar)</option>
						<option value="BOB">BOB (Boliviano)</option>
						<option value="BRL">BRL (Brazilian Real)</option>
						<option value="BSD">BSD (Bahamian Dollar)</option>
						<option value="BWP">BWP (Pula)</option>
						<option value="BYR">BYR (Belarussian Ruble)</option>
						<option value="BZD">BZD (Belize Dollar)</option>
						<option value="CAD">CAD (Canadian Dollar)</option>
						<option value="CDF">CDF (Congolese Franc)</option>
						<option value="CHF">CHF (Swiss Franc)</option>
						<option value="CLP">CLP (Chilean Peso)</option>
						<option value="CNY">CNY (Yuan Renminbi)</option>
						<option value="COP">COP (Colombian Peso)</option>
						<option value="CRC">CRC (Costa Rican Colon)</option>
						<option value="CUP">CUP (Cuban Peso)</option>
						<option value="CVE">CVE (Cabo Verde Escudo)</option>
						<option value="CZK">CZK (Czech Koruna)</option>
						<option value="DJF">DJF (Djibouti Franc)</option>
						<option value="DKK">DKK (Danish Krone)</option>
						<option value="DOP">DOP (Dominican Peso)</option>
						<option value="DZD">DZD (Algerian Dinar)</option>
						<option value="EGP">EGP (Egyptian Pound)</option>
						<option value="ERN">ERN (Nakfa)</option>
						<option value="ETB">ETB (Ethiopian Birr)</option>
						<option value="EUR">EUR (Euro)</option>
						<option value="FJD">FJD (Fiji Dollar)</option>
						<option value="FKP">FKP (Falkland Islands Pound)</option>
						<option value="GBP">GBP (Pound Sterling)</option>
						<option value="GEL">GEL (Lari)</option>
						<option value="GHS">GHS (Ghana Cedi)</option>
						<option value="GIP">GIP (Gibraltar Pound)</option>
						<option value="GMD">GMD (Dalasi)</option>
						<option value="GNF">GNF (Guinea Franc)</option>
						<option value="GTQ">GTQ (Quetzal)</option>
						<option value="GYD">GYD (Guyana Dollar)</option>
						<option value="HKD">HKD (Hong Kong Dollar)</option>
						<option value="HNL">HNL (Lempira)</option>
						<option value="HRK">HRK (Croatian Kuna)</option>
						<option value="HUF">HUF (Forint)</option>
						<option value="IDR">IDR (Rupiah)</option>
						<option value="ILS">ILS (New Israeli Sheqel)</option>
						<option value="INR">INR (Indian Rupee)</option>
						<option value="IQD">IQD (Iraqi Dinar)</option>
						<option value="IRR">IRR (Iranian Rial)</option>
						<option value="ISK">ISK (Iceland Krona)</option>
						<option value="JMD">JMD (Jamaican Dollar)</option>
						<option value="JOD">JOD (Jordanian Dinar)</option>
						<option value="JPY">JPY (Yen)</option>
						<option value="KES">KES (Kenyan Shilling)</option>
						<option value="KGS">KGS (Som)</option>
						<option value="KHR">KHR (Riel)</option>
						<option value="KMF">KMF (Comoro Franc)</option>
						<option value="KPW">KPW (North Korean Won)</option>
						<option value="KRW">KRW (Won)</option>
						<option value="KWD">KWD (Kuwaiti Dinar)</option>
						<option value="KYD">KYD (Cayman Islands Dollar)</option>
						<option value="KZT">KZT (Tenge)</option>
						<option value="LAK">LAK (Kip)</option>
						<option value="LBP">LBP (Lebanese Pound)</option>
						<option value="LKR">LKR (Sri Lanka Rupee)</option>
						<option value="LRD">LRD (Liberian Dollar)</option>
						<option value="LYD">LYD (Libyan Dinar)</option>
						<option value="MAD">MAD (Moroccan Dirham)</option>
						<option value="MDL">MDL (Moldovan Leu)</option>
						<option value="MGA">MGA (Malagasy Ariary)</option>
						<option value="MKD">MKD (Denar)</option>
						<option value="MMK">MMK (Kyat)</option>
						<option value="MNT">MNT (Tugrik)</option>
						<option value="MOP">MOP (Pataca)</option>
						<option value="MRO">MRO (Ouguiya)</option>
						<option value="MUR">MUR (Mauritius Rupee)</option>
						<option value="MVR">MVR (Rufiyaa)</option>
						<option value="MWK">MWK (Kwacha)</option>
						<option value="MXN">MXN (Mexican Peso)</option>
						<option value="MYR">MYR (Malaysian Ringgit)</option>
						<option value="MZN">MZN (Mozambique Metical)</option>
						<option value="NGN">NGN (Naira)</option>
						<option value="NIO">NIO (Cordoba Oro)</option>
						<option value="NOK">NOK (Norwegian Krone)</option>
						<option value="NPR">NPR (Nepalese Rupee)</option>
						<option value="NZD">NZD (New Zealand Dollar)</option>
						<option value="OMR">OMR (Rial Omani)</option>
						<option value="PEN">PEN (Nuevo Sol)</option>
						<option value="PGK">PGK (Kina)</option>
						<option value="PHP">PHP (Philippine Peso)</option>
						<option value="PKR">PKR (Pakistan Rupee)</option>
						<option value="PLN">PLN (Zloty)</option>
						<option value="PYG">PYG (Guarani)</option>
						<option value="QAR">QAR (Qatari Rial)</option>
						<option value="RON">RON (New Romanian Leu)</option>
						<option value="RSD">RSD (Serbian Dinar)</option>
						<option value="RUB">RUB (Russian Ruble)</option>
						<option value="RWF">RWF (Rwanda Franc)</option>
						<option value="SAR">SAR (Saudi Riyal)</option>
						<option value="SBD">SBD (Solomon Islands Dollar)</option>
						<option value="SCR">SCR (Seychelles Rupee)</option>
						<option value="SDG">SDG (Sudanese Pound)</option>
						<option value="SEK">SEK (Swedish Krona)</option>
						<option value="SGD">SGD (Singapore Dollar)</option>
						<option value="SHP">SHP (Saint Helena Pound)</option>
						<option value="SLL">SLL (Leone)</option>
						<option value="SOS">SOS (Somali Shilling)</option>
						<option value="SRD">SRD (Surinam Dollar)</option>
						<option value="SSP">SSP (South Sudanese Pound)</option>
						<option value="STD">STD (Dobra)</option>
						<option value="SYP">SYP (Syrian Pound)</option>
						<option value="SZL">SZL (Lilangeni)</option>
						<option value="THB">THB (Baht)</option>
						<option value="TJS">TJS (Somoni)</option>
						<option value="TMT">TMT (Turkmenistan New Manat)</option>
						<option value="TND">TND (Tunisian Dinar)</option>
						<option value="TOP">TOP (Pa'anga)</option>
						<option value="TRY">TRY (Turkish Lira)</option>
						<option value="TTD">TTD (Trinidad and Tobago Dollar)</option>
						<option value="TWD">TWD (New Taiwan Dollar)</option>
						<option value="TZS">TZS (Tanzanian Shilling)</option>
						<option value="UAH">UAH (Hryvnia)</option>
						<option value="UGX">UGX (Uganda Shilling)</option>
						<option selected="selected" value="USD">USD (US Dollar)</option>
						<option value="UYU">UYU (Peso Uruguayo)</option>
						<option value="UZS">UZS (Uzbekistan Sum)</option>
						<option value="VEF">VEF (Bolivar)</option>
						<option value="VND">VND (Dong)</option>
						<option value="VUV">VUV (Vatu)</option>
						<option value="WST">WST (Tala)</option>
						<option value="XAF">XAF (CFA Franc BEAC)</option>
						<option value="XCD">XCD (East Caribbean Dollar)</option>
						<option value="XOF">XOF (CFA Franc BCEAO)</option>
						<option value="XPF">XPF (CFP Franc)</option>
						<option value="YER">YER (Yemeni Rial)</option>
						<option value="ZAR">ZAR (Rand)</option>
						<option value="ZMW">ZMW (Zambian Kwacha)</option>
						<option value="ZWL">ZWL (Zimbabwe Dollar)</option>
					</select>
				</td>
				<td>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<button id="process-payment-btn" class="w-button">Run Test Payment</button>
				</td>
			</tr>
		</table>
		<div class="footer-section">
			<div class="busy-container"><img src="images/ajax-loader.gif"/></div>
			<div class="success"></div>
			<div class="error"></div>
			<div class="text">For more test cards, please checkout this <a class="link" target="_new"
																		   href="https://www.simplify.com/commerce/docs/tutorial/index#testing">page.</a>
			</div>
		</div>
	</form>
</div>
<div class="w-section footer-section">
	<div class="logo-container"><img class="logo" src="images/simplifyLogo@2x.png" width="102">
	</div>
</div>
</body>
</html>
