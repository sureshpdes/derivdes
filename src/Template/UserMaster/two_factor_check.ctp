<?php
$this->assign('title', "Two Factor Check");
?>
<?php
	$admin_all_data = $this->request->session()->read("check_user");
	$expired_time = $this->request->getSession()->read('expired_time');
	$generate_otp = $this->request->getSession()->read('generate_otp');
	function hide_email($email) {
        // extract email text before @ symbol
        $em = explode("@", $email);
        $name = implode('@', array_slice($em, 0, count($em) - 1));

        // count half characters length to hide
        $length = floor(strlen($name) / 2);

        // Replace half characters with * symbol
        return substr($name, 0, $length) . str_repeat('*', $length) . "@" . end($em);
	}
?>
<script type="text/javascript">
<!--
$(function(){
	$("#verify_form").validationEngine();
	$("#verify_form").submit(function(){
		if($("#verify_form").validationEngine("validate") == true)
		{
			$('#loader_section').show();
			$("#verify_form")[0].submit();
		}
		else
		{
			return false;
		}
	});
})
//-->
</script>
<section class="login-sec">
    <div class="col-10 col-sm-8 col-md-6 col-lg-4 login-form verify-form">
        <?php echo $this->Form->create(null, ['div'=>false, 'method'=>'post', 'id'=>'verify_form', 'class'=>'']);?>
        <?php echo $this->Form->unlockField('otp');?>
            <div class="col-12 logo text-center">
                <?php echo $this->Html->image("/images/logo.png", ['alt' => 'exchange data international', 'class' => 'img-fluid']);?>
            </div>
            <div class="col-12 text-center">
                <?php echo $this->Html->image("/images/email-notification.png", ['alt' => 'email.png', 'class' => 'email_icon']);?>
            </div>
            <div class="col-12 text-center">
            	<h4 class="mt-2 mb-2 ">Two Factor Authentication</h4>
            	<p class="mb-2 success-msg">Please enter the 6 digit code sent to <br><?php echo hide_email($admin_all_data['user_email']);?>.</p>
            </div>
            <div class="col-12 form-content">
                <div class="col-12">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-field">
                            <?php echo $this->Form->control('otp', ['autocomplete' => 'off', 'class' => 'form-control validate[required]', 'data-errormessage-value-missing'=>'OTP is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Enter OTP', 'type' => 'password']);?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-center">
                <button type="submit"  class="btn btn-login">Verify</button>
            </div>
        <?php echo $this->Form->end();?>
        <?php echo $this->element('footer');?>
    </div>
</section>
<?php echo $this->element('loader-section');?>
