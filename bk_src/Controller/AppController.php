<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */

class AppController extends Controller
{
	public $validImageFormats = array("jpg", "gif", "png", "jpeg", 'pdf', 'svg');	
	public $validMostFormats=array("jpg", "png", "jpeg","gif","pdf", "doc", "docx", "xls", "xlsx", "tiff", "tif", "pdf", 'svg');
	public $status = array('Y' => 'Active', 'N' => 'Inactive');
	public $other_status = array('1' => 'Active', '0' => 'Inactive');
	public $cache_level = array('D' => 'Default Cache', 'F' => 'Full Cache', 'N' => 'No Cache');
	public $website_name = 'Derivative';
	public $general_folder = 'general';
	public $upload_folder = 'upload';
	public $admin_folder = 'admin';
	public $header_folder = 'header';
	public $logo_folder = 'logo';
	public $gallery_folder = 'gallery';
	public $page_folder = 'page';
	public $product_folder = 'product';
	public $pdf_folder = 'pdf';
	public $popup_folder = 'popup';
	public $admin_page_limit = 50;
	public $date_time_format = "d-m-Y h:i A";
	public $date_format = "d-m-Y";
	public $security_hash = "3b27a2fee";
	public $google_map_api_key='AIzaSyAnw5M6CDZnL-GvVfmJpnJBS3i9cjiZOls';
	public $google_map_geocode_api_key='AIzaSyAnw5M6CDZnL-GvVfmJpnJBS3i9cjiZOls';
	public $website_type = ['Type 1' => 'Type 1', 'Type 2' => 'Type 2'];
	public $product_type = ['Product Type 1' => 'Product Type 1', 'Product Type 2' => 'Product Type 2'];
	public $max_attempts = ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10'];
	public $product_meta_settings = ['Custom text' => 'Custom text', 'DB variable' => 'DB variable'];
	public $redirect_type = ['301 permanent' => '301 permanent', '302 temporary' => '302 temporary'];
	public $page_type = ['P' => 'Main Page', 'S' => 'Sub Page'];
	public $image_position = ['R' => 'Right', 'L' => 'Left', 'C' => 'Center', 'B' => 'Background'];
	public $field_type = ['text' => 'Text', 'number' => 'Number', 'date' => 'Date'];
	public $salutation_type = ['Mr.' => 'Mr.', 'Miss.' => 'Miss.', 'Mrs.' => 'Mrs.'];
	public $database_type_arr = ['0' => 'DERIV-ACTION DATABASE', '1' => 'SPILL-OVER DATABASE'];
	public $is_active = array('0' => 'Inactive', '1' => 'Active');
	public $pdfmaster_folder = 'pdfmaster';
	public $pdfdump_folder = 'pdfdump';
	public $deletedpdf_folder = 'deletedpdf';
	public $login_expiring_time = '2 minutes';
	public $invite_expiring_time = '24 hours';
	public $two_fa_status = ['Y' => 'Active', 'N' => 'Inactive'];
	public $invite_status = ['Y' => 'Active', 'N' => 'Inactive'];
	
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {

        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        $this->loadComponent('Security');
		$this->loadComponent('Authorization');

			//exit();
		$this->viewBuilder()->setLayout('default');
		
			
    }
	public function beforeFilter(Event $event)
	{
		//exit();
			$this->viewBuilder()->setLayout('default');
			$this->loadComponent('Auth', [
				'loginAction' => [
					'controller' => 'UserMaster',
					'action' => 'login'
				],
				'loginRedirect' => [
					'controller' => 'UserMaster',
					'action' => 'dashboard'
				],
				'logoutRedirect' => [
					'controller' => 'UserMaster',
					'action' => 'login'
				],
				'authError' => 'Did you really think you are allowed to see that?',
				'authenticate' => [
					/*'Authenticate.Advance' => [
						'lockout' => [
							'retries' => 3,
							'expires' => '5 Minuten',
							'file_path' => 'prevent_brute_force',
							'message' => [
								'locked' => __('The maximum number of failed login attempts has been reached. Please try again in {0}.'),
								'login_fail' => __('Wrong user name or password. {0} trials left. Please try again!'),
							]
						],
						'userModel' => 'Users'
					],*/
					'Form' => [
						'fields' => [
							'username' => 'user_name',
							'password' => 'password'
						],
						'userModel' => 'UserMaster'
					]
				],
				'storage' => [ 
						'className' => 'Session', 
						'key' => 'Auth.UserMaster', 
				],
				//'unauthorizedRedirect' => false
			]);
			$this->Auth->setConfig('authenticate', [
				'Form' => ['userModel' => 'UserMaster']
			]); 
			$this->Auth->allow(['login', 'inviteUser', 'InviteUserChangePassword', 'twoFactorcheck']);
			$this->Security->setConfig('unlockedActions', ['fetchRelatedData', 'uploadPdf', 'dublicatePdf', 'deletePdf']);
			
	}
	 /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
    	//exit();

        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->getType(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
		$this->_setErrorLayout();
		$this->set('status', $this->status);
		
		$this->set('other_status', $this->other_status);
		$this->set('cache_level', $this->cache_level);
		$this->set('website_name', $this->website_name);
		$this->set('upload_folder', $this->upload_folder);
		$this->set('general_folder', $this->general_folder);
		$this->set('admin_folder', $this->admin_folder);
		$this->set('header_folder', $this->header_folder);
		$this->set('logo_folder', $this->logo_folder);
		$this->set('gallery_folder', $this->gallery_folder);
		$this->set('page_folder', $this->page_folder);
		$this->set('product_folder', $this->product_folder);
		$this->set('popup_folder', $this->popup_folder);
		$this->set('pdf_folder', $this->pdf_folder);
		$this->set('date_time_format', $this->date_time_format);
		$this->set('date_format', $this->date_format);
		$this->set('security_hash', $this->security_hash);
		$this->set('website_type', $this->website_type);
		$this->set('product_type', $this->product_type);
		$this->set('max_attempts', $this->max_attempts);
		
		$this->set('page_type', $this->page_type);
		$this->set('image_position', $this->image_position);
		$this->set('field_type', $this->field_type);
		$this->set('salutation_type', $this->salutation_type);
		$this->set('database_type_arr', $this->database_type_arr);
		$this->set('pdfmaster_folder', $this->pdfmaster_folder);
		$this->set('pdfdump_folder', $this->pdfdump_folder);
		$this->set('deletedpdf_folder', $this->deletedpdf_folder);
		$this->set('is_active', $this->is_active);
		$this->set('login_expiring_time', $this->login_expiring_time);
		$this->set('invite_expiring_time', $this->invite_expiring_time);
		$this->set('two_fa_status', $this->two_fa_status);
		$this->set('invite_status', $this->invite_status);
		

		
		
		
		
		
		
		
		$this->set('google_map_api_key', $this->google_map_api_key);
		$this->set('google_map_geocode_api_key', $this->google_map_geocode_api_key);

	}
	function _setErrorLayout() 
	{
		if ($this->name == 'CakeError') { 
			$this->layout = '404';
		}
	}
	public function slugify($text){ 
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
	/**
		* Insert user visite in log table
		* @param $type - values : Post / Get / Request

	**/

	
	public function read_all_files($root) {
		if (is_dir($root)){
		  if ($dh = opendir($root)){
			
			while (($file = readdir($dh)) !== false) {
				
				if($file == '.' || $file == '..' || is_dir($file)) {
					//DO NOTHING
				} else {
					if((isset($file) && $file!='') && file_exists($root."/".$file)) {
						//print $root."/".$file."<br/>";
						//unlink($root."/".$file);
					}
				}
			}
			closedir($dh);
		  }
		}
	}
	public function recursiveRemoveDirectory($directory)
	{
		foreach(glob("{$directory}/*") as $file)
		{
			if(is_dir($file)) { 
				$this->recursiveRemoveDirectory($file);
			} else {
				unlink($file);
			}
		}
		rmdir($directory);
	}
	public function generateAlphabetPassword($length = null) {
	    if($length=='')
	    {
	        $length = 6;
	    }
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
        $password = '';
        
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        return $password;
    }

	public function deleteRouteCacheTmp()
	{
		if(file_exists(ROOT."/tmp/cache/myapp_cake_routes_route_collection")) {
			unlink(ROOT."/tmp/cache/myapp_cake_routes_route_collection");
		}
	}

	

}
