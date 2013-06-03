<?php $this->title('Deactivate User'); ?>

<?=$this->html->link(
	'<i class="icon-chevron-left"></i> Back',
	array('li3_usermanager.ManageUsers::index', 'backend' => true),
	array('class' => 'btn', 'escape' => false)
);?>

<?php if ($success): ?>
	<p>User <strong><?=$user->username; ?></strong> activated!</p>
<?php else: ?>
	<p>Something went wrong!</p>
<?php endif; ?>