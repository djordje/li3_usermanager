<?php $this->title('User home'); ?>

<p>Hello, welcome aboard!</p>

<div class="btn-group">
	<?=$this->html->link('Edit details', 'li3_usermanager.Users::editDetails', array('class' => 'btn'));?>
	<?=$this->html->link('Change email', 'li3_usermanager.Users::changeEmail', array('class' => 'btn'));?>
	<?=$this->html->link('Change password', 'li3_usermanager.Users::changePassword', array('class' => 'btn'));?>
	<?=$this->html->link('Logout', 'li3_usermanager.Session::destroy', array('class' => 'btn btn-danger'));?>
</div>