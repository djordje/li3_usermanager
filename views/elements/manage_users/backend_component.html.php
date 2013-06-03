<ul class="nav nav-list well">
	<li class="nav-header">Manage users</li>
	<?=$this->backend->nav('Index', array('li3_usermanager.ManageUsers::index', 'backend' => true)); ?>
	<?=$this->backend->nav('Add user', array('li3_usermanager.ManageUsers::add', 'backend' => true)); ?>
</ul>