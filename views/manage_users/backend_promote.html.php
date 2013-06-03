<?php $this->title('Promote user'); ?>

<?=$this->form->create($user, array(
	'class' => 'form-inline',
	'url' => array('li3_usermanager.ManageUsers::promote', 'backend' => true, 'id' => $user->id)
));?>
	<label for="UsersUserGroupId">Promote <strong><?=$user->username; ?></strong> to:</label>
	<?=$this->form->select('user_group_id', $groups); ?>
	<?=$this->form->submit('Update', array('class' => 'btn btn-primary'));?>
<?=$this->form->end();?>