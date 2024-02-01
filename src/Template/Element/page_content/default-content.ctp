<?php	
	$page_title = $page_meta_keyword = $page_meta_description = "";
	if(isset($website_settings->website_title) && $website_settings->website_title !=''):
		$page_title = $website_settings->website_title;
	endif;
	if(isset($website_settings->website_slogan) && $website_settings->website_slogan !=''):
		if($page_title != "")
			$page_title .= "-";
		$page_title .= $website_settings->website_slogan;
	endif;
	if(isset($find_page_details->meta_page_title) && $find_page_details->meta_page_title !=''):
		if($page_title != "")
			$page_title .= "-";
		$page_title .= $find_page_details->meta_page_title;
	elseif(isset($general_settings->default_page_title) && $general_settings->default_page_title !=''):
		if($page_title != "")
			$page_title .= "-";
		$page_title .= $general_settings->default_page_title;
	endif;
	$this->assign('title', $page_title);
	if(isset($find_page_details->meta_keyword) && $find_page_details->meta_keyword !=''):
		$page_meta_keyword .= $find_page_details->meta_keyword;
	elseif(isset($general_settings->default_meta_keyword) && $general_settings->default_meta_keyword !=''):
		$page_meta_keyword .= $general_settings->default_meta_keyword;
	endif;
	$this->assign('metaKeyword', $page_meta_keyword);
	if(isset($find_page_details->meta_description) && $find_page_details->meta_description !=''):
		$page_meta_description .= $find_page_details->meta_description;
	elseif(isset($general_settings->default_meta_description) && $general_settings->default_meta_description !=''):
		$page_meta_description .= $general_settings->default_meta_description;
	endif;
	$this->assign('metaDescription', $page_meta_description);
	if(isset($find_page_details) && !empty($find_page_details)):
		$page_content = $find_page_details->content_layouts;
		foreach($short_tag as $short_tags)
		{
			if(strpos($page_content, $short_tags) > -1)
			{
				$section=strtolower(str_replace(["[%","%]"], ["",""],$short_tags));
				$page_content = str_replace($short_tags, $this->element('section/'.$section.'', ['find_page_details' => $find_page_details]), $page_content);
			}
		}
		
		if(strpos($page_content, "[%Gallery-Overons%]") > -1)
		{
			$page_content = str_replace("[%Gallery-Overons%]", $this->element('section/overons-gallery-section', ['find_page_details' => $find_page_details]), $page_content);
		}
		/*if(strpos($page_content, "[%Gallery-Counter%]") > -1)
		{
			$page_content = str_replace("[%Gallery-Counter%]", $this->element('section/counter-section', ['find_page_details' => $find_page_details]), $page_content);
		}*/
		if(strpos($page_content, "[%Gallery-Teammember%]") > -1)
		{
			$page_content = str_replace("[%Gallery-Teammember%]", $this->element('section/teammember-section', ['find_page_details' => $find_page_details]), $page_content);
		}
		if(strpos($page_content, "[%Gallery-Testimonial%]") > -1)
		{
			$page_content = str_replace("[%Gallery-Testimonial%]", $this->element('section/testimonial-section', ['find_page_details' => $find_page_details]), $page_content);
		}
		
		echo $page_content;
	endif;
?>