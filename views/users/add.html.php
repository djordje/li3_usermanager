<?php $this->title('Register'); ?>

<?=$this->form->create($user);?>
	<?=$this->form->field('username');?>
	<?=$this->form->field('password', array('type' => 'password'));?>
	<?=$this->form->field('confirm_password', array('type' => 'password'));?>
	<?=$this->form->field('email');?>
	<?=$this->form->submit('Submit', array('class' => 'btn btn-primary'));?>
<?=$this->form->end();?>