<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

class AdminsMailer extends Mailer
{
	public $website_name = 'Exchange Data International';
	public $general_folder = 'general';
	public $upload_folder = 'upload';
	public $admin_folder = 'admin';
	public $header_folder = 'header';
	public $logo_folder = 'logo';
	public $gallery_folder = 'gallery';
	public $page_folder = 'page';
	public $product_folder = 'product';
    
    public function twoFactorcheck($find_user, $generate_otp, $login_expiring_time) {
		$id = 1;
		$website_title = $this->website_name;
        $this
            ->setFrom(['jayakumar.s@exchange-data.com' => $website_title])
            ->setTo($find_user['user_email'])
            ->setSubject(__($website_title.'::Two Factor Authentication'))
            ->setTemplate('user_two_factor_check_form')
            ->setEmailFormat('html')
            ->setViewVars([
                'user' => $find_user,
                'generate_otp'  => $generate_otp,
                'login_expiring_time'  => $login_expiring_time,
				'website_title' => $website_title,
            ]);
    }
    public function inviteUser($find_user, $generate_password, $invite_url, $user_type, $invite_expiring_time) {
		$id = 1;
		$website_title = $this->website_name;
        $this
            ->setFrom(['jayakumar.s@exchange-data.com' => $website_title])
            ->setTo($find_user['user_email'])
            ->setSubject(__($website_title.'::Invite link'))
            ->setTemplate('invite_link_form')
            ->setEmailFormat('html')
            ->setViewVars([
                'user' => $find_user,
                'generate_password'  => $generate_password,
                'invite_expiring_time'  => $invite_expiring_time,
                'invite_url'  => $invite_url,
                'user_type' => $user_type,
				'website_title' => $website_title,
            ]);
    }
}
