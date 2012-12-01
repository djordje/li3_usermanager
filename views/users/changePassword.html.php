<?php $this->title('Change password'); ?>

<?=$this->html->link(
	'<i class="icon-chevron-left"></i> Back',
	array('library' => 'li3_usermanager', 'Users::index'),
	array('class' => 'btn', 'escape' => false)
);?>

<?=$this->form->create($user);?>
	<?=$this->form->field('old_password', array('type' => 'password'));?>
	<?=$this->form->field('password', array('type' => 'password', 'label' => 'New password'));?>
	<?=$this->form->field('confirm_password', array('type' => 'password', 'label' => 'Confirm new password'));?>
	<?=$this->form->submit('Change', array('class' => 'btn btn-primary'));?>
<?=$this->form->end();?>