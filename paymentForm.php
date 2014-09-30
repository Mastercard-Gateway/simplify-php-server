<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Simplify Commerce Getting Started Form</title>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.simplify.com/commerce/v1/simplify.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function simplifyResponseHandler(data) {
            var $paymentForm = $("#simplify-payment-form");
            $(".error").remove();
			$("#process-payment-btn").removeAttr("disabled");
            if (data.error) {
                if (data.error.code == "validation") {
                    var fieldErrors = data.error.fieldErrors,
                            fieldErrorsLength = fieldErrors.length,
                            errorList = "";
                    for (var i = 0; i < fieldErrorsLength; i++) {
                        errorList += "<div class='error'>Field: '" + fieldErrors[i].field +
                                "' is invalid - " + fieldErrors[i].message + "</div>";
                    }
                    $paymentForm.after(errorList);
                }
            } else {
                // The token contains id, last4, and card type
                var token = data["id"];
                console.log('#### token = ', token);
                var amount = $('#amount').val();
                console.log('##### Charging amount = ', amount);
                $.post("/charge.php", { simplifyToken: token, amount: amount}, function (data){
                    console.log('#### Success', data);
                });
            }
        }

		<?php
			$publicKey = getenv('SIMPLIFY_API_PUBLIC_KEY');
		?>
        $(document).ready(function () {
            $("#simplify-payment-form").on("submit", function () {
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
                // Prevent the form from submitting
                return false;
            });
        });
    </script>
</head>
<body>
<div class="container">
    <h1>Run Payments using Simplify Commerce</h1>

    <form role="form" class="form-horizontal" id="simplify-payment-form" action="http://mysterious-savannah-5521.herokuapp.com/charge.php" method="POST">
        <div class="form-group">
            <label>Amount</label>
            <input id="amount"  class="form-control" type="text" maxlength="10" autocomplete="off" value="" autofocus
                   placeholder="Enter Amount"/>
        </div>
        <div class="form-group">
            <label>Credit Card Number: </label>
            <input id="cc-number" class="form-control" type="text" maxlength="20" autocomplete="off" value="5555555555554444"/>
        </div>
        <div class="form-group">
            <label>CVC: </label>
            <input id="cc-cvc" class="form-control" type="text" maxlength="4" autocomplete="off" value="123"/>
        </div>
        <div class="form-group">
            <label>Expiry Date: </label>
            <select class="form-control" id="cc-exp-month">
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
            <select class="form-control" id="cc-exp-year">
                <option value="15">2015</option>
                <option value="16">2016</option>
                <option value="17">2017</option>
                <option value="18">2018</option>
                <option value="19">2019</option>
                <option value="20">2020</option>
                <option value="21">2021</option>
                <option value="22">2022</option>
            </select>
        </div>
        <button class="btn btn-primary" id="process-payment-btn" type="btn">Process Payment</button>
    </form>
</div>
</body>
</html>