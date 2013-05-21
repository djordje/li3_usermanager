<?php $this->title('Change email'); ?>

<?=$this->html->link(
	'<i class="icon-chevron-left"></i> Back',
	'li3_usermanager.Users::index',
	array('class' => 'btn', 'escape' => false)
);?>

<?=$this->form->create($user);?>
	<?=$this->form->field('email', array('type' => 'email'));?>
	<?=$this->form->submit('Change', array('class' => 'btn btn-primary'));?>
<?=$this->form->end();?>