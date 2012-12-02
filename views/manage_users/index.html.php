<?php $this->title('Manage Users'); ?>

<table class="table">
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
					<?php if($user->user_group->slug !== 'root'): ?>
					<div class="btn-group">
						<?php
							// Activate or deactivate button
							$activateDeactivate = array(
								'name' => '',
								'link' => array(
									'library' => 'li3_usermanager', 'controller' => 'ManageUsers', 'id' => $user->id
								)
							);
							if ($user->active) {
								$activateDeactivate['name'] = 'Deactivate';
								$activateDeactivate['link'] += array('action' => 'deactivate');
							} else {
								$activateDeactivate['name'] = 'Activate';
								$activateDeactivate['link'] += array('action' => 'activate');
							}
						?>
						<?=$this->html->link(
							$activateDeactivate['name'],
							$activateDeactivate['link'],
							array('class' => 'btn btn-small')
						);?>
						<?=$this->html->link(
							'Promote',
							array('library' => 'li3_usermanager', 'ManageUsers::promote', 'id' => $user->id),
							array('class' => 'btn btn-small')
						);?>
						<?=$this->html->link(
							'Destroy',
							array('library' => 'li3_usermanager', 'ManageUsers::destroy', 'id' => $user->id),
							array('class' => 'btn btn-small btn-danger')
						);?>
					</div>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>