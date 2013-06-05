<?php
	$this->title('Reset your password');
	$url = array(
		'li3_usermanager.Users::resetPassword',
		'token' => $this->_request->params['token'],
		'id' => $this->_request->params['id']
	);
?>

<?=$this->form->create($user->user, compact('url'));?>
	<?=$this->security->requestToken(); ?>
	<?=$this->form->field('password', array('type' => 'password'));?>
	<?=$this->form->field('confirm_password', array('type' => 'password'));?>
	<?=$this->form->submit('Reset', array('class' => 'btn btn-primary'));?>
<?=$this->form->end();?>