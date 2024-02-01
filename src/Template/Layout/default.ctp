<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="Keywords" content="<?php echo $this->fetch('metaKeyword'); ?>">
	<meta name="Description" content="<?php echo $this->fetch('metaDescription'); ?>">
    <title>
        Exchange Data International::<?= $this->fetch('title') ?>
    </title>
	<?php
		// :::::::::::: CSS ::::::::::
		echo $this->Html->css([
			'bootstrap.min.css',
			'style.css',
			'datepicker.css',
			'/admin/assets/css/notifyBar.css', 
			'/admin/assets/css/validationEngine.jquery.css',
			'/source/dist/fancybox.css',
		]);
		// :::::::::::: JS ::::::::::
		echo $this->Html->script([
			'jquery.min.js',
			'bootstrap.min.js', 
			'bootstrap-datepicker.js',
			'bootstrap.bundle.min.js',
			'/admin/assets/js/jquery.notifyBar.js',
			'/admin/assets/js/jquery.validationEngine.js', 
			'/admin/assets/js/jquery.validationEngine-en.js',
			'/source/dist/fancybox.umd.js',
		]);
	?>
</head>
<body>
    <div id="page">
		<!--==========================header area===================-->
		<?php echo $this->element('header'); ?>
		<!--==========================header area end===================-->
		<!--==========================main area===================-->
		<main>
			<?php echo $this->fetch('content'); ?>
		</main>
	</div>
	<?php
		echo $this->Html->script([
			'custom.js',
		]);
	?>
	
	<script type="text/javascript">
	<!--
		$(function(){
			$(".no_cut_copy_paste").on("cut copy paste",function(e) {
				e.preventDefault();
			});
		})
		showCustomMessage('<?=$this->Flash->render()?>');
	//-->
	</script>
</body>
</html>
