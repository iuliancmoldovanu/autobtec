<?php
require_once( 'core/init.php' );
?>

<!doctype html>
<!--[if lt IE 7 ]>
<html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>
<html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>
<html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Newsletter registration form</title>
	<meta name="description" content="">
	<meta name="author" content="11638">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/enquiry.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/jquery-ui-1.8.17.custom.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
</head>
<body class="order">
<div id="order-page">
	<h3>Newsletter registration form</h3>

	<form id="form-order" method="post" action="">
		<input type="hidden" name="product_id" id="product_id" value="8">

		<div id="top">
			<div id="data-customer" class="fl">
				<div>
					<label for="firstName">First name</label>
					<input type="text" value="<?php echo Input::get( 'firstName' ) ?>" id="firstName" name="firstName">
				</div>
				<div>
					<label for="lastName">Last name</label>
					<input type="text" value="<?php echo Input::get( 'lastName' ) ?>" id="lastName" name="lastName">
				</div>
				<div>
					<label for="email">Email</label>
					<input type="text" value="<?php echo Input::get( 'email' ) ?>" id="email" name="email">
				</div>
			</div>
			<input type="submit" id="submit" name="submit" value="REGISTER">

			<div class="clear"></div>

			<?php

			$user = new User();
			$user->createNewsletter();

			?>


		</div>
	</form>
</div>


<script>window.jQuery || document.write('<script src="jquery/1.7.1/jquery.min.js"><\/script>')</script>
<script src="jquery/jquery-ui-1.8.17.custom.min.js"></script>
<script>
	$(function () {
		$("#datepicker").datepicker({
			showOn: "button",
			buttonImage: "css/images/calendar.png",
			buttonImageOnly: true,
			"dateFormat": 'D, dd M yy',
			'minDate': '1',
			"beforeShowDay": function (date) {
				var day = date.getDay();
				return [(day != 0 && day != 6)];
			},
			maxDate: "+30d"
		});
	});
</script>

</body>
</html>