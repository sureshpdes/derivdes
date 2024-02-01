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
 * FtpManagement Controller
 *
 * @property \App\Model\Table\FtpManagementTable $FtpManagement
 *
 * @method \App\Model\Entity\user[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FtpManagementController extends AppController
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
			//return $this->redirect(array("controller" => "FtpManagement", "action" => "administrator_home"));
		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
		$this->paginate = [
			'limit' => 100,
		];
        $ftpmanagement = $this->paginate($this->FtpManagement);

        $this->set(compact('ftpmanagement'));
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
        $user = $this->FtpManagement->get($id, [
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
        $FtpManagement = $this->FtpManagement->newEntity();
        if ($this->request->is('post')) {
            $FtpManagement = $this->FtpManagement->patchEntity($FtpManagement, $this->request->getData());
			if ($this->FtpManagement->save($FtpManagement)) {
				$this->Flash->success(__('FTP management configuration has been created successfully.'));
				return $this->redirect(['action' => 'index']);
			} else {
				//$this->Flash->error(__('We are having some problem. Please try later.'));
				if($FtpManagement->getErrors()){
					$error_msg = [];
					foreach( $FtpManagement->getErrors() as $key=>$errors){
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
							"Ftp Management could not be saved. ".__(implode(" and ", $error_msg))
						);
					}
				}
			}
		}
        $this->set(compact('FtpManagement'));
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
		//$id = str_replace($this->security_hash, "", base64_decode($id));
		if($this->request->getSession()->read('user')=='administrator')
		{
			//
			//return $this->redirect(array("controller" => "FtpManagement", "action" => "administrator_home"));
		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
        $FtpManagement = $this->FtpManagement->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $FtpManagement = $this->FtpManagement->patchEntity($FtpManagement, $this->request->getData());
			if ($this->FtpManagement->save($FtpManagement)) {
				$this->Flash->success(__('FTP management configuration successfully updated.'));
				return $this->redirect(['action' => 'index']);
			} else {
				//$this->Flash->error(__('We are having some problem. Please try later.'));
				if($FtpManagement->getErrors()){
					$error_msg = [];
					foreach( $FtpManagement->getErrors() as $key=>$errors){
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
							"FTP management configuration could not be saved. ".__(implode(" and ", $error_msg))
						);
					}
				}
			}
        }
        $this->set(compact('FtpManagement'));
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
        $FtpManagement = $this->FtpManagement->get($id);
        if ($this->FtpManagement->delete($FtpManagement)) {
            $this->Flash->success(__('Ftp Management has been deleted successfully.'));
        } else {
            $this->Flash->error(__('Ftp Management could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
	
}
