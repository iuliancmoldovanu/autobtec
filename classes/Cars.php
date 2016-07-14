<?php

/**
 * Created by PhpStorm.
 * User: Ciprian
 * Date: 08/04/2015
 * Time: 11:39
 */
class Cars {
	private $_db;
	private $_adminRights = false;
	public $sortGlobal = '';
	private $_pagination = [ ];

	public function __construct( $adminRights = false ) {
		$this->_db          = DB::getInstance();
		$this->_adminRights = $adminRights;
	}

	public function showCars( $item = null, $getValue = null ) {
		if ( $item && $getValue ) {
			$this->_db->get( 'cars', array( $item, '=', $getValue ) );
			$this->selectCars( $this->_db->results(), $this->_adminRights );
		} else {
			$this->_db->query( 'SELECT * FROM cars' );

			$this->selectCars( $this->_db->results(), $this->_adminRights );
		}
	}

	private function selectCars( $carArray ) {
		$sortValue = 'year';
		$orderBy   = SORT_DESC;
		if ( isset( $_GET['sort'] ) ) {
			$sortValue = substr_replace( $_GET['sort'], "", - 1 );
			$orderBy   = $_GET['sort'];
			$orderBy   = substr( $orderBy, - 1 );
			if ( $orderBy == 'D' ) {
				$orderBy = SORT_ASC;
			} else {
				$orderBy = SORT_DESC;
			}
		}
		$carsInOrder = $this->getInOrder( $carArray, $sortValue, $orderBy );

		$countCars  = count( $carsInOrder );
		$carsOnPage = 5;
		if ( $countCars < $carsOnPage ) {
			$totalPages = 1;
			$carsOnPage = $countCars;
		} else {
			if ( $countCars % $carsOnPage == 0 ) {
				$totalPages = $countCars / $carsOnPage;
			} else {
				$totalPages = floor( $countCars / $carsOnPage ) + 1;
			}
		}

		if ( strpos( $this->url(), "page=" ) > 0 ) {
			// page= is already on url
			$sign = substr( $this->url(), strpos( $this->url(), "page=" ) - 1, 1 );
			$url  = substr( $this->url(), 0, strpos( $this->url(), "page=" ) - 1 ) . $sign;
		} else {
			if ( strpos( $this->url(), "?" ) > 0 ) {
				$url = $this->url() . '&';
			} else {
				$url = $this->url() . '?';
			}
		}

		for ( $i = 1; $i <= $totalPages; $i ++ ) {
			$indexPage = $url . $this->sortGlobal . 'page=' . $i;
			if ( isset( $_GET['page'] ) && $_GET['page'] == $i ) {
				$this->_pagination[ $i ] = '<li><a id="activePag" href="' . $indexPage . '"> ' . $i . ' </a></li>';
			} else {
				$this->_pagination[ $i ] = '<li><a href="' . $indexPage . '"> ' . $i . ' </a></li>';
			}
		}

		$startCount = 0;
		$endCount   = $carsOnPage;
		if ( isset( $_GET['page'] ) ) {
			$page       = $_GET['page'];
			$startCount = $carsOnPage * ( $page - 1 );
			$endCount   = $carsOnPage * $page;
			if ( $endCount > $countCars ) {
				$endCount = $countCars;
			}
		}

		for ( $i = $startCount; $i < $endCount; $i ++ ) {
			echo( '
					<div class="product-item">
						<div class="picture-product fl">
			        		<img src=' . $carsInOrder[ $i ]->location . ' alt=\'\'/>
						</div>
						<div class="fr product-info">
			' );

			if ( $this->_adminRights ) {
				echo( '<a href = "index.php?deleteCar=' . $carsInOrder[ $i ]->id . '" class="fr" rel = "fancybox" >
								<img src = "fancybox/fancy_close.png" >
							</a >
				' );
			}

			echo( '
							<h3>' . $carsInOrder[ $i ]->model . ' ' . $carsInOrder[ $i ]->other . '</h3>

							<span class="title">Year</span>
							<span class="separate"></span>
							<span>' . $carsInOrder[ $i ]->year . '</span>

							<div class="clear"></div>
							<span class="title">Engine</span>
							<span class="separate"></span>
							<span>' . $carsInOrder[ $i ]->engine . ' cm<sup>3</sup></span>

							<div class="clear"></div>
							<span class="title">Price</span>
							<span class="separate"></span>
							<span>&pound; ' . $carsInOrder[ $i ]->price . '</span>

							<div class="clear"></div>


			' );
			if ( $this->_adminRights ) {
				echo( '	<a href="carUpdate.php?id=' . $carsInOrder[ $i ]->id . '" class="buttom-enquire iframe">
								VEHICLE UPDATE</a>
				' );
				echo( '	<a href="carBooked.php?id=' . $carsInOrder[ $i ]->id . '" class="buttom-enquire iframe">
								SEE BOOKINGS</a>
				' );
			} else {
				echo( '	<a href="carDetails.php?id=' . $carsInOrder[ $i ]->id . '" class="buttom-enquire iframe">
								VEHICLE DETAILS </a>
				');
				echo( '	<a href="carTest.php?id=' . $carsInOrder[ $i ]->id . '" class="buttom-enquire iframe">
								BOOK TEST DRIVE </a>
				' );
			}
			echo( '	</div>
						<div class="clear"></div>
					</div>
			' );
		}
	}

	private function getInOrder( $cars, $value, $sortType ) {

		foreach ( $cars as $c => $key ) {
			$carValues[ $c ] = $key->$value;
		}
		array_multisort( $carValues, $sortType, $cars );

		return $cars;
	}

	public function getUniqueSeries( $series = null ) {

		$unique = [ ];
		$count  = 0;

		if ( $series ) {
			$cars = $this->_db->get( 'cars', array( 'series', '=', $series ) );
			foreach ( $cars->results() as $car ) {
				$unique[ $count ] = $car->model;
				$count ++;
			}
		} else {
			$cars = $this->_db->query( 'SELECT * FROM cars' );
			foreach ( $cars->results() as $car ) {
				$unique[ $count ] = $car->series;
				$count ++;
			}
		}

		$unique = array_unique( $unique );
		sort( $unique );

		return $unique;
	}

	public function pagination() {
		echo '<div id="pagination"><ul class="pagination">';
		if ( count( $this->_pagination ) > 1 ) {
			foreach ( $this->_pagination as $page ) {
				echo $page;
			}
		}
		echo '</ul></div>';
	}

	public function url() {
		$url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

		return $url;
	}

	public function addNewCar() {
		if ( $this->_adminRights ) {
			echo '<form action="" method="post" enctype="multipart/form-data">
			<label for="series">Series</label>
			<input type="text" class="login-inputs" name="series" id="series"
			       value="';
			echo Input::get( 'series' ) . '" placeholder="Series">
			<label for="model">Model</label>
			<input type="text" class="login-inputs" name="model" id="model"
			       value="';
			echo Input::get( 'model' ) . '" placeholder="Model">
			<label for="other">Other</label>
			<input type="text" class="login-inputs" name="other" id="other"
			       value="';
			echo Input::get( 'other' ) . '" placeholder="Other">
			<label for="engine">Engine size</label>
			<input type="text" class="login-inputs" name="engine" id="engine"
			       value="';
			echo Input::get( 'engine' ) . '" placeholder="Engine size">
			<label for="year">Year</label>
			<input type="text" class="login-inputs" name="year" id="year"
			       value="';
			echo Input::get( 'year' ) . '" placeholder="Year">
			<label for="seat">Seats</label>
			<input type="text" class="login-inputs" name="seat" id="seat"
			       value="';
			echo Input::get( 'seat' ) . '" placeholder="Seats">
			<label for="price">Price</label>
			<input type="text" class="login-inputs" name="price" id="price"
			       value="';
			echo Input::get( 'price' ) . '" placeholder="Price">
			<label for="fileToUpload">Image</label>
			<input type="file" name="fileToUpload" id="fileToUpload">


			<input type="submit" id="submit" name="submit" value="Save">
			<!-- Error message comes here for any issues  -->
			<div class="clear error-text">';

			if ( Input::exist() ) {
				$validate   = new Validate();
				$validation = $validate->check( $_POST, array(
					'series' => array( 'required' => true, 'max' => 20, 'name' => 'series' ),
					'model'  => array( 'required' => true, 'max' => 20, 'name' => 'model' ),
					'other'  => array( 'max' => 50, 'name' => 'other' ),
					'engine' => array( 'required' => true, 'max' => 6, 'numericOnly' => '', 'name' => 'engine size' ),
					'year'   => array( 'required' => true, 'max' => 4, 'numericOnly' => '', 'name' => 'year' ),
					'seat'   => array( 'required' => true, 'max' => 2, 'numericOnly' => '', 'name' => 'seat' ),
					'price'  => array( 'required' => true, 'max' => 6, 'numericOnly' => '', 'name' => 'price' )
				) );
				if ( $validation->passed() ) {

					$target_dir    = "images/cars/";
					$target_file   = $target_dir . basename( $_FILES["fileToUpload"]["name"] );
					$uploadOk      = 1;
					$imageFileType = pathinfo( $target_file, PATHINFO_EXTENSION );
					// Check if image file is a actual image or fake image
					try{
						$check = file_exists ( $_FILES["fileToUpload"]["tmp_name"] );
						if ( $check !== false ) {
							$uploadOk = 1;
						} else {
							echo "File is not an image.<br>";
							$uploadOk = 0;
						}
					}catch (Exception $e){

					}

					// Check if file already exists
					/*
					if ( file_exists( $target_file ) ) {
						echo "Sorry, file already exists.";
						$uploadOk = 0;
					}
					*/
					// Check file size
					if ( $_FILES["fileToUpload"]["size"] > 500000 ) {
						echo "Sorry, your file is too large.";
						$uploadOk = 0;
					}
					// Allow certain file formats
					if ( $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					     && $imageFileType != "gif"
					) {
						echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
						$uploadOk = 0;
					}

					// Check if $uploadOk is set to 0 by an error
					if ( $uploadOk == 0 ) {
						echo "Sorry, your file was not uploaded.<br>";
						// if everything is ok, try to upload file
					} else {
						if ( move_uploaded_file( $_FILES["fileToUpload"]["tmp_name"], $target_file ) ) {

							try {
								$this->_db->insert( 'cars', array(
									'series'      => Input::get( 'series' ),
									'model'       => Input::get( 'model' ),
									'other'       => Input::get( 'other' ),
									'engine'      => Input::get( 'engine' ),
									'year'        => Input::get( 'year' ),
									'seat'        => Input::get( 'seat' ),
									'price'       => Input::get( 'price' ),
									'location'    => $target_file,
									'dateCreated' => date( 'Y-m-d H:i:s' )
								) );
								echo 'New vehicle added succsessfully!<br>';
							} catch ( Exception $e ) {
								die( $e->getMessage() );
							}

						} else {
							echo "Sorry, there was an error uploading your file.";
						}
					}
				} else {
					foreach ( $validation->getError() as $error ) {
						echo $error . '<br>';
					}
				}
			}
			echo '</div>
			</form>';
		} else {
			echo '<p>You don\'t have the rights to add new car!</p>';
		}
	}

	public function updateCar( $table, $id ) {

		if ( Input::exist() ) {
			$validate   = new Validate();
			$validation = $validate->check( $_POST, array(
				'series' => array( 'required' => true, 'max' => 20, 'name' => 'series' ),
				'model'  => array( 'required' => true, 'max' => 20, 'name' => 'model' ),
				'other'  => array( 'max' => 50, 'name' => 'other' ),
				'engine' => array( 'required' => true, 'max' => 6, 'numericOnly' => '', 'name' => 'engine size' ),
				'year'   => array( 'required' => true, 'max' => 4, 'numericOnly' => '', 'name' => 'year' ),
				'seat'   => array( 'required' => true, 'max' => 2, 'numericOnly' => '', 'name' => 'seat' ),
				'price'  => array( 'required' => true, 'max' => 6, 'numericOnly' => '', 'name' => 'price' )
			) );
			if ( $validation->passed()  ) {
				if(Input::get('updateImage') == 'true') {
					$target_dir    = "images/cars/";
					$target_file   = $target_dir . basename( $_FILES["fileToUpload"]["name"] );
					$uploadOk      = 1;
					$imageFileType = pathinfo( $target_file, PATHINFO_EXTENSION );
					// Check if image file is a actual image or fake image
					if ( isset( $_POST["submit"] ) ) {
						$check = getimagesize( $_FILES["fileToUpload"]["tmp_name"] );
						if ( $check !== false ) {
							$uploadOk = 1;
						} else {
							echo "File is not an image.";
							$uploadOk = 0;
						}
					}
					// Check if file already exists
					/*
					if ( file_exists( $target_file ) ) {
						echo "Sorry, file already exists.";
						$uploadOk = 0;
					}
					*/
					// Check file size
					if ( $_FILES["fileToUpload"]["size"] > 500000 ) {
						echo "Sorry, your file is too large.";
						$uploadOk = 0;
					}
					// Allow certain file formats
					if ( $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					     && $imageFileType != "gif"
					) {
						echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
						$uploadOk = 0;
					}

					// Check if $uploadOk is set to 0 by an error
					if ( $uploadOk == 0 ) {
						echo "Sorry, your file was not uploaded.";
						// if everything is ok, try to upload file
					} else {
						if ( move_uploaded_file( $_FILES["fileToUpload"]["tmp_name"], $target_file ) ) {

							try {
								$this->_db->update( $table, $id, array(
									'series'      => Input::get( 'series' ),
									'model'       => Input::get( 'model' ),
									'other'       => Input::get( 'other' ),
									'engine'      => Input::get( 'engine' ),
									'year'        => Input::get( 'year' ),
									'seat'        => Input::get( 'seat' ),
									'price'       => Input::get( 'price' ),
									'location'    => $target_file,
									'dateCreated' => date( 'Y-m-d H:i:s' )
								) );
								echo 'Updated succsessfully!<br>';

								return true;
							} catch ( Exception $e ) {
								die( $e->getMessage() );
							}

						} else {
							echo "Sorry, there was an error uploading your file.";
						}
					}
				}else{
					try {
						$this->_db->update( $table, $id, array(
							'series'      => Input::get( 'series' ),
							'model'       => Input::get( 'model' ),
							'other'       => Input::get( 'other' ),
							'engine'      => Input::get( 'engine' ),
							'year'        => Input::get( 'year' ),
							'seat'        => Input::get( 'seat' ),
							'price'       => Input::get( 'price' ),
							'dateCreated' => date( 'Y-m-d H:i:s' )
						) );
						echo 'Updated succsessfully!<br>';
						return true;
					} catch ( Exception $e ) {
						die( $e->getMessage() );
					}
				}
			} else {
				foreach ( $validation->getError() as $error ) {
					echo $error . '<br>';
				}
			}
			return false;
		}
		echo '</div>
			</form>';
	}
} 