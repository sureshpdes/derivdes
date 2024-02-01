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

/**
 * ExchangeMaster Controller
 *
 * @property \App\Model\Table\ExchangeMasterTable $ExchangeMaster
 *
 * @method \App\Model\Entity\user[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExchangeMasterController extends AppController
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
    	if($this->request->getSession()->read('user')=='supervisor')
		{
			//
			//return $this->redirect(array("controller" => "ExchangeMaster", "action" => "administrator_home"));
		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
		if ($this->request->getSession()->check('database_type')) {
    		$database_type = $this->request->getSession()->read('database_type');
    	}
    	else{
    		return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
    	}
    	$condition = [];
    	$condition['ExchangeMaster.exchange_master_status'] = $database_type;
		$this->paginate = [
			'limit' => 50,
			'conditions' => $condition,
		];
        $exchangemaster = $this->paginate($this->ExchangeMaster);
        /*foreach ($exchangemaster as $admin){
        	if($admin->exchange_master_directory!='')
            {
            	//$admin->exchange_master_directory = str_replace(array('\\','/',':','*','?','"','<','>','|',' '),'_', $admin->exchange_master_directory);
            	if (!file_exists(WWW_ROOT.$this->pdfmaster_folder.DS.$admin->exchange_master_directory))
            	{
	            	mkdir(WWW_ROOT.$this->pdfmaster_folder.DS.$admin->exchange_master_directory, 0777, true);
	            }
            }
        }*/
        $this->set(compact('exchangemaster'));
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
        $user = $this->ExchangeMaster->get($id, [
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
    	$user_data = [];
    	if ($this->request->getSession()->check('user_data')) {
    		$user_data = $this->request->getSession()->read('user_data');
    		$user_id = $user_data['user_id'];
    	}
    	//print_r($user_data);exit();
    	$this->loadModel('ExcOptMap');
        $exchange = $this->ExchangeMaster->newEntity();
        if ($this->request->is('post')) {
            $exchange = $this->ExchangeMaster->patchEntity($exchange, $this->request->getData());     
				
			if ($exchange_insert = $this->ExchangeMaster->save($exchange)) {
				if($exchange_insert->exchange_master_directory!='')
	            {
	            	//$exchange_insert->exchange_master_directory = str_replace(array('\\','/',':','*','?','"','<','>','|',' '),'_', $exchange_insert->exchange_master_directory);
	            	if (!file_exists(WWW_ROOT.$this->pdfmaster_folder.DS.$exchange_insert->exchange_master_directory))
	            	{
		            	mkdir(WWW_ROOT.$this->pdfmaster_folder.DS.$exchange_insert->exchange_master_directory, 0777, true);
		            }
	            }
	            $insert_new_field = [];
				$ExcOptMap = $this->ExcOptMap->newEntity();
				$insert_new_field['exchange_id'] = $exchange_insert->exchange_master_id;
				$insert_new_field['user_id'] = $user_id;
				$ExcOptMap = $this->ExcOptMap->patchEntity($ExcOptMap, $insert_new_field);
				$this->ExcOptMap->save($ExcOptMap);

				$this->Flash->success(__('The Exchange has been created successfully.'));
				return $this->redirect(['action' => 'index']);
			} else {
				//$this->Flash->error(__('We are having some problem. Please try later.'));
				if($exchange->getErrors()){
					$error_msg = [];
					foreach( $exchange->getErrors() as $key=>$errors){
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
							"Exchange could not be saved. ".__(implode(" and ", $error_msg))
						);
					}
				}
			}
		}
        $this->set(compact('exchange'));
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
		//$id = str_replace($this->security_hash, "", base64_decode($id));
        $exchange = $this->ExchangeMaster->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $exchange = $this->ExchangeMaster->patchEntity($exchange, $this->request->getData());
            $previous_val = $this->ExchangeMaster->get($id);
            $previous_directory = '';
            if($previous_val->exchange_master_directory != $this->request->getData('exchange_master_directory'))
            {

            	$previous_directory = $previous_val->exchange_master_directory;
            	
            }
            //echo $previous_directory.'1';exit();
			if ($exchange_insert = $this->ExchangeMaster->save($exchange)) {
				if($previous_directory!='')
				{
					//$exchange_master_directory = str_replace(array('\\','/',':','*','?','"','<','>','|',' '),'_', $this->request->getData('exchange_master_directory'));
					//$previous_directory = str_replace(array('\\','/',':','*','?','"','<','>','|',' '),'_', $previous_directory);
					if (!file_exists(WWW_ROOT.$this->pdfmaster_folder.DS.$exchange_master_directory))
	            	{
		            	rename(WWW_ROOT.$this->pdfmaster_folder.DS.$previous_directory, WWW_ROOT.$this->pdfmaster_folder.DS.$exchange_master_directory);
		            }
				}
				$this->Flash->success(__('The Exchange has been updated successfully.'));
				return $this->redirect(['action' => 'index']);
			} else {
				//$this->Flash->error(__('We are having some problem. Please try later.'));
				if($exchange->getErrors()){
					$error_msg = [];
					foreach( $exchange->getErrors() as $key=>$errors){
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
							"user user could not be saved. ".__(implode(" and ", $error_msg))
						);
					}
				}
			}
        }
        $this->set(compact('exchange'));
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
        //$this->request->allowMethod(['post', 'delete']);
		//$id = str_replace($this->security_hash, "", base64_decode($id));
		$this->loadModel('ExcOptMap');
		$this->loadModel('FieldMaster');
		$this->loadModel('TemplateMaster');
		$this->loadModel('ViewFileMaster2');
		$this->loadModel('EntryMaster');
		$this->loadModel('EntryTxn');
        $exchange = $this->ExchangeMaster->get($id);
        $find_template = $this->TemplateMaster->find('all', ['conditions' => ['TemplateMaster.exchange_master_id' => $exchange->exchange_master_id]])->first();
        if ($this->ExchangeMaster->delete($exchange)) {
        	$this->ExcOptMap->deleteAll(['exchange_id' => $exchange->exchange_master_id]);
        	$this->ViewFileMaster2->deleteAll(['exchange_id' => $exchange->exchange_master_id]);

        	if(!empty($find_template)){
        		$find_entry = $this->EntryMaster->find('all', ['conditions' => ['EntryMaster.template_master_id' => $find_template->template_master_id]])->first();
        		if(!empty($find_entry)){
        			$this->EntryTxn->deleteAll(['entry_master_id' => $find_entry->entry_master_id]);
        		}
        		$this->EntryMaster->deleteAll(['template_master_id' => $find_template->template_master_id]);
        		$this->FieldMaster->deleteAll(['template_master_id' => $find_template->template_master_id]);
        		$this->TemplateMaster->deleteAll(['exchange_master_id' => $exchange->exchange_master_id]);
        	}
        	
        	//$exchange->exchange_master_directory = str_replace(array('\\','/',':','*','?','"','<','>','|',' '),'_', $exchange->exchange_master_directory);
        	if (file_exists(WWW_ROOT.$this->pdfmaster_folder.DS.$exchange->exchange_master_directory))
	        {
	        	$this->recursiveRemoveDirectory(WWW_ROOT.$this->pdfmaster_folder.DS.$exchange->exchange_master_directory);
	        }
            $this->Flash->success(__('The Exchange has been deleted successfully.'));
        } else {
            $this->Flash->error(__('The Exchange could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
	
}
