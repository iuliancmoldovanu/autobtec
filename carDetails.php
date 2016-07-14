<?php
require_once( 'core/init.php' );
$car = DB::getInstance()->get( 'cars', array( 'id', '=', $_GET['id'] ) );
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
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/enquiry.css">
	<link rel="stylesheet" href="css/jquery-ui-1.8.17.custom.css">
	<script src="jquery/modernizr-2.0.6.min.js"></script>
</head>
<body class="order">
<div id="order-page">
	<h3>Vehicle Details</h3>

	<div id="bottom">
		<?php
		?>
		<div>
			<div class="carDetails">
				<h3>
				Model: <?php echo $car->first()->model; ?>
				<br>
				Engine size: <?php echo $car->first()->engine; ?>
					</h3>
			</div>
			<img class="carImage" src="<?php echo $car->first()->location; ?>">
		</div>
		<div class="clear"></div>
	</div>
</div>


<script>window.jQuery || document.write('<script src="jquery/1.7.1/jquery.min.js"><\/script>')</script>
<script src="jquery/jquery-ui-1.8.17.custom.min.js"></script>
<script>

	$(function () {
		$("#date").datepicker({
			//
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