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
 * PdfManagement Controller
 *
 * @property \App\Model\Table\PdfManagementTable $PdfManagement
 *
 * @method \App\Model\Entity\user[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PdfManagementController extends AppController
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
    	if($this->request->getSession()->read('user')=='administrator')
		{
			//
			//return $this->redirect(array("controller" => "PdfManagement", "action" => "administrator_home"));
		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
		$this->paginate = [
			'limit' => 100,
		];
        $PdfManagement = $this->paginate($this->PdfManagement);

        $this->set(compact('PdfManagement'));
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
        $user = $this->PdfManagement->get($id, [
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
        $pdfmanagement = $this->PdfManagement->newEntity();
        if ($this->request->is('post')) {
            $pdfmanagement = $this->PdfManagement->patchEntity($pdfmanagement, $this->request->getData());
			if ($this->PdfManagement->save($pdfmanagement)) {
				$this->Flash->success(__('PDF management configuration has been created successfully.'));
				return $this->redirect(['action' => 'index']);
			} else {
				//$this->Flash->error(__('We are having some problem. Please try later.'));
				if($pdfmanagement->getErrors()){
					$error_msg = [];
					foreach( $pdfmanagement->getErrors() as $key=>$errors){
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
    	$id = 2;
    	if($this->request->getSession()->read('user')=='administrator')
		{
			//
			//return $this->redirect(array("controller" => "FtpManagement", "action" => "administrator_home"));
		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
		//$id = str_replace($this->security_hash, "", base64_decode($id));
		$this->loadModel('ViewFileMaster2');
        $this->loadModel('ExchangeMaster');
        $pdfupload = $this->ViewFileMaster2->newEntity();
        $pdfmanagement = $this->PdfManagement->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
        	if($this->request->getData('form_name') == 'pdf_form'):
	    		if($this->request->getData('input-file-1')['name']== ""){
	                $this->Flash->error(__('Please choose a PDF file.'));
	            }
	            else
	            {
		            $pdfupload = $this->ViewFileMaster2->patchEntity($pdfupload, $this->request->getData());
		            $ExchangeMaster = $this->ExchangeMaster->get($pdfupload->exchange_id);
					$folder_name = $ExchangeMaster->exchange_master_directory;
					//$folder_name = str_replace(array('\\','/',':','*','?','"','<','>','|',' '),'_', $folder_name);
					$extension_array = explode('.', $this->request->getData('input-file-1')['name']);
					$extension = end($extension_array);
					//$image_name ='pdf_file_'.time().rand().".".$extension;
					$image_name = $this->request->getData('input-file-1')['name'];
					$pdfupload->file_name = $extension_array[0];
					$pdfupload->folder = $folder_name;
					$pdfupload->path = $this->pdfmaster_folder.'/'.$folder_name.'/'.$pdfupload->file_name.'.pdf';
					if ($this->ViewFileMaster2->save($pdfupload)) {
						if (file_exists(WWW_ROOT.$this->pdfmaster_folder.DS.$folder_name))
		            	{
			            	move_uploaded_file($this->request->getData('input-file-1')['tmp_name'], WWW_ROOT.$this->pdfmaster_folder.'/'.$folder_name.'/'.$pdfupload->file_name.'.pdf');
			            }
						$this->Flash->success(__('PDF file has been uploded successfully.'));
						return $this->redirect(['action' => 'edit']);
					} else {
						//$this->Flash->error(__('We are having some problem. Please try later.'));
						if($pdfupload->getErrors()){
							$error_msg = [];
							foreach( $pdfupload->getErrors() as $key=>$errors){
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
	        else:
				$pdfmanagement = $this->PdfManagement->patchEntity($pdfmanagement, $this->request->getData());
				if ($this->PdfManagement->save($pdfmanagement)) {
					$this->Flash->success(__('PDF management configuration successfully updated.'));
					return $this->redirect(['action' => 'edit']);
				} else {
					//$this->Flash->error(__('We are having some problem. Please try later.'));
					if($pdfmanagement->getErrors()){
						$error_msg = [];
						foreach( $pdfmanagement->getErrors() as $key=>$errors){
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
								"PDF management configuration could not be saved. ".__(implode(" and ", $error_msg))
							);
						}
					}
				}
			endif;
        }
        
        $this->set(compact('pdfmanagement', 'pdfupload'));
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
        
    }
	
}
