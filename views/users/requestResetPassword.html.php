<?php $this->title('Reset password'); ?>

<?php if (!$emailSent && !$message): ?>
	<?=$this->form->create(null);?>
		<?=$this->form->field('username');?>
		<?=$this->form->field('email');?>
		<?=$this->form->submit('Request', array('class' => 'btn'));?>
	<?=$this->form->end();?>
<?php else: ?>
	<p><?=$message; ?></p>
<?php endif; ?>