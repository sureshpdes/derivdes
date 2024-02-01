<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use DateTime;
use Cake\ORM\Query;

class CommonHelper extends Helper 
{
	public function find_user($id=null){ 
		$find_user = TableRegistry::get('UserMaster')->find('all', ['conditions'=>['UserMaster.id'=>$id]])->first();
		return $find_user;
	}
	public function find_user_name($id=null){ 
		$find_user = TableRegistry::get('UserMaster')->find('all', ['conditions'=>['UserMaster.user_id'=>$id]])->first();
		return $find_user;
	}
	public function find_user_type(){ 
		$find_user_type = TableRegistry::get('UserType')->find('all')->toArray();
		return $find_user_type;
	}
	public function find_exchange($user_id = null)
	{
		$ExcOptMap = TableRegistry::get('ExcOptMap');
		$ExchangeMaster = TableRegistry::get('ExchangeMaster');
		$query = $ExchangeMaster->find()
		    ->select(['ExchangeMaster.exchange_master_name', 'ExcOptMap.exh_opt_map_id'])
		    ->innerJoinWith('ExcOptMap')
		    ->where(['ExcOptMap.user_id' => $user_id]);

		$results = $query->toArray();
		
		return $results;
	}
	public function find_exchange_list()
	{
		$conditions = [];
		$database_type = '';
		$database_type = $this->request->getSession()->read('database_type');
		if($database_type!='')
		{
			$conditions['ExchangeMaster.exchange_master_status'] = $database_type;
		}
		$ExchangeMaster = TableRegistry::get('ExchangeMaster');
		$query = $ExchangeMaster->find('all', ['conditions' => $conditions])->toArray();
		return $query;
	}
	public function find_exchange_by_id($exchange_id=null)
	{
		$conditions = [];
		$database_type = '';
		$database_type = $this->request->getSession()->read('database_type');
		if($database_type!='')
		{
			$conditions['ExchangeMaster.exchange_master_status'] = $database_type;
		}
		if($exchange_id!='')
		{
			$conditions['ExchangeMaster.exchange_master_id'] = $exchange_id;
		}
		$ExchangeMaster = TableRegistry::get('ExchangeMaster');
		$query = $ExchangeMaster->find('all', ['conditions' => $conditions])->first();
		return $query;
	}
	public function find_common_field()
	{
		$database_type = $this->request->getSession()->read('database_type');
		$CommonFields = TableRegistry::get('CommonFields');
		$query = $CommonFields->find('all', ['conditions' => ['CommonFields.common_fields_status' => $database_type]])->toArray();
		return $query;
	}
	public function find_field_list($template_id)
	{
		$FieldMaster = TableRegistry::get('FieldMaster');
		$query = $FieldMaster->find('all', ['conditions' => ['FieldMaster.template_master_id' => $template_id]])->toArray();
		return $query;
	}
	public function find_entry_list($status = null, $from_datepicker = null, $to_datepicker = null, $typeSearch = null)
	{

		$EntryMaster = TableRegistry::get('EntryMaster');
		$TemplateMaster = TableRegistry::get('TemplateMaster');
		$database_type = $this->request->getSession()->read('database_type');
		$conditions = [];
		$conditions['EntryMaster.entry_master_status'] = $database_type;
		if($status!=''){
			$conditions['EntryMaster.entry_status'] = $status;
		}
		if($from_datepicker!='' && $to_datepicker!=''){
			$from_date = date_format(date_create($from_datepicker), "Y-m-d H:i:s");
			$to_date = date_format(date_create($to_datepicker), "Y-m-d H:i:s");
			$conditions['EntryMaster.mod_time >='] = $from_date;
			$conditions['EntryMaster.mod_time <='] = $to_date;
		}
		$query = $EntryMaster->find('all', ['conditions' => $conditions]);
		return $query;
	}
	public function find_template($id = null)
	{
		$TemplateMaster = TableRegistry::get('TemplateMaster');
		$query = $TemplateMaster->find('all', ['conditions' => ['TemplateMaster.template_master_id' => $id]])->first();
		return $query;
	}
	public function find_files($id = null)
	{
		$ViewFileMaster2 = TableRegistry::get('ViewFileMaster2');
		$query = $ViewFileMaster2->find('all', ['conditions' => ['ViewFileMaster2.exchange_id' => $id]])->toArray();
		return $query;
	}
	public function find_template_list()
	{
		$conditions = [];
		$database_type = '';
		$database_type = $this->request->getSession()->read('database_type');
		if($database_type!='')
		{
			$conditions['TemplateMaster.template_master_status'] = $database_type;
		}
		$TemplateMaster = TableRegistry::get('TemplateMaster');
		$query = $TemplateMaster->find('all', ['conditions' => $conditions])->toArray();
		return $query;
	}
	public function format_database_datetime($datetime=null){
		$formated_datetime['only_date'] = "";
		$formated_datetime['full_datetime'] = "";
		if($datetime != "" && $datetime != "0000-00-00 00:00:00")
		{
			$formated_datetime['only_date'] = date("d-m-Y", strtotime($datetime));
			$formated_datetime['full_datetime'] = date("d-m-Y h:i A", strtotime($datetime));
		}
		return $formated_datetime;
	}
	
	
	
	public function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			//'s' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
	function slugify($text){ 
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);		
		
		// trim
		$text = trim($text, '-');
		
		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		// lowercase
		$text = strtolower($text);		
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
		$text = str_replace('<br>', '', $text);
		//$text = preg_replace('/<br \/>/iU', '', $text);
		if (empty($text)){
			return 'n-a';
		}	
		return $text;
	}
}
?>