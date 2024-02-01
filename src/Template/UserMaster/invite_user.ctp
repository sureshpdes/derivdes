<?php
$this->assign('title', "Invite user");
?>
<?php
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
	$("#invite_form").validationEngine();
	$("#invite_form").submit(function(){
		if($("#invite_form").validationEngine("validate") == true)
		{
			$('#loader_section').show();
			$("#invite_form")[0].submit();
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
        <?php echo $this->Form->create(null, ['div'=>false, 'method'=>'post', 'id'=>'invite_form', 'class'=>'']);?>
        <?php echo $this->Form->unlockField('temp_password');?>
        <?php echo $this->Form->unlockField('user_email');?>
            <div class="col-12 logo text-center">
                <?php echo $this->Html->image("/images/logo.png", ['alt' => 'exchange data international', 'class' => 'img-fluid']);?>
            </div>
            <div class="col-12 text-center">
                <?php echo $this->Html->image("/images/email-notification.png", ['alt' => 'email.png', 'class' => 'email_icon']);?>
            </div>
            <div class="col-12 text-center">
            	<h4 class="mt-2 mb-2 ">Verify your email</h4>
            	<p class="mb-2 success-msg">Please enter the 8 digit temp password sent to <br><?php echo hide_email($email);?>.</p>
            </div>
            <div class="col-12 form-content">
                <div class="col-12">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-field">
                            <?php echo $this->Form->control('temp_password', ['autocomplete' => 'off', 'class' => 'form-control validate[required]', 'data-errormessage-value-missing'=>'Temp Password is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Enter Temp Password', 'type' => 'password']);?>
                            <input type="hidden" name="user_email" value="<?php echo $email?>">
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
