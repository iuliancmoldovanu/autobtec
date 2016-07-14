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
	<link rel="stylesheet" href="css/enquiry.css">
	<link rel="stylesheet" href="css/jquery-ui-1.8.17.custom.css">
	<script src="jquery/modernizr-2.0.6.min.js"></script>
</head>
<body class="order">
<div id="order-page">

	<?php
	$cars = new Cars();
	if ( ! $cars->updateCar( 'cars', $car->first()->id ) ) {
	?>
		<h3>Update the car ID: <?php echo $car->first()->id; ?></h3>
		<form id="form-order" method="post" action="" enctype="multipart/form-data">
			<div id="top">
				<div id="updateContainer">
					<div id="updateFields">
						<label for="series">Series</label>
						<input type="text" class="login-inputs" name="series" id="series"
						       value="<?php echo $car->first()->series; ?>" placeholder="Series">
						<br>
						<label for="model">Model</label>
						<input type="text" class="login-inputs" name="model" id="model"
						       value="<?php echo $car->first()->model; ?>" placeholder="Model">
						<br>
						<label for="other">Other</label>
						<input type="text" class="login-inputs" name="other" id="other"
						       value="<?php echo $car->first()->other; ?>" placeholder="Other">
						<br>
						<label for="engine">Engine size</label>
						<input type="text" class="login-inputs" name="engine" id="engine"
						       value="<?php echo $car->first()->engine; ?>" placeholder="Engine size">
						<br>
						<label for="year">Year</label>
						<input type="text" class="login-inputs" name="year" id="year"
						       value="<?php echo $car->first()->year; ?>" placeholder="Year">
						<br>
						<label for="seat">Seats</label>
						<input type="text" class="login-inputs" name="seat" id="seat"
						       value="<?php echo $car->first()->seat; ?>" placeholder="Seats">
						<br>
						<label for="price">Price</label>
						<input type="text" class="login-inputs" name="price" id="price"
						       value="<?php echo $car->first()->price; ?>" placeholder="Price">

					</div>
					<div id="updateImage">
						<h3>Update image?
							<div id="uploadImage" style="display: none">
								<input type="file" name="fileToUpload" id="fileToUpload">
							</div>
						</h3>

						<label for="updateImageT">Yes</label>
						<input type="radio" name="updateImage" id="updateImageT" value="true">
						<label for="updateImageF">No</label>
						<input type="radio" name="updateImage" id="updateImageF" value="false" checked>

						<div id="showImage">
							<img class="carImage" src="<?php echo $car->first()->location; ?>">
						</div>
					</div>
					<!-- Error message comes here for any issues  -->
					<div class="clear error-text"></div>
				</div>

				<input type="submit" id="submit" name="submit" value="UPDATE">

				<div class="clear"></div>
			</div>
		</form>
	<?php } ?>
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

	document.getElementById('updateImageT').onclick = function () {
		document.getElementById('uploadImage').style.display = 'inline';
	};
	document.getElementById('updateImageF').onclick = function () {
		document.getElementById('uploadImage').style.display = 'none';
	};

</script>

</body>
</html>