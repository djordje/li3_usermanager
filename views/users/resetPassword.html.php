<?php $this->title('Reset your password'); ?>

<?=$this->form->create($user->user);?>
	<?=$this->form->field('password', array('type' => 'password'));?>
	<?=$this->form->field('confirm_password', array('type' => 'password'));?>
	<?=$this->form->submit('Reset', array('class' => 'btn btn-primary'));?>
<?=$this->form->end();?>