<?php
require_once 'core/init.php';
$logoutUser = new User();
$logoutUser->logout();
Redirect::to('index.php');
