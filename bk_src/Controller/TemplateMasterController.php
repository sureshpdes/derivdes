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
 * TemplateMaster Controller
 *
 * @property \App\Model\Table\TemplateMasterTable $TemplateMaster
 *
 * @method \App\Model\Entity\user[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TemplateMasterController extends AppController
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
    	//$this->request->query('exc')
    	if($this->request->getSession()->read('user')=='supervisor')
		{
			if ($this->request->getSession()->check('database_type')) {
    			$database_type = $this->request->getSession()->read('database_type');
	    	}
	    	else{
	    		return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
	    	}
		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
		$exchange_id = '';
		if ($this->request->is('post')) {
			if($this->request->getData('exchange')!='')
			{
				$exchange_id = $this->request->getData('exchange');
			}
		}
		$condition = [];
		if($exchange_id!='')
		{
			$condition['TemplateMaster.exchange_master_id'] = $exchange_id;
		}
    	$condition['TemplateMaster.template_master_status'] = $database_type;
		$this->paginate = [
			'limit' => 50,
			'conditions' => $condition,
		];
        $TemplateMaster = $this->paginate($this->TemplateMaster);

        $this->set(compact('TemplateMaster', 'exchange_id'));
    }

    public function commonField()
    {
    	if($this->request->getSession()->read('user')=='supervisor')
		{
			if ($this->request->getSession()->check('database_type')) {
    			$database_type = $this->request->getSession()->read('database_type');
	    	}
	    	else{
	    		return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
	    	}
		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
		/*$this->paginate = [
			'limit' => 100,
		];
        $TemplateMaster = $this->paginate($this->TemplateMaster);

        $this->set(compact('TemplateMaster'));*/
    }
    public function commonFieldAdd()
    {
    	if ($this->request->getSession()->check('database_type')) {
    		$database_type = $this->request->getSession()->read('database_type');
    	}
    	else{
    		return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
    	}
    	$this->loadModel('CommonFields');
        $CommonFields = $this->CommonFields->newEntity();
        if ($this->request->is('post')) {
            $CommonFields = $this->CommonFields->patchEntity($CommonFields, $this->request->getData());
            $CommonFields->common_fields_status = $database_type;
			if ($this->CommonFields->save($CommonFields)) {
				$this->Flash->success(__('Field has been created successfully.'));
				return $this->redirect(['action' => 'commonField']);
			} else {
				//$this->Flash->error(__('We are having some problem. Please try later.'));
				if($CommonFields->getErrors()){
					$error_msg = [];
					foreach( $CommonFields->getErrors() as $key=>$errors){
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
							"Field could not be saved. ".__(implode(" and ", $error_msg))
						);
					}
				}
			}
		}
        $this->set(compact('CommonFields'));
    }
    public function commonFieldEdit($id=null)
    {
    	if ($this->request->getSession()->check('database_type')) {
    		$database_type = $this->request->getSession()->read('database_type');
    	}
    	else{
    		return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
    	}
    	$this->loadModel('CommonFields');
        $CommonFields = $this->CommonFields->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $CommonFields = $this->CommonFields->patchEntity($CommonFields, $this->request->getData());
			if ($this->CommonFields->save($CommonFields)) {
				$this->Flash->success(__('Field has been updated successfully.'));
				return $this->redirect(['action' => 'commonField']);
			} else {
				//$this->Flash->error(__('We are having some problem. Please try later.'));
				if($CommonFields->getErrors()){
					$error_msg = [];
					foreach( $CommonFields->getErrors() as $key=>$errors){
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
							"Field could not be saved. ".__(implode(" and ", $error_msg))
						);
					}
				}
			}
		}
        $this->set(compact('CommonFields'));
    }
    
    public function commonFieldDelete($id = null)
    {
        //$this->request->allowMethod(['post', 'delete']);
		//$id = str_replace($this->security_hash, "", base64_decode($id));
        $this->loadModel('CommonFields');
        $CommonFields = $this->CommonFields->get($id);;
        if ($this->CommonFields->delete($CommonFields)) {
            $this->Flash->success(__('This Field has been deleted successfully.'));
        } else {
            $this->Flash->error(__('This Field could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'commonField']);
    }

    public function editTempFields($id=null)
    {
    	if($id){
	    	if ($this->request->getSession()->check('database_type')) {
	    		$database_type = $this->request->getSession()->read('database_type');
	    	}
	    	else{
	    		return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
	    	}
	    	$this->loadModel('FieldMaster');
	        $condition['FieldMaster.template_master_id'] = $id;
	        $this->paginate = [
				'limit' => 100,
				'conditions' => $condition,
			];
			$temp_id = $id;
	        $FieldMaster = $this->paginate($this->FieldMaster);
	        $this->set(compact('FieldMaster', 'temp_id'));
	    }
	    else
	    {
	    	return $this->redirect(['action' => 'index']);
	    }
    }
    public function FieldDelete($id = null, $temp_id = null)
    {
        $this->loadModel('FieldMaster');
        $FieldMaster = $this->FieldMaster->get($id);;
        if ($this->FieldMaster->delete($FieldMaster)) {
            $this->Flash->success(__('This Field has been deleted successfully.'));
        } else {
            $this->Flash->error(__('This Field could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'editTempFields', $temp_id]);
    }
    public function FieldAdd($temp_id)
    {
    	if($temp_id){
	    	if ($this->request->getSession()->check('database_type')) {
	    		$database_type = $this->request->getSession()->read('database_type');
	    	}
	    	else{
	    		return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
	    	}
	    	$this->loadModel('FieldMaster');
	        $FieldMaster = $this->FieldMaster->newEntity();
	        if ($this->request->is('post')) {
	            $FieldMaster = $this->FieldMaster->patchEntity($FieldMaster, $this->request->getData());
	            $FieldMaster->template_master_id = $temp_id;
				if ($this->FieldMaster->save($FieldMaster)) {
					$this->Flash->success(__('Field has been created successfully.'));
					return $this->redirect(['action' => 'editTempFields', $temp_id]);
				} else {
					//$this->Flash->error(__('We are having some problem. Please try later.'));
					if($FieldMaster->getErrors()){
						$error_msg = [];
						foreach($FieldMaster->getErrors() as $key=>$errors){
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
								"Field could not be saved. ".__(implode(" and ", $error_msg))
							);
						}
					}
				}
			}
	        $this->set(compact('FieldMaster', 'temp_id'));
	    }
	    else{
	    	return $this->redirect(['action' => 'index']);
	    }
    }
    public function FieldEdit($id = null, $temp_id = null)
    {
    	if ($this->request->getSession()->check('database_type')) {
    		$database_type = $this->request->getSession()->read('database_type');
    	}
    	else{
    		return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
    	}
    	$this->loadModel('FieldMaster');
        $FieldMaster = $this->FieldMaster->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $FieldMaster = $this->FieldMaster->patchEntity($FieldMaster, $this->request->getData());
			if ($this->FieldMaster->save($FieldMaster)) {
				$this->Flash->success(__('Field has been updated successfully.'));
				return $this->redirect(['action' => 'editTempFields', $temp_id]);
			} else {
				//$this->Flash->error(__('We are having some problem. Please try later.'));
				if($FieldMaster->getErrors()){
					$error_msg = [];
					foreach( $FieldMaster->getErrors() as $key=>$errors){
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
							"Field could not be saved. ".__(implode(" and ", $error_msg))
						);
					}
				}
			}
		}
        $this->set(compact('FieldMaster', 'temp_id'));
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
        $user = $this->TemplateMaster->get($id, [
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
    	if ($this->request->getSession()->check('database_type')) {
    		$database_type = $this->request->getSession()->read('database_type');
    	}
    	else{
    		return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
    	}
    	$this->loadModel('FieldMaster');
    	$this->loadModel('CommonFields');
    	$find_common_fields = $this->CommonFields->find('all', ['conditions' => ['CommonFields.common_fields_status' => $database_type]])->toArray();
        $TemplateMaster = $this->TemplateMaster->newEntity();
        //$FieldMaster = $this->FieldMaster->newEntity();
        if ($this->request->is('post')) {
            $TemplateMaster = $this->TemplateMaster->patchEntity($TemplateMaster, $this->request->getData());
            //print_r($this->request->getData('field'));exit();
            $field_ids= [];
            $field_ids = $this->request->getData('field');
            if(empty($field_ids))
            {
            	$field_ids= [0];
            }
            $TemplateMaster->exchange_master_id = $this->request->getData('exchange_list');
			$find_fields = $this->FieldMaster->find('all', ['conditions' => ['FieldMaster.field_master_id IN' => $field_ids]])->toArray();
            $TemplateMaster->template_master_status = $database_type;
			if ($this->TemplateMaster->save($TemplateMaster)) {
				$last_insert_id = $TemplateMaster->template_master_id;
				if(!empty($find_fields))
				{
					$insert_new_field = [];
					foreach ($find_fields as $key => $new_fields) {
						$FieldMaster = $this->FieldMaster->newEntity();
						$insert_new_field['template_master_id'] = $last_insert_id;
						$insert_new_field['field_name'] = $new_fields->field_name;
						$insert_new_field['field_type'] = $new_fields->field_type;
						$insert_new_field['is_mand'] = $new_fields->is_mand;
						$FieldMaster = $this->FieldMaster->patchEntity($FieldMaster, $insert_new_field);
						$this->FieldMaster->save($FieldMaster);
					}
				}
				if(!empty($find_common_fields))
				{
					$insert_field = [];
					foreach ($find_common_fields as $key => $fields) {
						$FieldMaster = $this->FieldMaster->newEntity();
						$insert_field['template_master_id'] = $last_insert_id;
						$insert_field['field_name'] = $fields->common_field_name;
						$insert_field['field_type'] = $fields->common_field_type;
						$insert_field['is_mand'] = $fields->is_mand;
						$FieldMaster = $this->FieldMaster->patchEntity($FieldMaster, $insert_field);
						$this->FieldMaster->save($FieldMaster);
					}
				}
				$this->Flash->success(__('Template has been created successfully.'));
				return $this->redirect(['action' => 'index']);
			} else {
				//$this->Flash->error(__('We are having some problem. Please try later.'));
				if($TemplateMaster->getErrors()){
					$error_msg = [];
					foreach( $TemplateMaster->getErrors() as $key=>$errors){
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
							"Template could not be saved. ".__(implode(" and ", $error_msg))
						);
					}
				}
			}
		}
        $this->set(compact('TemplateMaster'));
    }
    public function fetchRelatedData()
    {
    	$this->loadModel('FieldMaster');
    	$this->viewBuilder()->layout('false');
		$data['msg'] = "We are having some problem. Please, try again.";
		if ($this->request->is(['patch', 'post', 'put'])):
			//pr($this->request->getData());exit;
			$find_templates = [];
			if($this->request->getData("temp_id") != ""){
				$find_templates = $this->FieldMaster->find('all', ['conditions' => ['FieldMaster.template_master_id' => $this->request->getData("temp_id")]])->toArray();
				$data['find_templates']=$find_templates;
			}
			$data['msg'] = "success";
		endif;
		echo json_encode($data);
		exit;
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
    	if ($this->request->getSession()->check('database_type')) {
    		$database_type = $this->request->getSession()->read('database_type');
    	}
    	else{
    		return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
    	}

    	$TemplateMaster = $this->TemplateMaster->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $TemplateMaster = $this->TemplateMaster->patchEntity($TemplateMaster, $this->request->getData());
			if ($this->TemplateMaster->save($TemplateMaster)) {
				$this->Flash->success(__('Template successfully updated.'));
				return $this->redirect(['action' => 'index']);
			} else {
				//$this->Flash->error(__('We are having some problem. Please try later.'));
				if($TemplateMaster->getErrors()){
					$error_msg = [];
					foreach( $TemplateMaster->getErrors() as $key=>$errors){
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
							"Template could not be saved. ".__(implode(" and ", $error_msg))
						);
					}
				}
			}
        }
        
        $this->set(compact('TemplateMaster'));
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
    public function delete($id = null)
    {
    	$this->loadModel('FieldMaster');
    	$this->loadModel('EntryMaster');
		$this->loadModel('EntryTxn');
        $TemplateMaster = $this->TemplateMaster->get($id);
        if ($this->TemplateMaster->delete($TemplateMaster)) {
        	$this->FieldMaster->deleteAll(['template_master_id' => $TemplateMaster->template_master_id]);

    		$find_entry = $this->EntryMaster->find('all', ['conditions' => ['EntryMaster.template_master_id' => $TemplateMaster->template_master_id]])->first();
    		if(!empty($find_entry)){
    			$this->EntryTxn->deleteAll(['entry_master_id' => $find_entry->entry_master_id]);
    		}
    		$this->EntryMaster->deleteAll(['template_master_id' => $TemplateMaster->template_master_id]);

            $this->Flash->success(__('Template has been deleted successfully.'));
        } else {
            $this->Flash->error(__('Template could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
	
}
