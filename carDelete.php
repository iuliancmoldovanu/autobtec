<?php
/**
 * Created by PhpStorm.
 * User: Ciprian
 * Date: 13/04/2015
 * Time: 09:58
 *
 *
 * DOCUMENTATION !!!
 * This file has been created because needs a confirmation from javascript function confirm()
 * Can not be on same file index.php
 *
 *
 */
require_once( 'core/init.php' );
$user       = new User();
if(isset( $_GET['deleteCar'] ) && $user->isLoggedIn()) {
	$db = new DB();
	if ( $db->delete( 'cars', array( 'id', '=', $_GET['deleteCar'] ) ) ) {
		Session::put('carDeleted', 'Car has been deleted successfully !');
		Redirect::to('index.php');
	} else {
		Session::put('carDeleted', 'Fail to delete that car !');
		Redirect::to('index.php');
	}
}else {
	Session::put('carDeleted', 'You don\'t have the rights to delete items!');
	Redirect::to('index.php');
}