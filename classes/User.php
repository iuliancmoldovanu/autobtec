<?php

class User {
	private $_db,
		$_data,
		$_session_name,
		$_cookie_name,
		$_isLoggedIn;

	public function __construct( $user = null ) {
		$this->_db = DB::getInstance();

		$this->_session_name = Config::get( 'session/session_name' );

		if ( ! isset( $user ) ) {
			if ( Session::exist( $this->_session_name ) ) {
				$user = Session::get( $this->_session_name );
				if ( $this->find( $user, 'id' ) ) {
					$this->_isLoggedIn = true;
				}
			}
		} else {
			$this->find( $user );
		}
	}

	public function update( $fields = array(), $id = null ) {
		if ( ! $id && $this->isLoggedIn() ) {
			$id = $this->getData()->id;
		}
		if ( ! $this->_db->update( 'users', $id, $fields ) ) {
			throw new Exception( 'There was a problem updating account.' );
		}
	}

	public function create( $fields = array() ) {
		if ( ! $this->_db->insert( 'users', $fields ) ) {
			throw new Exception( 'There was a problem creating an account.' );
		}
	}

	public function find( $user = null, $fieldName = 'username' ) {
		if ( $user ) {
			$data = $this->_db->get( 'users', array( $fieldName, '=', $user ) );
			if ( $data->count() ) {
				$this->_data = $data->first();

				return true;
			}
		}
		return false;
	}

	public function login( $username = null, $password = null ) {
		if ( ! $username && ! $password && $this->exists() ) {
			Session::put( $this->_session_name, $this->getData()->id );
		} else {
			$user = $this->find( $username );
			if ( $user ) {
				if ( $this->getData()->password === $password ) {
					Session::put( $this->_session_name, $this->getData()->id );
					return true;
				}
			}
		}
		return false;
	}

	public function hasPermission( $key ) {
		$group = $this->_db->get( 'groups', array( 'id', '=', $this->getData()->group ) );
		if ( $group->count() ) {
			$permissions = json_decode( $group->first()->permission, true );
			if ( $permissions[ $key ] == true ) {
				return true;
			}
		}

		return false;
	}

	public function exists() {
		return ( ! empty( $this->_data ) ) ? true : false;
	}

	public function getData() {
		return $this->_data;
	}

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}

	public function logout() {
		$this->_db->delete( 'users_session', array(
			'user_id',
			'=',
			$this->getData()->id
		) );
		Session::delete( $this->_session_name );
	}

	public function createNewsletter() {
		if ( Input::exist() ) {
			$validate   = new Validate();
			$validation = $validate->check( $_POST, array(
				'firstName' => array( 'required' => true, 'min' => 3, 'max' => 50, 'name' => 'first name' ),
				'lastName'  => array( 'required' => true, 'min' => 3, 'max' => 50, 'name' => 'last name' ),
				'email'     => array( 'required' => true, 'min' => 5, 'max' => 50, 'name' => 'email' )
			) );
			if ( $validation->passed() ) {
				try {
					$this->_db->insert( 'newsletter', array(
						'firstname'    => Input::get( 'firstName' ),
						'lastname'     => Input::get( 'lastName' ),
						'email'        => Input::get( 'email' ),
						'registerDate' => date( 'Y-m-d H:i:s' )
					) );
					echo 'Newsletter registration succsessfully!<br>';
				} catch ( Exception $e ) {
					die( $e->getMessage() );
				}
			} else {
				foreach ( $validation->getError() as $error ) {
					echo $error . '<br>';
				}
			}
		}
	}

	public function createTestDrive() {
		if ( Input::exist() ) {
			$validate   = new Validate();
			$validation = $validate->check( $_POST, array(
				'name'       => array( 'required' => true, 'min' => 3, 'max' => 50, 'name' => 'name' ),
				'phone'      => array( 'required' => true, 'min' => 3, 'max' => 50, 'name' => 'phone' ),
				'email'      => array( 'required' => true, 'min' => 5, 'max' => 50, 'name' => 'email' ),
				'datepicker' => array( 'required' => true, 'min' => 5, 'max' => 50, 'name' => 'date' ),
				'time'       => array( 'required' => true, 'min' => 5, 'max' => 50, 'name' => 'time' )
			) );
			if ( $validation->passed() ) {
				try {
					$this->_db->insert( 'testdrive', array(
						'name'        => Input::get( 'name' ),
						'phone'       => Input::get( 'phone' ),
						'email'       => Input::get( 'email' ),
						'date'        => Input::get( 'datepicker' ),
						'time'        => Input::get( 'time' ),
						'carID'       => $_GET['id'],
						'testCreated' => date( 'Y-m-d H:i:s' )
					) );
					echo 'Test drive booking succsessfully!<br>';
				} catch ( Exception $e ) {
					die( $e->getMessage() );
				}
			} else {
				foreach ( $validation->getError() as $error ) {
					echo $error . '<br>';
				}
			}
		}
	}

	public function userCRUD( $value ) {
		if ( $this->isLoggedIn() ) {
			if ( $value == 'addUser' ) {
				echo '<form action="" method="post" enctype="multipart/form-data">
			<label for="username">Username</label>
			<input type="text" class="login-inputs" name="username" id="username"
			       value="';
				echo Input::get( 'username' ) . '" placeholder="Username">
			<label for="password">Password</label>
			<input type="text" class="login-inputs" name="password" id="password"
			       value="';
				echo Input::get( 'password' ) . '" placeholder="Password">
			<label for="access">Access type</label>
			<select name="access" id="access">
				<option value="0">Standard</option>
				<option value="1">Administrator</option>
			</select>

			<input type="submit" id="submit" name="submit" value="Save">
			<!-- Error message comes here for any issues  -->
			<div class="clear error-text">';

				if ( Input::exist() ) {
					$validate   = new Validate();
					$validation = $validate->check( $_POST, array(
						'username' => array( 'required' => true, 'min' => 3, 'max' => 20, 'name' => 'username' ),
						'password' => array( 'required' => true, 'min' => 3, 'max' => 20, 'name' => 'password' )
					) );
					if ( $validation->passed() ) {
						try {
							$this->_db->insert( 'users', array(
								'username' => Input::get( 'username' ),
								'password' => Input::get( 'password' ),
								'access'    => Input::get( 'access' )
							) );
							echo 'New user has been added succsessfully!<br>';
						} catch ( Exception $e ) {
							die( $e->getMessage() );
						}


					} else {
						foreach ( $validation->getError() as $error ) {
							echo $error . '<br>';
						}
					}
				}
				echo '</div>
			</form>';
			} else if ( $value == 'modifyUser' ) {
				$this->_db->query( 'SELECT * FROM users' );
				if ( count( $this->_db->results() ) == 0 ) {
					echo "There are no users stored.";
				} else {
					if ( isset( $_GET['mod'] ) ) {
						$this->find( $_GET['mod'] , 'id');
						echo '<form action="" method="post">';
						echo '<table border="1" style="width:100%">
							<tr><th>ID</th>
							<th>Username</th>
							<th>Password</th>
							<th>Access type</th>
							<th></th>
							<th></th></tr>';
						echo "<tr><td>" . $this->getData()->id . "</td>";
						echo "<td><input style='background-color: #fef1ec;' name='username' value='" . $this->getData()->username . "' /></td>";
						echo "<td><input style='background-color: #fef1ec;' name='password' value='" . $this->getData()->password . "' /></td>";
						echo '<td><select name="access" style="background-color: #fef1ec;">';
						if($this->getData()->access == 0){
							echo '<option value="0">Standard</option>
										<option value="1">Administrator</option>';
						}else{
							echo '<option value="1">Administrator</option>
									<option value="0">Standard</option>';
						}
						echo '</select></td><td><input style="width: 100%;" type="submit" name="submit" value="Save"></td>';
						echo "<td><a href='userDelete.php?del=" . $_GET['mod'] . "'><button>Delete</button></a></td></tr>";
						echo '</tr></table></form>';

						if(Input::exist()) {
							$validate   = new Validate();
							$validation = $validate->check( $_POST, array(
								'username' => array( 'required' => true, 'min' => 3, 'max' => 20, 'name' => 'username' ),
								'password' => array( 'required' => true, 'min' => 3, 'max' => 20, 'name' => 'password' )
							) );
							if ( $validation->passed() ) {
								try {
									$result = $this->_db->update( 'users', $this->getData()->id, array(
										'username' => Input::get( 'username' ),
										'password' => Input::get( 'password' ),
										'access' => Input::get( 'access' )
									) );
									if(!$result){
										echo 'The user has not been updated!<br>';
									}else {
										echo '<br>The user has been updated successfully to <br>';
										$this->find( $_GET['mod'] , 'id');
										echo "ID: " . $this->getData()->id . "<br>";
										echo "Username: " . $this->getData()->username . "<br>";
										echo "Password: " . $this->getData()->password . "<br>";
										if($this->getData()->access == 0){
											echo 'Access type: Standard';
										}else{
											echo 'Access type: Administrator';
										}
									}
								} catch ( Exception $e ) {
									die( $e->getMessage() );
								}
							} else {
								foreach ( $validation->getError() as $error ) {
									echo $error . '<br>';
								}
							}
						}
					} else if ( isset( $_GET['del'] ) ) {
						echo '<form action="" method="post"></form>';
					} else {
						echo '<table border="1" style="width:100%">
							<tr>
								<th>ID</th>
								<th>Username</th>
								<th>Password</th>
								<th>Access type</th>
								<th></th>
								<th></th>
						</tr>';
						foreach ( $this->_db->results() as $value => $key ) {
							echo "<tr><td>" . $key->id . "</td>";

							echo "<td>" . $key->username . "</td>";
							echo "<td>" . $key->password . "</td>";
							if ( $key->access == 0 ) {
								echo "<td>Standard</td>";
							} else {
								echo "<td>Administrator</td>";
							}

							echo "<td><a href='index.php?crudUser=modifyUser&mod=" . $key->id . "'><button>Update</button></a></td>";
							echo "<td><a href='userDelete.php?del=" . $key->id . "'><button>Delete</button></a></td></tr>";
						}
					}
					echo '</table>';
				}
			}
		} else {
			echo '<p>You don\'t have the rights to CRUD a user!</p>';
		}
	}

}