<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

//$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
//$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
//pr($this->Flash->render());
?>
<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			Welcome to Admin Panel<?php //echo $this->fetch('title'); ?>
		</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php
			echo $this->Html->css(['/admin/assets/css/bootstrap.min', '/admin/assets/scss/style', '/admin/assets/css/notifyBar', '/admin/assets/css/validationEngine.jquery']);

			echo $this->Html->script(['/admin/assets/js/jquery.min', '/admin/assets/js/jquery.notifyBar', '/admin/assets/js/jquery.validationEngine', '/admin/assets/js/jquery.validationEngine-en']);

		?>
	</head>
	<body class="bg-dark" style="background:url('<?php echo $this->Url->build("/admin/assets/images/login_bg.jpg");?>');background-size: inherit;background-repeat: no-repeat;">
		<?php echo $this->fetch('content'); ?>
		<div class="clearfix"></div>
		<!-- ========== custom notify bar message =========== -->
		<script type="text/javascript">
		<!--
			showCustomMessage('<?=$this->Flash->render()?>');
		//-->
		</script>
	</body>
</html>