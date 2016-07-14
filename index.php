<?php
// this file is having the global configuration for: mysql, session
// Also there is sql_autoload function which will help to call any class from class folder
// without using "require" function, and allow just to instantiating the class.
require_once( 'core/init.php' );

$loginError = '';
$user       = new User();
$cars       = new Cars( $user->isLoggedIn() );

if ( ! $user->isLoggedIn() ) {
	if ( Input::exist() ) {
		$validate   = new Validate();
		$validation = $validate->check( $_POST, array(
			'username' => array( 'required' => true, 'name' => 'username' ),
			'password' => array( 'required' => true, 'name' => 'password' )
		) );
		if ( $validation->passed() ) {
			$login = $user->login( escape( Input::get( 'username' ) ), escape( Input::get( 'password' ) ) );
			if ( $login ) {
				Redirect::to( $_SERVER['PHP_SELF'] );
			} else {
				$loginError = 'Login failed! Username or password not found.';
			}
		} else {
			$loginError = $validation->getError()[0] . " ";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="keyword" content="">
	<meta name="author" content="Iulian C Moldovanu, Student ID: 11638">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/enquiry.css">
	<link rel="stylesheet" href="fancybox/jquery.fancybox-1.3.4.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
	<link rel="stylesheet" href="css/pagination.css">
	<title>BMW by 11638</title>
</head>
<body>
<div id="container">
	<header>
		<?php
		if ( ! $user->isLoggedIn() ) {
			?>
			<form id="login" method="post" class="fr">
				<label for="username">AGENT LOGIN</label>
				<input type="text" class="login-inputs" name="username" id="username"
				       value="<?php echo Input::get( 'username' ); ?>" placeholder="Username">
				<input type="password" class="login-inputs" name="password" id="password" value=""
				       placeholder="Password">
				<input type="submit" value="Sign in">
				<!-- Error message comes here for any issues  -->
				<div class="clear error-text"><?php echo $loginError; ?></div>
			</form>
		<?php } else { ?>
			<div id="login" class="fr current-user">
				<h3>
					Hello <?php echo $user->getData()->username; ?>, you logged in as an
					<?php echo ( $user->getData()->access == 1 ) ? 'administrator' : 'agent'; ?>.
				</h3>
				<a href="logout.php" title="Logout Agent">Logout</a>
			</div>
		<?php } ?>
		<div class="clear"></div>
		<div id="logo">
			<a href="index.php" title="Home page"></a>
		</div>
		<nav class="fr">
			<ul id="menu">
				<li><a href="newsletter.php" class="buttom-enquire iframe">Sign up to our newsletter</a>
				</li>
				<li><a href="contact.php" title="">Contact Us</a></li>
			</ul>
		</nav>
		<div class="clear"></div>
	</header>


	<div id="main">

		<?php if ( ! isset( $_GET['newCar'] ) && ! isset( $_GET['crudUser'] ) ) { ?>

			<div class="fr sorting">
				Sort by
				<select id="sorting">
					<option value="yearA"
						<?php if ( isset( $_GET['sort'] ) && $_GET['sort'] == 'yearA' ) {
							echo 'selected';
						}
						?>>Newest (default)
					</option>
					<option value="priceD"
						<?php if ( isset( $_GET['sort'] ) && $_GET['sort'] == 'priceD' ) {
							echo 'selected';
						}
						?>>Price (low-high)
					</option>

					<option value="priceA"
						<?php if ( isset( $_GET['sort'] ) && $_GET['sort'] == 'priceA' ) {
							echo 'selected';
						}
						?>>Price (high-low)
					</option>
					<option value="engineD"
						<?php if ( isset( $_GET['sort'] ) && $_GET['sort'] == 'engineD' ) {
							echo 'selected';
						}
						?>>Engine (small-big)
					</option>
					<option value="engineA"
						<?php if ( isset( $_GET['sort'] ) && $_GET['sort'] == 'engineA' ) {
							echo 'selected';
						}
						?>>Engine (big-small)
					</option>
					<option value="modelD"
						<?php if ( isset( $_GET['sort'] ) && $_GET['sort'] == 'modelD' ) {
							echo 'selected';
						}
						?>>Name (A-Z)
					</option>
				</select>
			</div>
		<?php } ?>
		<script>
			<?php
			if ( $user->isLoggedIn()) {
				if(strpos( $cars->url(), "deleteCar=" ) > 0){
			?>
			var response = confirm("Are you sure you want to delete this car?");
			if (response == false) {
				window.location = "index.php";
			} else {
				window.location = "carDelete.php<?php echo '?deleteCar=' . $_GET['deleteCar']; ?>";
			}
			<?php
				}
			} else{
				if(strpos( $cars->url(), "deleteCar=" )){
					Session::put('carDeleted', 'You don\'t have rights to delete items!');
				}
			}

			?>

			document.getElementById('sorting').onchange = function () {
				var location = window.location.href;

				if (location.indexOf('sort=') >= 0) {
					var sign = location.substr(location.indexOf('sort=') - 1, 1);
					location = location.substring(0, location.indexOf('sort='));
				} else {
					if (location.indexOf('?') >= 0) {
						location += '&';
					} else {
						location += '?';
					}
				}

				switch (document.getElementById('sorting').value) {
					case 'priceA':
						window.location = (location + 'sort=priceA');
						break;
					case 'priceD':
						window.location = (location + 'sort=priceD');
						break;
					case 'yearA':
						window.location = (location + 'sort=yearA');
						break;
					case 'engineA':
						window.location = (location + 'sort=engineA');
						break;
					case 'engineD':
						window.location = (location + 'sort=engineD');
						break;
					case 'modelD':
						window.location = (location + 'sort=modelD');
						break;
				}
				var str = '<?php
							if ( strpos( $cars->url(), "page=" ) < strpos( $cars->url(), "sort=" )
							&& strpos( $cars->url(), "page=") > 0 )
							{
								$getPage = substr( $cars->url(), strpos( $cars->url(), "page=" ), strpos( $cars->url(), "sort=" ) );
								$getSort = substr( $getPage, strpos( $getPage, "sort=" ), count( str_split($getPage)) );
								$cars->sortGlobal = $getSort . '&';
							}
						?>';
			}
		</script>

		<div id="page-vehicles" class="content">
			<h3>MADE/TYPE</h3>
			<aside id="product-category">

				<ul>
					<li class="<?php if ( ! isset( $_GET['series'] ) && ! isset( $_GET['newCar'] ) && ! isset( $_GET['crudUser'] ) ) {
						echo 'current';
					} ?>">
						<a href="index.php?page=1" title="">Show all</a>
					</li>
				</ul>

		<?php
		foreach ( $cars->getUniqueSeries() as $serie ) {
			?>
			<ul>
				<li class="<?php if ( isset( $_GET['series'] ) && $_GET['series'] == $serie ) {
					echo 'current';
				} ?>">
					<a href="index.php?series=<?php echo $serie; ?>" title="">
						Series <?php echo $serie; ?> </a>

					<?php foreach ( $cars->getUniqueSeries( $serie ) as $model ) { ?>
						<ul>
							<li class="<?php if ( isset( $_GET['model'] ) && $_GET['model'] == $model ) {
								echo 'current';
							} ?>">
								<a href="index.php?series=<?php echo $serie; ?>&model=<?php echo $model; ?>"
								   title="">
									<?php echo $model; ?> </a>
							</li>
						</ul>
					<?php } ?>
				</li>
			</ul>
		<?php
		}
		?>

				<?php
				if ( $user->isLoggedIn() ) {
					?>
					<ul>
						<li class="<?php if ( isset( $_GET['newCar'] ) ) {
							echo 'current';
						} ?>">
							<a href="index.php?newCar" title="Add new vehicle">+ Add new vehicle</a>
						</li>
						<?php
						if ( $user->getData()->access == 1 ) {
							?>
							<li class="<?php if ( isset( $_GET['crudUser'] ) ) {
								echo 'current';
							} ?>">
								<a href="index.php?crudUser" title="CRUD user">+ CRUD user</a>
							</li>
						<?php
						}
						?>
					</ul>

				<?php
				}
				?>

			</aside>

			<div class="fr" id="content-vehicles">

				<?php
				if ( Session::exist( 'carDeleted' ) ) {
					echo '<p>' . Session::get( 'carDeleted' ) . '</p>';
					Session::delete( 'carDeleted' );
				}
				if ( Session::exist( 'userDeleted' ) ) {
					echo '<p>' . Session::get( 'userDeleted' ) . '</p>';
					Session::delete( 'userDeleted' );
				}

				if ( isset( $_GET['newCar'] ) ) {
					echo '<div id="updateContainer">';
					$cars->addNewCar();
					echo '</div>';
				} else if ( isset( $_GET['crudUser'] ) ) {
					echo '<div id="updateContainer">';
					?>
					<nav style="margin: 0; padding: 0">
						<ul id="menu">
							<?php
							if ( $_GET['crudUser'] == 'addUser' ) {
								echo '<h2>Add new user</h2>
							<a href="index.php?crudUser" title="Go back"><button>Go back</button></a>';
								//echo '<li><a href="index.php?crudUser=addUser" title="Add new user">Create user</a></li>';
								//echo '<li><a href="index.php?crudUser=modifyUser" title="Modify user">Modify user</a></li>';
							} else if ( $_GET['crudUser'] == 'modifyUser' ) {
								echo '<h2>Modify users</h2>
							<a href="index.php?crudUser" title="Go back"><button>Go back</button></a>';
							} else {
								echo '<li><a href="index.php?crudUser=addUser" title="Add new user">Create user</a></li>';
								echo '<li><a href="index.php?crudUser=modifyUser" title="Modify user">Modify user</a></li>';
							}

							?>
							<br/><br/>
						</ul>
					</nav>
					<?php
					$user->userCRUD( $_GET['crudUser'] );
					echo '</div>';
				} else {
					if ( isset( $_GET['model'] ) ) {
						$cars->showCars( 'model', $_GET['model'] );
					} else {
						if ( ! isset( $_GET['series'] ) ) {
							$cars->showCars( null, null );
						} else {
							$cars->showCars( 'series', $_GET['series'] );
						}
					}
					echo $cars->pagination();
				}
				?>

			</div>
			<div class="clear"></div>

		</div>
	</div>
</div>
<!--! end of #container -->
<div id="footer">
	<div id="article">
		<div class="fr">
			<span id="copyright">&copy; 2015 Student ID: 11638</span>
			<span>Web Design & Development "Final project" by Iulian C Moldovanu</span>
		</div>
		<div class="clear"></div>
	</div>
</div>

<script>window.jQuery || document.write('<script src="jquery/1.7.1/jquery.min.js"><\/script>')</script>
<script src="jquery/jquery-ui-1.8.17.custom.min.js"></script>


<script src="jquery/jquery.fancybox-1.3.4.pack.js"></script>
<script src="jquery/custom.js"></script>

<!--[if lt IE 7 ]>
<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
<script>window.attachEvent('onload', function () {
	CFInstall.check({mode: 'overlay'})
})</script>
<![endif]-->
</body>
</html>