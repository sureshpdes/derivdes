<?php
namespace App\Controller;

use App\Controller\AppController;
use GoogleAuthenticator\GoogleAuthenticator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Cookie\CookieCollection;
use DateTime;
use Cake\Http\Response;
use Cake\Mailer\MailerAwareTrait;
use Cake\Routing\Router;
use Cake\Http\Client;
use Cake\ORM\TableRegistry;

/**
 * EntryMaster Controller
 *
 * @property \App\Model\Table\EntryMasterTable $EntryMaster
 *
 * @method \App\Model\Entity\user[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EntryMasterController extends AppController
{
	use MailerAwareTrait;

	public function initialize()
    {
        parent::initialize();
	}
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
    	return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
    }
    public function supervisorHome($type = null)
    {
    	if($type!='')
    	{
    		$database_type = $type;
    		$this->request->getSession()->write("database_type", $database_type);
    		return $this->redirect(array("controller" => "EntryMaster", "action" => "supervisorHome"));
    	}
    	elseif ($this->request->getSession()->check('database_type')) {
    		$database_type = $this->request->getSession()->read('database_type');
    	}
    	else
    	{
    		$database_type = 0;
    	}
    	$this->viewBuilder()->setLayout('default');
		if($this->request->getSession()->read('user')=='supervisor')
		{
			$this->request->getSession()->write("database_type", $database_type);
		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
		$EntryMaster = TableRegistry::get('EntryMaster');
		$TemplateMaster = TableRegistry::get('TemplateMaster');
		$database_type = $this->request->getSession()->read('database_type');
		$conditions = [];
		$product_arr_con = [];
		$get_data = [];
    	if ($this->request->is('post')) {
    		$this->request->getSession()->delete("search_data_ids");
    		$this->request->getSession()->delete("get_data");
			$status = $from_datepicker = $to_datepicker = $typeSearch = "";
			if($this->request->getData('radio') != '')
			{
				$status = $this->request->getData('radio');
			}
			if($this->request->getData('from_datepicker') != '')
			{
				$from_datepicker = $this->request->getData('from_datepicker');
			}
			if($this->request->getData('to_datepicker') != '')
			{
				$to_datepicker = $this->request->getData('to_datepicker');
			}
			if($this->request->getData('typeSearch') != '')
			{
				$typeSearch = $this->request->getData('typeSearch');
			}
			$get_data = $this->request->getData();
			$this->request->getSession()->write("get_data", $get_data);
			$entry_id_arr = [];
			$conditions['EntryMaster.entry_master_status'] = $database_type;
			if($status!=''){
				$conditions['EntryMaster.entry_status LIKE'] = $status;	
			}
			if($from_datepicker!='' && $to_datepicker!=''){
				$from_date = date_format(date_create($from_datepicker), "Y-m-d H:i:s");
				$to_date = date_format(date_create($to_datepicker), "Y-m-d H:i:s");
				$conditions['EntryMaster.mod_time >='] = $from_date;
				$conditions['EntryMaster.mod_time <='] = $to_date;
			}
			$find_entry_ids = TableRegistry::get('EntryMaster')->find('all', ['conditions' => $conditions, 'fields' =>['entry_ids' => 'GROUP_CONCAT(DISTINCT(entry_master_id))']])->first();
			if($find_entry_ids->entry_ids != ""){
				$entry_id_arr = explode(",", $find_entry_ids->entry_ids);
			}
			else{
				$entry_id_arr = ['0'];
			}
			
			if($typeSearch!=''){
				$search_id_arr = [];
				$temp_id_arr = [];

				$conditions_temp['TemplateMaster.template_master_name LIKE'] = $typeSearch.'%';
				$conditions_temp['TemplateMaster.template_master_status'] = $database_type;;
				$TemplateMaster = TableRegistry::get('TemplateMaster');
				$find_in_template = TableRegistry::get('TemplateMaster')->find('all', ['conditions' => $conditions_temp, 'fields' =>['temp_ids' => 'GROUP_CONCAT(DISTINCT(template_master_id))']])->first();
				if($find_in_template->temp_ids != ""){
					$temp_id_arr = explode(",", $find_in_template->temp_ids);
				}
				if(!empty($temp_id_arr))
				{
					$conditions_type['EntryMaster.template_master_id IN'] = $temp_id_arr;
				}
				$conditions_type['EntryMaster.primary_pdf_name LIKE'] = $typeSearch.'%';
				$conditions_type['EntryMaster.mod_time LIKE'] = $typeSearch.'%';

				$find_entry_ids = TableRegistry::get('EntryMaster')->find('all', ['conditions' => ['OR' => $conditions_type], 'fields' =>['entry_ids' => 'GROUP_CONCAT(DISTINCT(entry_master_id))']])->first();
				//print_r($find_entry_ids->entry_ids);exit();
				if($find_entry_ids->entry_ids != ""):
					$search_id_arr = explode(",", $find_entry_ids->entry_ids);
					
					if(!empty($entry_id_arr)){
						$entry_id_arr = array_intersect($entry_id_arr, $search_id_arr);
						//print_r($entry_id_arr);exit();
					}
					else{
						$entry_id_arr = $search_id_arr;

					}
				else:
					$entry_id_arr = ["0"];

				endif;	
			}
			if(empty($entry_id_arr))
			{
				$entry_id_arr = ["0"];
			}
			//print_r($entry_id_arr);exit();
			$product_arr_con = ['EntryMaster.entry_master_id IN' => $entry_id_arr];
			$this->request->getSession()->write("search_data_ids", $entry_id_arr);
			//$find_entry_list = $EntryMaster->find('all', ['conditions' => $product_arr_con])->toArray();
		}
		elseif($this->request->getSession()->check('search_data_ids')){
			$search_data_ids = $this->request->getSession()->read('search_data_ids');
			$product_arr_con = ['EntryMaster.entry_master_id IN' => $search_data_ids];
		}
		else
		{
			$status = 'submitted';
			$product_arr_con['EntryMaster.entry_master_status'] = $database_type;
			$product_arr_con['EntryMaster.entry_status'] = $status;
			//$find_entry_list = $EntryMaster->find('all', ['conditions' => $product_arr_con])->toArray();
		}
		$this->paginate = [
			'limit' => 50,
			'conditions' => $product_arr_con,
		];
        $find_entry_list = $this->paginate($this->EntryMaster);
        $this->set(compact('find_entry_list', 'get_data'));
    }
    public function operatorHome($type = null)
    {
    	if($type!='')
    	{
    		$database_type = $type;
    		$this->request->getSession()->write("database_type", $database_type);
    		return $this->redirect(array("controller" => "EntryMaster", "action" => "operatorHome"));
    	}
    	elseif ($this->request->getSession()->check('database_type')) {
    		$database_type = $this->request->getSession()->read('database_type');
    	}
    	else
    	{
    		$database_type = 0;
    	}
    	$this->viewBuilder()->setLayout('default');
		if($this->request->getSession()->read('user')=='operator')
		{
			$this->request->getSession()->write("database_type", $database_type);
		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}

		$EntryMaster = TableRegistry::get('EntryMaster');
		$TemplateMaster = TableRegistry::get('TemplateMaster');
		$database_type = $this->request->getSession()->read('database_type');
		$conditions = [];
		$product_arr_con = [];
		$get_data = [];
    	if ($this->request->is('post')) {
    		$this->request->getSession()->delete("search_data_ids");
    		$this->request->getSession()->delete("get_data");
			$status = $from_datepicker = $to_datepicker = $typeSearch = "";
			if($this->request->getData('radio') != '')
			{
				$status = $this->request->getData('radio');
			}
			if($this->request->getData('from_datepicker') != '')
			{
				$from_datepicker = $this->request->getData('from_datepicker');
			}
			if($this->request->getData('to_datepicker') != '')
			{
				$to_datepicker = $this->request->getData('to_datepicker');
			}
			if($this->request->getData('typeSearch') != '')
			{
				$typeSearch = $this->request->getData('typeSearch');
			}
			$get_data = $this->request->getData();
			$this->request->getSession()->write("get_data", $get_data);
			$entry_id_arr = [];
			$conditions['EntryMaster.entry_master_status'] = $database_type;
			if($status!=''){
				$conditions['EntryMaster.entry_status LIKE'] = $status;	
			}
			if($from_datepicker!='' && $to_datepicker!=''){
				$from_date = date_format(date_create($from_datepicker), "Y-m-d H:i:s");
				$to_date = date_format(date_create($to_datepicker), "Y-m-d H:i:s");
				$conditions['EntryMaster.mod_time >='] = $from_date;
				$conditions['EntryMaster.mod_time <='] = $to_date;
			}
			$find_entry_ids = TableRegistry::get('EntryMaster')->find('all', ['conditions' => $conditions, 'fields' =>['entry_ids' => 'GROUP_CONCAT(DISTINCT(entry_master_id))']])->first();
			if($find_entry_ids->entry_ids != ""){
				$entry_id_arr = explode(",", $find_entry_ids->entry_ids);
			}
			else{
				$entry_id_arr = ['0'];
			}
			
			if($typeSearch!=''){
				$search_id_arr = [];
				$temp_id_arr = [];

				$conditions_temp['TemplateMaster.template_master_name LIKE'] = $typeSearch.'%';
				$conditions_temp['TemplateMaster.template_master_status'] = $database_type;;
				$TemplateMaster = TableRegistry::get('TemplateMaster');
				$find_in_template = TableRegistry::get('TemplateMaster')->find('all', ['conditions' => $conditions_temp, 'fields' =>['temp_ids' => 'GROUP_CONCAT(DISTINCT(template_master_id))']])->first();
				if($find_in_template->temp_ids != ""){
					$temp_id_arr = explode(",", $find_in_template->temp_ids);
				}
				if(!empty($temp_id_arr))
				{
					$conditions_type['EntryMaster.template_master_id IN'] = $temp_id_arr;
				}
				$conditions_type['EntryMaster.primary_pdf_name LIKE'] = $typeSearch.'%';
				$conditions_type['EntryMaster.mod_time LIKE'] = $typeSearch.'%';

				$find_entry_ids = TableRegistry::get('EntryMaster')->find('all', ['conditions' => ['OR' => $conditions_type], 'fields' =>['entry_ids' => 'GROUP_CONCAT(DISTINCT(entry_master_id))']])->first();
				//print_r($find_entry_ids->entry_ids);exit();
				if($find_entry_ids->entry_ids != ""):
					$search_id_arr = explode(",", $find_entry_ids->entry_ids);
					
					if(!empty($entry_id_arr)){
						$entry_id_arr = array_intersect($entry_id_arr, $search_id_arr);
						//print_r($entry_id_arr);exit();
					}
					else{
						$entry_id_arr = $search_id_arr;

					}
				else:
					$entry_id_arr = ["0"];

				endif;	
			}
			if(empty($entry_id_arr))
			{
				$entry_id_arr = ["0"];
			}
			//print_r($entry_id_arr);exit();
			$product_arr_con = ['EntryMaster.entry_master_id IN' => $entry_id_arr];
			$this->request->getSession()->write("search_data_ids", $entry_id_arr);
			//$find_entry_list = $EntryMaster->find('all', ['conditions' => $product_arr_con])->toArray();
		}
		elseif($this->request->getSession()->check('search_data_ids')){
			$search_data_ids = $this->request->getSession()->read('search_data_ids');
			$product_arr_con = ['EntryMaster.entry_master_id IN' => $search_data_ids];
		}
		else
		{
			$status = 'ongoing';
			$product_arr_con['EntryMaster.entry_master_status'] = $database_type;
			$product_arr_con['EntryMaster.entry_status'] = $status;
			//$find_entry_list = $EntryMaster->find('all', ['conditions' => $product_arr_con])->toArray();
		}
		$this->paginate = [
			'limit' => 50,
			'conditions' => $product_arr_con,
		];
        $find_entry_list = $this->paginate($this->EntryMaster);
        $this->set(compact('find_entry_list', 'get_data'));
    }
    
    /**
     * View method
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->EntryMaster->get($id, [
            'contain' => []
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
    	$this->loadModel('EntryTxn');
    	$this->loadModel('ExchangeMaster');
        $entry = $this->EntryMaster->newEntity();
        
        $TemplateMaster = TableRegistry::get('TemplateMaster');
        $FieldMaster = TableRegistry::get('FieldMaster');
        $exchange_id = '';
        $template_id = '';
        $find_templates = [];
        $find_fields = [];
        if ($this->request->getSession()->check('user_data')) {
    		$user_data = $this->request->getSession()->read('user_data');
    		$user_id = $user_data['user_id'];
    	}
        if ($this->request->getSession()->check('database_type')) {
    			$database_type = $this->request->getSession()->read('database_type');
    	}
    	else{
    		return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
    	}
        if ($this->request->is('post')) {
        	if($this->request->getSession()->check('search_data_ids')){
        		$this->request->getSession()->delete("search_data_ids");
        	}
        	if($this->request->getSession()->check('get_data')){
        		$this->request->getSession()->delete("get_data");
        	}
        	if($this->request->getData('entry_status') && $this->request->getData('entry_status') != ''){
        		if($this->request->getData('primary_pdf_name')== ""){
	                $this->Flash->error(__('Please choose a PDF file.'));
	            }
	            else
	            {
	        		$entry = $this->EntryMaster->patchEntity($entry, $this->request->getData());
					$image_name = $this->request->getData('primary_pdf_name');
	        		$entry->entry_name = $image_name;
	        		$entry->primary_pdf_name = $image_name;
	        		$entry->user_id = $user_id;
	        		$entry->entry_master_status = $database_type;
	        		$entry_field_values= [];
					$entry_field_ids= [];
					$entry_field_values = $this->request->getData('field_value');
					$entry_field_ids = $this->request->getData('field_id');
					$exchange_id = $this->request->getData('exchange');
					$find_exc_details = $this->ExchangeMaster->find('all', ['conditions' => ['ExchangeMaster.exchange_master_id' => $exchange_id]])->first();
	        		$exchange_master_directory = $find_exc_details->exchange_master_directory;
					if ($inserted_entry = $this->EntryMaster->save($entry)) {
						if (file_exists(WWW_ROOT.$this->pdfmaster_folder.DS.$exchange_master_directory.DS.$image_name.'.pdf') && $image_name!='')
		            	{
		            		$copy_from = WWW_ROOT.$this->pdfmaster_folder.DS.$exchange_master_directory.DS.$image_name.'.pdf';
		            		$copy_to = WWW_ROOT.$this->pdfdump_folder.DS.$image_name.'.pdf';
			            	if(copy($copy_from,$copy_to))
			            	{
			            		unlink($copy_from);
			            	}
			            }
						if(!empty($entry_field_values))
						{
							$insert_new_field = [];
							foreach ($entry_field_values as $key => $field_value) {
								if($field_value!=''){
									$EntryTxn = $this->EntryTxn->newEntity();

									$insert_new_field['entry_master_id'] = $inserted_entry->entry_master_id;
									$insert_new_field['field_id'] = $entry_field_ids[$key];
									$insert_new_field['field_value'] = $field_value;
									$insert_new_field['user_id'] = $user_id;

									$EntryTxn = $this->EntryTxn->patchEntity($EntryTxn, $insert_new_field);
									$this->EntryTxn->save($EntryTxn);
								}
							}
						}
						$this->Flash->success(__('The entry has been created successfully.'));
						return $this->redirect(['action' => 'operatorHome']);
					} else {
						//$this->Flash->error(__('We are having some problem. Please try later.'));
						if($entry->getErrors()){
							$error_msg = [];
							foreach( $entry->getErrors() as $key=>$errors){
								if(is_array($errors)){
									foreach($errors as $error){
										$error_msg[]=$error;
									}
								}else{
									$error_msg[]=$errors;
								}
							}
							if(!empty($error_msg)){
								$this->Flash->error(
									"entry could not be saved. ".__(implode(" and ", $error_msg))
								);
							}
						}
					}
				}
        	}
        	else
        	{
				if($this->request->getData('exchange') && $this->request->getData('exchange') != ''){
	        		$exchange_id = $this->request->getData('exchange');
	        		$condition = [];
					$condition['TemplateMaster.exchange_master_id'] = $exchange_id;
			    	$condition['TemplateMaster.template_master_status'] = $database_type;
			    	$find_templates = $TemplateMaster->find('all', ['conditions' => $condition])->toArray();
			    	if($this->request->getData('template') && $this->request->getData('template') != '')
			    	{
			    		$template_id = $this->request->getData('template');
			    		$find_fields = $FieldMaster->find('all', ['conditions' => ['FieldMaster.template_master_id' => $template_id]])->toArray();
			    	}
	        	}
        	}
            
		}
        $this->set(compact('entry', 'find_templates', 'exchange_id', 'template_id', 'find_fields'));
    }

    /**
     * Edit method
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->loadModel('EntryTxn');
        $entry = $this->EntryMaster->get($id);
        $enrty_txn = $this->EntryTxn->find('all', ['conditions' => ['EntryTxn.entry_master_id' => $id]])->toArray();
        if ($this->request->is(['patch', 'post', 'put'])) {
        	if($this->request->getSession()->check('search_data_ids')){
        		$this->request->getSession()->delete("search_data_ids");
        	}
        	if($this->request->getSession()->check('get_data')){
        		$this->request->getSession()->delete("get_data");
        	}
        	$entry_arr = [];
			$entry_field_values= [];
			$entry_field_ids= [];
			$entry_field_values = $this->request->getData('field_value');
			$entry_field_ids = $this->request->getData('field_id');
			if($this->request->getData('entry_status') == 'ongoing')
    		{
    			$entry_arr['entry_status'] = 'published';
    		}
    		else
    		{
    			$entry_arr['entry_status'] = $this->request->getData('entry_status');
    		}
    		$entry_arr['mod_time'] = new \DateTIme();
			$entry = $this->EntryMaster->patchEntity($entry, $entry_arr);

			if ($this->EntryMaster->save($entry)) {
				$insert_new_field = [];
				$find_enrty_txn = [];
				$edit_id = 0;
				foreach ($entry_field_ids as $key => $field_id) {
					$find_enrty_txn = $this->EntryTxn->find('all', ['conditions' => ['EntryTxn.field_id' => $field_id, 'EntryTxn.entry_master_id' => $id]])->first();
					if(!empty($find_enrty_txn))
					{
						$edit_id = $find_enrty_txn->entry_txn_id;
						$new_entry_txn = $this->EntryTxn->get($edit_id);
						$insert_new_field['field_value'] = $entry_field_values[$key];
						$EntryTxn = $this->EntryTxn->patchEntity($new_entry_txn, $insert_new_field);
						$this->EntryTxn->save($EntryTxn);
					}
				}

				$this->Flash->success(__('This Entry has been updated successfully.'));

				if($this->request->getSession()->read('user')=='supervisor')
				{	
					return $this->redirect(['action' => 'supervisorHome']);
				}
				else
				{
					return $this->redirect(['action' => 'operatorHome']);
				}
			} else {
				//$this->Flash->error(__('We are having some problem. Please try later.'));
				if($user->getErrors()){
					$error_msg = [];
					foreach( $user->getErrors() as $key=>$errors){
						if(is_array($errors)){
							foreach($errors as $error){
								$error_msg[]=$error;
							}
						}else{
							$error_msg[]=$errors;
						}
					}
					if(!empty($error_msg)){
						$this->Flash->error(
							"user could not be saved. ".__(implode(" and ", $error_msg))
						);
					}
				}
			}
        }
        $this->set(compact('entry', 'enrty_txn'));
    }
    /**
     * Change Password
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    /**
     * Delete method
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function reset()
    {
    	$this->request->getSession()->delete("search_data_ids");
    	$this->request->getSession()->delete("get_data");
        if($this->request->getSession()->read('user')=='supervisor')
		{	
			return $this->redirect(['action' => 'supervisorHome']);
		}
		else
		{
			return $this->redirect(['action' => 'operatorHome']);
		}
        
    }
    public function delete($id = null)
    {
    	$this->loadModel('EntryTxn');
    	$this->loadModel('TemplateMaster');
    	$this->loadModel('ExchangeMaster');
    	$primary_pdf_name = '';
        $user = $this->EntryMaster->get($id);
        if ($this->EntryMaster->delete($user)) {
        	$primary_pdf_name = $user->primary_pdf_name;
        	$template_master_id = $user->template_master_id;
        	$find_template = $this->TemplateMaster->find('all', ['conditions' => ['TemplateMaster.template_master_id' => $template_master_id]])->first();
        	if(!empty($find_template))
        	{
        		$find_exchange = $this->ExchangeMaster->find('all', ['conditions' => ['ExchangeMaster.exchange_master_id' => $find_template->exchange_master_id]])->first();
        		if(!empty($find_exchange))
	        	{
	        		$exchange_master_directory = $find_exchange->exchange_master_directory;
		        	if (file_exists(WWW_ROOT.$this->pdfdump_folder.DS.$primary_pdf_name.'.pdf') && $primary_pdf_name!='')
		        	{
		        		$copy_from = WWW_ROOT.$this->pdfdump_folder.DS.$primary_pdf_name.'.pdf';
		        		$copy_to = WWW_ROOT.$this->pdfmaster_folder.DS.$exchange_master_directory.DS.$primary_pdf_name.'.pdf';
		            	if(copy($copy_from,$copy_to))
		            	{
		            		unlink($copy_from);
		            	}
		            }
		        }
        	}
        	$this->EntryTxn->deleteAll(['entry_master_id' => $user->entry_master_id]);
            $this->Flash->success(__('The Entry has been deleted successfully.'));
        } else {
            $this->Flash->error(__('The Entry could not be deleted. Please, try again.'));
        }
        if($this->request->getSession()->read('user')=='supervisor')
		{	
			return $this->redirect(['action' => 'supervisorHome']);
		}
		else
		{
			return $this->redirect(['action' => 'operatorHome']);
		}
    }
	public function downloadData()
    {
    	$this->viewBuilder()->layout('false');
    	if ($this->request->is('post')) {
    		//print_r($this->request->getData());exit();
    		$exchange_id = $from_datepicker = $to_datepicker = "";
			if($this->request->getData('exchange') != '')
			{
				$exchange_id = $this->request->getData('exchange');
			}
			if($this->request->getData('from_datepicker') != '')
			{
				$from_datepicker = $this->request->getData('from_datepicker');
				$from_date = date_format(date_create($from_datepicker), "Y-m-d H:i:s");
				$conditions['EntryMaster.mod_time >='] = $from_date;
			}
			if($this->request->getData('to_datepicker') != '')
			{
				$to_datepicker = $this->request->getData('to_datepicker');
				$to_date = date_format(date_create($to_datepicker), "Y-m-d H:i:s");
				$conditions['EntryMaster.mod_time <='] = $to_date;
			}

			if ($this->request->getSession()->check('database_type')) {
	    		$database_type = $this->request->getSession()->read('database_type');
	    	}
	    	else{
	    		$database_type = 0;
	    	}

			$status=$database_type;
			$staticHeaderNameArray2=[];
			$staticHeaderNameArray=[];
			$staticHeaderNameArray=["ENTRY_ID","EXCHANGE_NAME"];
			$this->loadModel('TemplateMaster');
			$this->loadModel('FieldMaster');
			$this->loadModel('ExchangeMaster');
			$template_ids = [];
			$temp_condition = [];
			$temp_condition['TemplateMaster.template_master_status'] = $status;
			if($exchange_id!='')
			{
				$temp_condition['TemplateMaster.exchange_master_id'] = $exchange_id;
			}
    		$find_templates = $this->TemplateMaster->find('all', ['conditions' => $temp_condition])->toArray();
    		if(!empty($find_templates))
    		{
    			foreach ($find_templates as $key => $find_template) {
    				$template_ids[] = $find_template->template_master_id;
    				$find_fields = $this->FieldMaster->find('all', ['conditions' => ['FieldMaster.template_master_id' => $find_template->template_master_id]])->toArray();
    				foreach ($find_fields as $key => $find_field) {
    					$staticHeaderNameArray2[$find_field->field_name]= $find_field->field_name;
    				}
    			}
    			//print_r($template_ids);exit();
	    		$this->loadModel('CommonFields');
	    		$find_common_fields = $this->CommonFields->find('all', ['conditions' => ['CommonFields.common_fields_status' => $status]])->toArray();
	    		if(!empty($find_common_fields)){
	    			foreach ($find_common_fields as $key => $find_common_field) {
	    				$staticHeaderNameArray2[$find_common_field->common_field_name]= $find_common_field->common_field_name;
	    			}
	    		}
				foreach($staticHeaderNameArray2 as $key=>$value){
				  $staticHeaderNameArray[] = $value;
				}
				array_push($staticHeaderNameArray,"PRIMARY_PDF_LINK");
				$count_field=count($staticHeaderNameArray);
				//print_r($staticHeaderNameArray);exit();
				$separator = ",";
				$filename = "data_" . time() . ".csv";
				// Set header content-type to CSV and filename
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename="' . $filename . '";');
				// create a file pointer connected to the output stream
				$output = fopen('php://output', 'w');

				//set CSV column headers
				$fieldNamesArray = $staticHeaderNameArray;
				fputcsv($output, $fieldNamesArray, $separator);
				$conditions['EntryMaster.entry_master_status'] = $status;
				$conditions['EntryMaster.entry_status'] = 'published';
				$conditions['EntryMaster.template_master_id IN'] = $template_ids;
				$this->loadModel('EntryMaster');
				$this->loadModel('EntryTxn');
				//print_r($conditions);exit();
	    		$find_entry_list = $this->EntryMaster->find('all', ['conditions' => $conditions, 'order' => ['EntryMaster.mod_time' => 'DESC']])->toArray();
	    		$maxColNo = count($fieldNamesArray);
	    		$maxColNo = $maxColNo-3;
	    		//echo count($find_entry_list);exit;
	    		if(!empty($find_entry_list)){
	    			foreach ($find_entry_list as $key => $entry_list) {
	    				$find_temp_details = $this->TemplateMaster->find('all', ['conditions' => ['TemplateMaster.template_master_id' => $entry_list->template_master_id]])->first();
	    				$find_exc_details = $this->ExchangeMaster->find('all', ['conditions' => ['ExchangeMaster.exchange_master_id' => $find_temp_details->exchange_master_id]])->first();
						$lineData=[];
						$lineData[]=$entry_list->entry_master_id;
						$lineData[]=$find_exc_details->exchange_master_name;
						$sl=0;
						$j=0;
						for($i = 2; $i < $maxColNo+2; $i++){
							$sl++;
							$find_entry_txn = $this->EntryTxn->find('all', ['conditions' => ['EntryTxn.entry_master_id' => $entry_list->entry_master_id]])->toArray();
							if(!empty($find_entry_txn)){
								foreach ($find_entry_txn as $key => $entry_txn) {
									$find_field_details = $this->FieldMaster->find('all', ['conditions' => ['FieldMaster.field_master_id' => $entry_txn->field_id]])->first();
									if (strcasecmp($fieldNamesArray[$i], $find_field_details->field_name) == 0) {
										if($entry_txn->field_value!=''){
											$lineData[]=$entry_txn->field_value;
											//echo $lineData.'<br>';
										}
										else{
											//array_push($lineData,"-");
											$lineData[] = '-';
										}
										$j++;
									}
								}
							}
							if($sl!=$j)
							{
								//array_push($lineData,"-");
								$lineData[] = '-';
								$j++;
							}
						}
						// Check if the request was via HTTPS or HTTP
						$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
						// Get the server name
						$host = $_SERVER['HTTP_HOST'];
						// Get the base URL
						$base_url = $protocol . $host;
						if(file_exists(WWW_ROOT.$this->pdfdump_folder.DS.$entry_list->primary_pdf_name.'.pdf'))
                        {
							$lineData[]= $base_url.DS.$this->pdfdump_folder.DS.$entry_list->primary_pdf_name.'.pdf';
						}
						else
						{
							$lineData[] = '-';
						}
						fputcsv($output, $lineData, $separator);
	    			}
	    		}
	    		fclose($output);exit;
    		}
    		else
			{
				$this->Flash->error(__('No data found, try again'));
				return $this->redirect(array("controller" => "EntryMaster", "action" => "supervisorHome"));
			}	
    	}
    }
    public function fetchRelatedData()
    {
    	$this->loadModel('FieldMaster');
    	$this->viewBuilder()->layout('false');
		$data['msg'] = "We are having some problem. Please, try again.";
		if ($this->request->is(['patch', 'post', 'put'])):
			//pr($this->request->getData());exit;
			$find_fields = [];
			if($this->request->getData('template_id') && $this->request->getData('template_id') != '')
	    	{
	    		$template_id = $this->request->getData('template_id');
	    		$find_fields = $this->FieldMaster->find('all', ['conditions' => ['FieldMaster.template_master_id' => $template_id]])->toArray();
	    		if(!empty($find_fields))
	    		{
	    			$data['find_fields']=$find_fields;
	    			$data['success'] = "success";
	    		}
	    		else
	    		{
	    			$data['empty'] = "empty";
	    		}
	    	}
			
		endif;
		echo json_encode($data);
		exit;
    }

    public function uploadPdf()
    {
    	$this->loadModel('ViewFileMaster2');
    	$this->loadModel('ExchangeMaster');
    	$this->viewBuilder()->layout('false');

		$data['msg'] = "We are having some problem. Please, try again.";
		if ($this->request->is(['patch', 'post', 'put'])):
		    //print_r($this->request->getData());exit;
			if($this->request->getData('file')!='' && $this->request->getData('exchange_id')!='')
			{	
				$find_exchange = $this->ExchangeMaster->find('all', ['conditions' => ['ExchangeMaster.exchange_master_id' => $this->request->getData('exchange_id')]])->first();
				if(!empty($find_exchange))
				{
					$upload = [];
					$form_data = [];
					$folder_name = $find_exchange->exchange_master_directory;
					$newpdfupload = $this->ViewFileMaster2->newEntity();
					$form_data = $this->request->getData('file');
					$img_extension_array = explode('.', $form_data['name']);
					$upload['exchange_id'] = $this->request->getData('exchange_id');
					$upload['file_name'] = $img_extension_array[0];
					$upload['path'] = $this->pdfmaster_folder.'/'.$folder_name.'/'.$form_data['name'];
					$upload['folder'] = $folder_name;
					$pdfupload = $this->ViewFileMaster2->patchEntity($newpdfupload,$upload);
					if (file_exists(WWW_ROOT.$this->pdfmaster_folder.DS.$folder_name))
		        	{
		            	if(move_uploaded_file($form_data['tmp_name'], WWW_ROOT.$this->pdfmaster_folder.'/'.$folder_name.'/'.$form_data['name']))
		            	{
		            		$this->ViewFileMaster2->save($pdfupload);
		            		$find_files = $this->ViewFileMaster2->find('all', ['conditions' => ['ViewFileMaster2.exchange_id' => $this->request->getData('exchange_id')]])->toArray();
							$files_arr = [];
							if(!empty($find_files)){
						        foreach($find_files as $key =>$file)
						        {
						            if($file!='')
						            {
						                $dir = WWW_ROOT.$this->pdfmaster_folder.DS.$find_exchange->exchange_master_directory;
						                if (count(glob($dir . '/'.$file->file_name.'.pdf')) === 0) {
						                    
						                } else {
						                    $files_arr[$file->file_name] = $file->file_name;
						                }
						            }
						        }
						    }
						    $data['success'] = "uploded";
						    $data['file_arr'] = $files_arr;
		            	}
		            }
		            //$data['form_data'] = $upload;
				}
			}	
		endif;
		echo json_encode($data);
		exit;
    }
    public function dublicatePdf()
    {
    	$this->viewBuilder()->layout('false');
    	$this->loadModel('ExchangeMaster');
    	$this->loadModel('ViewFileMaster2');
		$data['msg'] = "We are having some problem. Please, try again.";
		if ($this->request->is(['patch', 'post', 'put'])):
			//pr($this->request->getData());exit;
			$find_fields = [];
			if($this->request->getData('pdf')!='' && $this->request->getData('exchange_id') != '')
	    	{
	    		$find_exchange = $this->ExchangeMaster->find('all', ['conditions' => ['ExchangeMaster.exchange_master_id' => $this->request->getData('exchange_id')]])->first();
				if(!empty($find_exchange))
				{
					$folder_name = $find_exchange->exchange_master_directory;
					$pdf_name = $this->request->getData('pdf');
					//$check_pdf = $this->request->getData('pdf');
					$new_name = $pdf_name.'_dup_1';
					$check_files = $this->ViewFileMaster2->find('all', ['conditions' => ['ViewFileMaster2.exchange_id' => $this->request->getData('exchange_id'), 'ViewFileMaster2.file_name' => $new_name]])->first();
					if(isset($check_files) && !empty($check_files)){
						$new_name = $pdf_name.'_dup_'.time();
					}
					//$extension = end($extension_array);
	    			copy(WWW_ROOT.$this->pdfmaster_folder.'/'.$folder_name.'/'.$pdf_name.'.pdf', WWW_ROOT.$this->pdfmaster_folder.'/'.$folder_name.'/'.$new_name.'.pdf');
	    			$upload=[];
	    			$newpdfupload = $this->ViewFileMaster2->newEntity();
	    			$upload['exchange_id'] = $this->request->getData('exchange_id');
					$upload['file_name'] = $new_name;
					$upload['path'] = $this->pdfmaster_folder.'/'.$folder_name.'/'.$new_name.'.pdf';
					$upload['folder'] = $folder_name;
					$pdfupload = $this->ViewFileMaster2->patchEntity($newpdfupload,$upload);
					$this->ViewFileMaster2->save($pdfupload);

					$find_files = $this->ViewFileMaster2->find('all', ['conditions' => ['ViewFileMaster2.exchange_id' => $this->request->getData('exchange_id')]])->toArray();
					$files_arr = [];
					if(!empty($find_files)){
				        foreach($find_files as $key =>$file)
				        {
				            if($file!='')
				            {
				                $dir = WWW_ROOT.$this->pdfmaster_folder.DS.$find_exchange->exchange_master_directory;
				                //
				                if (count(glob($dir . '/'.$file->file_name.'.pdf')) === 0) {
				                    
				                } else {
				                    $files_arr[$file->file_name] = $file->file_name;
				                }
				            }
				        }
				    }
				    $data['success'] = "dublicate";
				    $data['file_arr'] = $files_arr;
	    		}
	    	}
			
		endif;
		echo json_encode($data);
		exit;
    }
    public function movetoSpill($id = null)
    {
    	$this->loadModel('EntryTxn');
    	$this->loadModel('DeletedFile');
    	$this->loadModel('TemplateMaster');
    	$this->loadModel('ExchangeMaster');
    	$this->loadModel('ViewFileMaster2');
		$check_entry = $this->EntryMaster->get($id);
		$primary_pdf_name = $check_entry->primary_pdf_name;
		$check_template = $this->TemplateMaster->find('all', ['conditions' => ['TemplateMaster.template_master_id' => $check_entry->template_master_id]])->first();
		if ($this->request->getSession()->check('user_data')) {
    		$user_data = $this->request->getSession()->read('user_data');
    		$user_id = $user_data['user_id'];
    	}
		if(!empty($check_template))
		{
			$exchange_master_id = $check_template->exchange_master_id;
			$check_files = $this->ViewFileMaster2->find('all', ['conditions' => ['ViewFileMaster2.exchange_id' => $exchange_master_id, 'ViewFileMaster2.file_name' => $primary_pdf_name]])->first();
			if(!empty($check_files))
			{
				if(file_exists(WWW_ROOT.$this->pdfdump_folder.'/'.$primary_pdf_name.'.pdf'))
		        {
		        	$upload = [];
		        	$new_updated_entry = [];
		        	$source = WWW_ROOT.$this->pdfdump_folder.'/'.$primary_pdf_name.'.pdf';
		        	$destination = WWW_ROOT.$this->deletedpdf_folder.'/'.$primary_pdf_name.'.pdf';
		        	if(rename($source, $destination))
		        	{
		        		$newpdfupload = $this->DeletedFile->newEntity();

		        		$upload['file_name'] = $primary_pdf_name;
						$upload['file_directory'] = $check_files->folder;
						$upload['user_id'] = $user_id;
						$upload['delete_time'] = new \DateTIme();
		        		$pdfupload = $this->DeletedFile->patchEntity($newpdfupload,$upload);

						if($this->DeletedFile->save($pdfupload))
						{
							$this->ViewFileMaster2->deleteAll(['id' => $check_files->id]);
							$new_updated_entry['entry_master_status'] = 1;
							$update_entry = $this->EntryMaster->patchEntity($check_entry,$new_updated_entry);
							if($this->EntryMaster->save($update_entry))
							{
								$this->Flash->success(__('The Entry has been deleted successfully.'));
								return $this->redirect(['action' => 'supervisorHome']);
							}
						}
		        	}
		        }
			}
		}	
    }
    public function deletePdf()
    {
    	$this->viewBuilder()->layout('false');
    	$this->loadModel('ExchangeMaster');
    	$this->loadModel('ViewFileMaster2');
    	$this->loadModel('DeletedFile');
		$data['msg'] = "We are having some problem. Please, try again.";
		if ($this->request->is(['patch', 'post', 'put'])):
			if ($this->request->getSession()->check('user_data')) {
	    		$user_data = $this->request->getSession()->read('user_data');
	    		$user_id = $user_data['user_id'];
	    	}
			if($this->request->getData('pdf')!='' && $this->request->getData('exchange_id') != '')
	    	{
	    		$find_exchange = $this->ExchangeMaster->find('all', ['conditions' => ['ExchangeMaster.exchange_master_id' => $this->request->getData('exchange_id')]])->first();
				if(!empty($find_exchange))
				{
					$folder_name = $find_exchange->exchange_master_directory;
					$pdf_name = $this->request->getData('pdf');
					$check_files = $this->ViewFileMaster2->find('all', ['conditions' => ['ViewFileMaster2.exchange_id' => $this->request->getData('exchange_id'), 'ViewFileMaster2.file_name' => $pdf_name]])->first();
					if(isset($check_files) && !empty($check_files) && file_exists(WWW_ROOT.$this->pdfmaster_folder.'/'.$folder_name.'/'.$pdf_name.'.pdf'))
					{
		    			if(rename(WWW_ROOT.$this->pdfmaster_folder.'/'.$folder_name.'/'.$pdf_name.'.pdf', WWW_ROOT.$this->deletedpdf_folder.'/'.$pdf_name.'.pdf'))
		    			{
		    				$newpdfupload = $this->DeletedFile->newEntity();
			        		$upload['file_name'] = $pdf_name;
							$upload['file_directory'] = $folder_name;
							$upload['user_id'] = $user_id;
							$upload['delete_time'] = new \DateTIme();
			        		$pdfupload = $this->DeletedFile->patchEntity($newpdfupload,$upload);

							if($this->DeletedFile->save($pdfupload))
							{
								$this->ViewFileMaster2->deleteAll(['id' => $check_files->id]);
								$find_files = $this->ViewFileMaster2->find('all', ['conditions' => ['ViewFileMaster2.exchange_id' => $this->request->getData('exchange_id')]])->toArray();
								$files_arr = [];
								if(!empty($find_files)){
							        foreach($find_files as $key =>$file)
							        {
							            if($file!='')
							            {
							                $dir = WWW_ROOT.$this->pdfmaster_folder.DS.$find_exchange->exchange_master_directory;
							                //
							                if (count(glob($dir . '/'.$file->file_name.'.pdf')) === 0) {
							                    
							                } else {
							                    $files_arr[$file->file_name] = $file->file_name;
							                }
							            }
							        }
							    }
							    $data['success'] = "deleted";
							    $data['file_arr'] = $files_arr;
							}
		    			}
		    		}
	    		}
	    	}
			
		endif;
		echo json_encode($data);
		exit;
    }
}
