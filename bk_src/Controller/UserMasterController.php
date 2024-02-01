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
 * UserMaster Controller
 *
 * @property \App\Model\Table\UserMasterTable $UserMaster
 *
 * @method \App\Model\Entity\user[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UserMasterController extends AppController
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
    	return $this->redirect(['action' => 'users']);
		$this->paginate = [
			'conditions' => ['UserMaster.user_id NOT IN ' => [$this->request->getSession()->read('administrator_data.user_id')]]
		];
        $usermaster = $this->paginate($this->UserMaster);

        $this->set(compact('usermaster'));
    }
    public function users()
    {
    	if($this->request->getSession()->read('user')=='administrator')
		{
			//
			//return $this->redirect(array("controller" => "UserMaster", "action" => "administrator_home"));
		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
		$this->paginate = [
			'limit' => 100,
			'conditions' => ['UserMaster.user_id NOT IN ' => [$this->request->getSession()->read('user_data.user_id')]]
		];
        $usermaster = $this->paginate($this->UserMaster);

        $this->set(compact('usermaster'));
    }
    public function dashboard()
    {
    	$this->request->getSession()->delete("database_type");
    	$this->request->getSession()->delete("search_data_ids");
    	$this->request->getSession()->delete("get_data");
		if($this->request->getSession()->read('user')=='administrator')
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "administrator_home"));
		}
		elseif($this->request->getSession()->read('user')=='supervisor')
		{
			//return $this->redirect(array("controller" => "UserMaster", "action" => "supervisor_home"));
		}
		elseif($this->request->getSession()->read('user')=='operator')
		{
			//return $this->redirect(array("controller" => "UserMaster", "action" => "operator_home"));
		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
    }
    public function administratorHome()
    {
    	$this->viewBuilder()->setLayout('default');
		if($this->request->getSession()->read('user')=='administrator')
		{

		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
		/*$find_passwords = $this->UserMaster->find('all', [])->toArray();
		if(!empty($find_passwords))
		{
			$pass1 = [];
			$hash = [];
			foreach($find_passwords as $find_password)
			{
				$pass = base64_decode($find_password->user_password);
				$hasher = new DefaultPasswordHasher();
				$hash = $hasher->hash($pass);
				$this->UserMaster->updateAll(['password' => $hash], ['user_id' => $find_password->user_id]);

			}
		}*/
		//print_r($pass1);
		//print_r($hash);exit();
		$this->paginate = [
			'limit' => $this->admin_page_limit,
		];
        $UserMaster = $this->paginate($this->UserMaster);
        $this->set(compact('UserMaster'));
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
        $user = $this->UserMaster->get($id, [
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
    	$this->loadModel('UserType');
        $user = $this->UserMaster->newEntity();
        if ($this->request->is('post')) {
            $user = $this->UserMaster->patchEntity($user, $this->request->getData());
            //$hasher = new DefaultPasswordHasher();
            if($user->invite_link_status == 'Y')
	        {
	            $generate_password = $this->generateAlphabetPassword(8);
	        	$user->password = $generate_password;
	        }
			if ($inserted_user = $this->UserMaster->save($user)) {
				if($generate_password)
	            {
	                $invite_url = '';
	            	$find_user_type = $this->UserType->find('all',['conditions'=>['UserType.user_type_id'=>$inserted_user->user_type_id]])->first();
	            	// Check if the request was via HTTPS or HTTP
					$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
					// Get the server name
					$host = $_SERVER['HTTP_HOST'];
					// Get the base URL
					$base_url = $protocol . $host;
					
	            	$invite_url = $base_url.'/user-master/invite-user';
	            	$this->getMailer('Admins')->send('inviteUser', [$inserted_user, $generate_password, $invite_url, $find_user_type->user_type, $this->invite_expiring_time]);
	            }
				$this->Flash->success(__('The user has been created successfully.'));
				return $this->redirect(['action' => 'users']);
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
							"User could not be saved. ".__(implode(" and ", $error_msg))
						);
					}
				}
			}
		}
        $this->set(compact('user'));
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
        $user = $this->UserMaster->get($id);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
			if($this->request->getData('new_password')== "" && $this->request->getData('confirm_password')!= "")
        	{
				$this->Flash->error(__('Please enter password.'));
			}
			elseif($this->request->getData('new_password')!= "" && $this->request->getData('confirm_password') == ""){
				$this->Flash->error(__('Please enter confirm password.'));
			}
			elseif($this->request->getData('new_password')!=$this->request->getData('confirm_password'))
			{
				$this->Flash->error(__('Password and confirm password does not match.'));
			}
			else
			{
				$user = $this->UserMaster->patchEntity($user, $this->request->getData());
				if($this->request->getData('new_password')!='')
				{
					$user->password = $this->request->getData('new_password');
				}
				if ($this->UserMaster->save($user)) {
					$this->Flash->success(__('The user has been updated successfully.'));
					return $this->redirect(['action' => 'users']);
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
        }
        $this->set(compact('user'));
    }
    /**
     * Change Password
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function InviteUserChangePassword()
    {
    	if($this->request->getSession()->check('temp_user'))
		{
		   $currentuser = $this->request->getSession()->read('temp_user');
		}
		else{
		    return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
		$id = $currentuser['user_id'];

        $user = $this->UserMaster->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
        	if($this->request->getData('new_password')== "" && $this->request->getData('confirm_password')!= "")
        	{
				$this->Flash->error(__('Please enter password.'));
			}
			elseif($this->request->getData('new_password')!= "" && $this->request->getData('confirm_password') == ""){
				$this->Flash->error(__('Please enter confirm password.'));
			}
			elseif($this->request->getData('new_password')!=$this->request->getData('confirm_password'))
			{
				$this->Flash->error(__('Password and confirm password does not match.'));
			}
			else{
				$user = $this->UserMaster->patchEntity($user, $this->request->getData());
				$user->password = $this->request->getData('new_password');
				$user->invite_link_status = 'N';
				if ($this->UserMaster->save($user)) {
					$this->Flash->success(__('The Password has been updated successfully.'));
					$this->request->getSession()->delete('temp_user');
					return $this->redirect(['action' => 'login']);
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
								"Password could not be saved. ".__(implode(" and ", $error_msg))
							);
						}
					}
				}
           	}

        }
        $this->set(compact('user'));
    }
    public function changePassword($id = null)
    {
    	if($this->request->getSession()->check('user'))
		{
		   $currentuser = $this->request->getSession()->read('user_data');
		}
		else{
		    return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
		$id = $currentuser['user_id'];
		$hasher = new DefaultPasswordHasher();
		
        $user = $this->UserMaster->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if($hasher->check($this->request->getData('old_password'), $user->password))
            {
            	if($this->request->getData('new_password')== "" && $this->request->getData('confirm_password')!= "")
            	{
					$this->Flash->error(__('Please enter password.'));
				}
				elseif($this->request->getData('new_password')!= "" && $this->request->getData('confirm_password') == ""){
					$this->Flash->error(__('Please enter confirm password.'));
				}
				elseif($this->request->getData('new_password')!=$this->request->getData('confirm_password'))
				{
					$this->Flash->error(__('Password and confirm password does not match.'));
				}
				else{
					$user = $this->UserMaster->patchEntity($user, $this->request->getData());
					$user->password = $this->request->getData('new_password');
					if ($this->UserMaster->save($user)) {
						$this->Flash->success(__('The Password has been updated successfully.'));
						return $this->redirect(['action' => 'change-password']);
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
									"Password could not be saved. ".__(implode(" and ", $error_msg))
								);
							}
						}
					}
	           	}
            }
            else
            {
            	$this->Flash->error(__('Old password does not match.'));
            } 
        }
        $this->set(compact('user'));
    }
    /**
     * Delete method
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function addUserExc()
    {
		$this->loadModel('ExcOptMap');
		$this->loadModel('ExchangeMaster');
        if ($this->request->is('post')) {
        	$exchange_ids= [];
        	$user_id = $this->request->getData('user_id');
            $exchange_ids = $this->request->getData('exchange');
            if(empty($exchange_ids))
            {
            	//$exchange_ids= [0];
            }
            else
            {
            	$find_exchanges = $this->ExchangeMaster->find('all', ['conditions' => ['ExchangeMaster.exchange_master_id IN' => $exchange_ids]])->toArray();
            	if(!empty($find_exchanges))
				{
					$insert_new_field = [];
					foreach ($find_exchanges as $key => $exchange) {
						$new_user_exchange = $this->ExcOptMap->newEntity();
						$insert_new_field['exchange_id'] = $exchange->exchange_master_id;
						$insert_new_field['user_id'] = $user_id;
						$user_exchange = $this->ExcOptMap->patchEntity($new_user_exchange, $insert_new_field);
						$this->ExcOptMap->save($user_exchange);
					}
				}
				$this->Flash->success(__('The user exchange has been updated successfully.'));
            }
            
		}
        return $this->redirect(['action' => 'edit', $user_id]);
    }
    public function DeleteUserExc($id = null, $user_id = null)
    {
		$this->loadModel('ExcOptMap');
        $user = $this->ExcOptMap->get($id);
        if ($this->ExcOptMap->delete($user)) {
            $this->Flash->success(__('User Exchange has been deleted successfully.'));
        } else {
            $this->Flash->error(__('User Exchange could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'edit', $user_id]);
    }
    public function delete($id = null)
    {
        $user = $this->UserMaster->get($id);
        if ($this->UserMaster->delete($user)) {
            $this->Flash->success(__('The user has been deleted successfully.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'users']);
    }

	public function login()
    {	
		if($this->Auth->user())
		{
			$this->Flash->error(__('You are already logged in.'));
			return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
		}
		$this->viewBuilder()->setLayout('user_login_layout');
		if ($this->request->is('post')) 
		{
			$find_user_email = $this->UserMaster->find('all', ['conditions' => ['UserMaster.user_name' => $this->request->getData('user_name')]])->first();
			if($find_user_email)
			{
				$find_user = $this->Auth->identify();
				if($find_user) 
				{
					if($find_user['is_active'] == 1)
					{
						if($find_user['2fa_status'] =='Y')
						{
							$expired_time = strtotime('+ '.$this->login_expiring_time, strtotime('now'));
							$this->request->getSession()->write("expired_time", $expired_time);
							$this->request->getSession()->write("check_user", $find_user);
							$generate_otp = $this->generateAlphabetPassword();
							//$generate_otp = rand(100000,999999);
							$this->request->getSession()->write("generate_otp", $generate_otp);
							$this->getMailer('Admins')->send('twoFactorCheck', [$find_user, $generate_otp, $this->login_expiring_time]);
							return $this->redirect(['action' => 'twoFactorCheck']);
						}
						else
						{
				 			if($find_user['user_type_id'] == 1)
							{
								$this->request->getSession()->write("user", 'administrator');
							}
							if($find_user['user_type_id'] == 2)
							{
								$this->request->getSession()->write("user", 'supervisor');
							}
							if($find_user['user_type_id'] == 3)
							{
								$this->request->getSession()->write("user", 'operator');
								
							}
							$this->request->getSession()->write("user_data", $find_user);
							$this->Auth->setUser($find_user);
							$this->Flash->success(__('You have successfully logged in.'));
							return $this->redirect($this->Auth->redirectUrl());
						}
					}
					else
					{
						$this->Flash->error(__('Your profile is not active yet.'));
						return $this->redirect($this->Auth->logout());
					}
				}
				else
				{
					$this->Flash->error(__('Invalid username or password, try again'));
				}
				
			}
        }
	}
	public function twoFactorCheck(){
		$this->viewBuilder()->setLayout('user_login_layout');
		if($this->request->getSession()->check('check_user'))
		{
			if ($this->request->is('post')) 
			{
				if($this->request->getSession()->check('expired_time'))
				{
					$expired_time = $this->request->getSession()->read('expired_time');
					$current_time = strtotime('now');
			 		if($current_time>=$expired_time)
			 		{
			 			$this->Flash->error(__('Your session has expired. Please try again.'));
			 			return $this->redirect($this->Auth->logout());
			 		}
			 		else
			 		{
			 			$otp = $this->request->getData('otp');
			 			$generate_otp = $this->request->getSession()->read("generate_otp");
			 			if($otp==$generate_otp)
			 			{
			 				$find_user = $this->request->getSession()->read('check_user');
				 			if($find_user['user_type_id'] == 1)
							{
								$this->request->getSession()->write("user", 'administrator');
							}
							if($find_user['user_type_id'] == 2)
							{
								$this->request->getSession()->write("user", 'supervisor');
							}
							if($find_user['user_type_id'] == 3)
							{
								$this->request->getSession()->write("user", 'operator');
								
							}

							$this->request->getSession()->write("user_data", $find_user);
							$this->Auth->setUser($find_user);
							$this->Flash->success(__('You have successfully logged in.'));
							
							$this->request->getSession()->delete('expired_time');
							$this->request->getSession()->delete('check_user');
							$this->request->getSession()->delete('generate_otp');
							
							return $this->redirect($this->Auth->redirectUrl());
			 			}
			 			else
			 			{
			 				$this->Flash->error(__('OTP does not match. Please try again.'));
			 			}
			 			
			 		}
				}
			}
		}
		else
		{
			return $this->redirect($this->Auth->logout());
		}
	}
	public function inviteUser($email = null){
		$this->viewBuilder()->setLayout('user_login_layout');
		/*$find_invite_status = $this->UserMaster->find('all', [])->toArray();
		if(!empty($find_invite_status))
		{
			foreach($find_invite_status as $invite_status)
			{
				$expired_time = strtotime('+ '.$this->invite_expiring_time, strtotime($invite_status->creation_time));
				$current_time = strtotime('now');
		 		if($current_time>=$expired_time)
		 		{
		 			$this->UserMaster->updateAll(['invite_link_status' => 'N'], ['user_id' => $invite_status->user_id]);
		 		}
			}
		}*/
		if ($this->request->is('post')) 
		{
			$hasher = new DefaultPasswordHasher();
			if($this->request->getData('user_email')!='' && $this->request->getData('temp_password')!='')
			{
				$find_user_email = $this->UserMaster->find('all', ['conditions' => ['UserMaster.user_email' => $this->request->getData('user_email')]])->first();
				if($find_user_email)
				{
					if($hasher->check($this->request->getData('temp_password'), $find_user_email->password))
	            	{
	            		$this->request->getSession()->write("temp_user", $find_user_email);
	            		return $this->redirect(['action' => 'inviteUserChangePassword']);
	            	}
	            	else
	            	{
	            		$this->Flash->error(__('Temp Password does not  match.'));
	            	}
	            }

			}	
		}
		elseif($email!='')
		{
			$find_user_email = $this->UserMaster->find('all', ['conditions' => ['UserMaster.user_email' => $email]])->first();
			if($find_user_email)
			{
				if($find_user_email->invite_link_status == 'Y'){
					//
				}
				else{
					$this->Flash->error(__('Invite link has expired. Please contact administrator.'));
					return $this->redirect($this->Auth->logout());
				}
			}
			else
			{
			    $this->Flash->error(__('Invalid Email Address.'));
				return $this->redirect($this->Auth->logout());
			}
		}
		else
		{
			return $this->redirect($this->Auth->logout());
		}
		$this->set(compact('find_user_email','email'));
	}
	public function logout()
	{
		$this->Flash->success(__('Your have been logged out successfully.'));
		$this->request->getSession()->delete('user');
		$this->request->getSession()->delete('user_data');
		$this->request->getSession()->delete('database_type');
		return $this->redirect($this->Auth->logout());
	}
	/*public function forgot()
	{
		$user = $this->UserMaster->newEntity();
		$this->viewBuilder()->layout('user_login_layout');
		if ($this->request->is('post')) 
		{
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$password_gen = substr( str_shuffle( $chars ), 0, 8 );
			$password_gen_2 = substr( str_shuffle( $chars ), 0, 8 );
			$get_acc = $this->UserMaster->find('all', ['conditions' => ['UserMaster.email' => $this->request->getData('email')]])->first();
			if($get_acc)
			{
				$user = $this->UserMaster->get($get_acc->id);
				$user = $this->UserMaster->patchEntity($user, $this->request->getData());
				$user->password = $password_gen;

				if ($this->UserMaster->save($user)) 
				{
					$this->getMailer('UserMaster')->send('forgetPassword', [$password_gen, $user]);
					$this->Flash->success(__('Your new password has been send to your email.'));
					return $this->redirect(['action' => 'login']);
				} 
				else 
				{
					$this->Flash->error(__('Sorry. Please, try again.'));
				}
			}
			else
			{
				$this->Flash->error(__('This email address is not exists with us. Please, try again.'));
			}
		}
		$this->set(compact('user'));
        $this->set('_serialize', ['user']);
	}
	*/
}
