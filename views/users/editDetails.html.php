<?php $this->title('Edit details'); ?>

<?=$this->html->link(
	'<i class="icon-chevron-left"></i> Back',
	'li3_usermanager.Users::index',
	array('class' => 'btn', 'escape' => false)
);?>

<?=$this->form->create($details, array('url' => 'li3_usermanager.Users::editDetails'));?>
	<?=$this->security->requestToken(); ?>
	<?=$this->form->field('fullname');?>
	<?=$this->form->field('homepage');?>
	<div>
		<?=$this->form->label('About', 'About');?>
		<?=$this->form->textarea('about');?>
		<?=$this->form->error('about');?>
	</div>
	<?=$this->form->submit('Update', array('class' => 'btn'));?>
<?=$this->form->end();?>