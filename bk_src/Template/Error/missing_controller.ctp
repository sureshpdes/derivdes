<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

if($this->request->getSession()->check('Auth.Admin.id')):
	$this->layout = 'admin_default';
else:
	$this->layout = 'admin_login_layout';
endif;
?>
<div id="right-panel" class="right-panel">
	<div class="general_basic">
		<div class="container bg-danger text-light mt-5 p-5">
			<div id="header" class="mb-3">
				<h1><?= __('Error') ?></h1>
			</div>
			<h2>No such file avaliable</h2>
			<div id="footer" class="mt-5">
				<?= $this->Html->link(__('Back'), 'javascript:history.back()', ['class'=> 'text-light']) ?>
			</div>
		</div>
	</div>
</div>
