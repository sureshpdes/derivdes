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
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = 'CakePHP: the rapid development php framework';
$base_url = WWW_ROOT;
//$company_name_arr = json_decode($website_settings->company_name, true);
//pr($company_name_arr);exit;
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="Keywords" content="<?php echo $this->fetch('metaKeyword'); ?>">
	<meta name="Description" content="<?php echo $this->fetch('metaDescription'); ?>">
	<title>
		<?php
			if(isset($website_settings->website_title) && $website_settings->website_title !=''):
				$page_title = $website_settings->website_title;
			endif;
			//$this->assign('title', $page_title);
		?>
	</title>
    <?php 
	echo $this->Html->meta('icon');

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
	?>
	<style>
	/** Define the margins of your page **/
	@page {
		margin: 120px 0px 30px;
		header: myHeader1;
	}
	
</style>
</head>
<body>
	<htmlpageheader name="myHeader1" style="display:none">
		<div class="total-body" style="width:100%; height:auto; padding-bottom: 0px;color: #fff;">
			<div style="width:100%; height:auto;margin:0 auto;">
				<div style="width:400px;margin: 0 auto;text-align:center;">
					<img src="<?=$base_url?>images/logo.png" style="width:30%;">
				</div>
			</div>
		</div>
	</htmlpageheader>
  <div id="page">
	<?php echo $this->fetch('content'); ?>
  </div>
</body>
</html>