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
 * DeletedFile Controller
 *
 * @property \App\Model\Table\DeletedFileTable $DeletedFile
 *
 * @method \App\Model\Entity\user[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DeletedFileController extends AppController
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

		}
		else
		{
			return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
		}
		$this->paginate = [
			'limit' => 50,
		];
        $deletedfile = $this->paginate($this->DeletedFile);

        $this->set(compact('deletedfile'));
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
        $deletedfile = $this->DeletedFile->get($id, [
            'contain' => []
        ]);

        $this->set('deletedfile', $deletedfile);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
    	
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

    public function restore()
    {
    	$this->loadModel('ViewFileMaster2');
    	$this->loadModel('ExchangeMaster');
    	if ($this->request->is('post')) {
    		$pdf_ids= [];
            $pdf_ids = $this->request->getData('pdffile');
            if(empty($pdf_ids))
            {
            	$this->Flash->error(__('Please Select PDF to restore.'));
            	return $this->redirect(['action' => 'index']);
            }
            else
            {
            	$check_pdf = $this->DeletedFile->find('all', ['conditions' => ['DeletedFile.deleted_file_id IN' => $pdf_ids]])->toArray();
            	if(!empty($check_pdf))
				{
					$insert_new_pdf = [];
					foreach ($check_pdf as $key => $pdf) {
						if (file_exists(WWW_ROOT.$this->deletedpdf_folder.'/'.$pdf->file_name.'.pdf'))
		        		{
		        			$source = WWW_ROOT.$this->deletedpdf_folder.'/'.$pdf->file_name.'.pdf';
		        			if (file_exists(WWW_ROOT.$this->pdfmaster_folder.'/'.$pdf->file_directory))
		        			{
		        				$find_exchange = $this->ExchangeMaster->find('all', ['conditions' => ['ExchangeMaster.exchange_master_directory' => $pdf->file_directory]])->first();
		        				if(!empty($find_exchange)){
		        					$exchange_id = $find_exchange->exchange_master_id;
		        				}
		        				else{
		        					$this->Flash->error(__('Exchnge Master not found.'));
            						return $this->redirect(['action' => 'index']);
		        				}
			        			$destination = WWW_ROOT.$this->pdfmaster_folder.'/'.$pdf->file_directory.'/'.$pdf->file_name.'.pdf';
			        			if(rename($source, $destination))
			        			{
			        				$newpdfupload = $this->ViewFileMaster2->newEntity();
									$insert_new_pdf['exchange_id'] = $exchange_id;
									$insert_new_pdf['file_name'] = $pdf->file_name;
									$insert_new_pdf['path'] = $this->pdfmaster_folder.'/'.$pdf->file_directory.'/'.$pdf->file_name.'.pdf';
									$insert_new_pdf['folder'] = $pdf->file_directory;
									$newpdf = $this->ViewFileMaster2->patchEntity($newpdfupload, $insert_new_pdf);

									if($this->ViewFileMaster2->save($newpdf))
									{
										$this->DeletedFile->deleteAll(['deleted_file_id' => $pdf->deleted_file_id]);
									}
			        			}
			        		}
			        		else
			        		{
			        			$this->Flash->error(__('Exchange Master directory not found.'));
            					return $this->redirect(['action' => 'index']);
			        		}
		        		}
		        		else
		        		{
		        			$this->Flash->error(__('PDF file not found.'));
            				return $this->redirect(['action' => 'index']);
		        		}
					}
				}
            	$this->Flash->success(__('This PDF has been restored successfully.'));
            	return $this->redirect(['action' => 'index']);
            }          
		}
    }
}
