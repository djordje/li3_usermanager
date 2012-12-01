<?php $this->title('User home'); ?>

<div class="btn-group">
	<?=$this->html->link('Edit details', array('library' => 'li3_usermanager', 'Users::editDetails'), array('class' => 'btn'));?>
	<?=$this->html->link('Change email', array('library' => 'li3_usermanager', 'Users::changeEmail'), array('class' => 'btn'));?>
	<?=$this->html->link('Change password', array('library' => 'li3_usermanager', 'Users::changePassword'), array('class' => 'btn'));?>
	<?=$this->html->link('Logout', array('library' => 'li3_usermanager', 'Session::destroy'), array('class' => 'btn btn-danger'));?>
</div>