<?php $this->title('Login'); ?>

<?php if ($inactive): ?>
	<p>Your account is inactive!</p>
<?php endif; ?>

<?=$this->form->create(null);?>
	<?=$this->form->field('username');?>
	<?=$this->form->field('password', array('type' => 'password'));?>
	<?=$this->form->submit('Login', array('class' => 'btn btn-primary'));?>
<?=$this->form->end();?>

<?php if (LI3_UM_EnableUserRegistration): ?>
	<p>
		Don't have account? You can register <?=$this->html->link('here', array('library' => 'li3_usermanager', 'Users::add'));?>.
	</p>
<?php endif; ?>
<p>You can't remember your password? You can reset it <?=$this->html->link('here', array('library' => 'li3_usermanager', 'Users::requestResetPassword'));?>.</p>