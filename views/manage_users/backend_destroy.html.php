<?php $this->title('Destroy user'); ?>

<?=$this->html->link(
	'<i class="icon-chevron-left"></i> Back',
	array('li3_usermanager.ManageUsers::index', 'backend' => true),
	array('class' => 'btn', 'escape' => false)
);?>

<?php if ($destroyed): ?>
	<p>
		User with id: <code><?=$user->id; ?></code>,
		and username: <code><?=$user->username; ?></code> destroyed!
	</p>
<?php else: ?>
	<p>Something went wrong!</p>
<?php endif; ?>