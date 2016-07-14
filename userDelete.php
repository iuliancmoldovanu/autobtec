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
if(isset( $_GET['del'] ) && $user->isLoggedIn()) {
	$db = new DB();
	if ( $db->delete( 'users', array( 'id', '=', $_GET['del'] ) ) ) {
		Session::put('userDeleted', 'User has been deleted successfully !');
		Redirect::to('index.php?crudUser=modifyUser');
	} else {
		Session::put('userDeleted', 'Fail to delete that user !');
		Redirect::to('index.php?crudUser=modifyUser');
	}
}else {
	Session::put('userDeleted', 'You don\'t have the rights to delete users!');
	Redirect::to('index.php?crudUser=modifyUser');
}

