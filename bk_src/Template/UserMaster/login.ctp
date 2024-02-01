<?php
$this->assign('title', "Login");
?>
<script type="text/javascript">
<!--
$(function(){
	$("#login_form").validationEngine();
	$("#login_form").submit(function(){
		if($("#login_form").validationEngine("validate") == true)
		{
			$('#loader_section').show();
			$("#login_form")[0].submit();
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
    <div class="col-10 col-sm-8 col-md-6 col-lg-4 login-form">
        <?php echo $this->Form->create("login", ['div'=>false, 'method'=>'post', 'id'=>'login_form', 'class'=>'']);?>
            <div class="col-12 logo">
                <?php echo $this->Html->image("/images/logo.png", ['alt' => 'exchange data international', 'class' => 'img-fluid']);?>
            </div>
            <div class="col-12 form-content">

                <div class="col-12 form-right">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-field">
                            <span>
                            	<?php echo $this->Html->image("/images/user.svg", ['alt' => 'user.svg', 'class' => 'mw-100']);?>
                            </span>
                            <?php echo $this->Form->control('user_name', ['autocomplete' => 'off', 'class' => 'form-control validate[required]', 'data-errormessage-value-missing'=>'Username is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Username']);?>
                        </div>
                    </div>
                    <div class="col-12 ">
                        <div class="form-field">
                            <span>
                            	<?php echo $this->Html->image("/images/lock.svg", ['alt' => 'lock.svg', 'class' => 'mw-100']);?>
                            </span>
                            <?php echo $this->Form->control('password', ['autocomplete' => 'off', 'type' => 'password', 'class' => 'form-control validate[required]', 'data-errormessage-value-missing'=>'Password is required', 'label'=>false, 'div'=>false, 'required'=>false, 'placeholder'=>'Password']);?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-end">
                <button type="submit"  class="btn btn-login">Login</button>
            </div>
        <?php echo $this->Form->end();?>
        <?php echo $this->element('footer');?>
    </div>
</section>
<?php echo $this->element('loader-section');?>
