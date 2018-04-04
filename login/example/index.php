<?php
	require_once '../class/user.php';
	require_once 'config.php';
	$user->indexHead();
	$user->indexTop();
	$user->loginForm();
	$user->activationForm();
	$user->indexMiddle();
	$user->registerForm();
	$user->indexFooter();

	if( mail('gjqm8917@naver.com', 'MySubject', 'haha')){
		echo "good";
	}else{
		echo "bad";
	}
print phpinfo();
?>
