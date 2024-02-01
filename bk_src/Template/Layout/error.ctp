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

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
//$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Keywords" content="<?php echo $this->fetch('metaKeyword'); ?>">
	<meta name="Description" content="<?php echo $this->fetch('metaDescription'); ?>">
	<title>		
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		$fav_showed = "";
		$favicon_image_arr = json_decode($website_settings->website_favicon, true);
		if($website_settings->website_favicon != "" && !empty($favicon_image_arr)):
			for($i=0;$i<6;$i++):
				if(isset($favicon_image_arr[$i]) && $favicon_image_arr[$i] != "" && file_exists(WWW_ROOT. $favicon_image_arr[$i])):
					$fav_showed = "1";
					echo $this->Html->meta(
						'favicon.ico',
						'/'.$favicon_image_arr[$i],
						['type' => 'icon']
					);
				endif;
			endfor;
		endif;
		if($fav_showed == "")
		echo $this->Html->meta('icon');

		echo $this->Html->css([
			'bootstrap.min.css'.$css_no_cache,
			'demo.css'.$css_no_cache,
			'hamburgers.css'.$css_no_cache,
			'jquery.mmenu.all.css'.$css_no_cache,
			'swiper-bundle.min.css'.$css_no_cache,
			'style.css'.$css_no_cache,
			'responsive.css'.$css_no_cache,
			'/admin/assets/css/notifyBar.css'.$css_no_cache, 
			'/admin/assets/css/validationEngine.jquery.css'.$css_no_cache,
			'/source/dist/fancybox.css'.$css_no_cache,
		]);
		// :::::::::::: JS ::::::::::
		echo $this->Html->script([
			'jquery.min.js'.$js_no_cache,
			'bootstrap.bundle.min.js'.$js_no_cache, 
			'jquery.mmenu.all.min.js'.$js_no_cache,
			'swiper-bundle.min.js'.$js_no_cache,
			'/admin/assets/js/jquery.notifyBar.js'.$js_no_cache,
			'/admin/assets/js/jquery.validationEngine.js'.$js_no_cache, 
			'/admin/assets/js/jquery.validationEngine-en.js'.$js_no_cache,
			'/source/dist/fancybox.umd.js'.$js_no_cache,
		]);
	?>
</head>
<body>
	<div id="page" class="error_page">
		<?php echo $this->element('header'); ?>
		<div id="" class="error_background"></div>
		
		<div class="error_top">
			<center><?=$this->Html->image('404.png',array('class' => 'mw-100'))?></center>
		</div>	
		<?php echo $this->element('footer'); ?>
		<?php echo $this->Html->script(array('custom'))?>
	</div>
</body>

</html>
