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
	<title>Test Drive Form</title>
	<meta name="description" content="">
	<meta name="author" content="11638">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/enquiry.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/jquery-ui-1.8.17.custom.css">
	<script src="jquery/modernizr-2.0.6.min.js"></script>
</head>
<body class="order">
<div id="order-page">
	<h3>TEST DRIVE BOOKING FORM</h3>

	<form id="form-order" method="post" action="">
		<input type="hidden" name="product_id" id="product_id" value="8">

		<div id="top">
			<div id="data-customer" class="fl">
				<p>To book a test drive for this vehicle,<br> fill in the form below:</p>

				<div>
					<label for="name">Name</label>
					<input type="text" value="" id="name" name="name">
				</div>
				<div>
					<label for="phone">Phone</label>
					<input type="text" value="" id="phone" name="phone">
				</div>
				<div>
					<label for="email">Email</label>
					<input type="text" value="" id="email" name="email">
				</div>
			</div>
			<div>
				<div class="carDetails">
					<?php $car = DB::getInstance()->get( 'cars', array( 'id', '=', $_GET['id'] ) ); ?>
					Series: <?php echo $car->first()->series; ?>
					<br>
					Model: <?php echo $car->first()->model; ?>
					<br>
					Year: <?php echo $car->first()->year; ?>
					<br>
					Engine size: <?php echo $car->first()->engine; ?>
					<br>
					Price: <?php echo $car->first()->price; ?>

				</div>
				<img class="carImage" src="<?php echo $car->first()->location; ?>">
			</div>
			<div class="clear"></div>
		</div>

		<div id="bottom">
			<div class="fl">
				<div class="fl">
					<label for="date"><strong>Date</strong></label>
					<input type="text" value="" id="datepicker" name="datepicker" readonly>
				</div>
				<div class="fl">
					<strong>Time</strong>
					<select class="form-select " id="time" name="time">
						<option value=""></option>
						<script type="text/javascript">
							for (var i = 9; i < 18; i++) {
								if (i < 10) {
									document.write('<option value="0' + i + ':00">0' + i + ':00</option>')
								} else {
									document.write('<option value="' + i + ':00">' + i + ':00</option>');
								}
							}
						</script>
					</select>
				</div>
			</div>
			<input type="submit" id="submit" name="submit" value="SUBMIT">

			<div class="clear"></div>

			<?php
			$user = new User();
			$user->createTestDrive();
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