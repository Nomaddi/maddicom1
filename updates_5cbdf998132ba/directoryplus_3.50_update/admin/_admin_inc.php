<?php
if(!$is_admin) {
	header("Location: $baseurl/user/sign-in");
	die();
}