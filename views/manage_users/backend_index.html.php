<?php $this->title('Manage Users'); ?>

<table class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th>Username</th>
			<th>Email</th>
			<th>User group</th>
			<th>Controls</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($users as $user): ?>
			<tr>
				<td><?=$user->username; ?></td>
				<td><?=$user->email; ?></td>
				<td><?=$user->user_group->name; ?></td>
				<td>
					<div class="btn-group">
						<?php
							// Activate or deactivate link
							$name = '';
							$link = 'li3_usermanager.ManageUsers';
							$params = array('id' => $user->id, 'backend' => true);
							$options = array('class' => 'btn btn-small');
							if ($user->active) {
								$name = 'Deactivate';
								$link .= '::deactivate';
								$options['data-confirm'] = 'Are you sure about deactivation of this account?';
							} else {
								$name = 'Activate';
								$link .= '::activate';
								$options['data-confirm'] = 'Are you sure about activation of this account?';
							}
							echo $this->html->link(
								$name, array($link) + $params, $options
							);
						?>
						<?=$this->html->link(
							'Promote',
							array('li3_usermanager.ManageUsers::promote', 'id' => $user->id, 'backend' => true),
							array('class' => 'btn btn-small')
						);?>
						<?=$this->html->link(
							'Destroy',
							array('li3_usermanager.ManageUsers::destroy', 'id' => $user->id, 'backend' => true),
							array('class' => 'btn btn-small btn-danger', 'data-confirm' => 'You cant undo this operation!')
						);?>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>