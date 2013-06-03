<?php $this->title('Create user'); ?>

<?=$this->form->create($user, array('url' => array('li3_usermanager.ManageUsers::add', 'backend' => true)));?>
	<?=$this->form->field('username');?>
	<?=$this->form->field('password', array('type' => 'password'));?>
	<?=$this->form->field('confirm_password', array('type' => 'password'));?>
	<?=$this->form->field('email');?>
	<?=$this->form->submit('Submit', array('class' => 'btn btn-primary'));?>
<?=$this->form->end();?>