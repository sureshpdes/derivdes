<?php
$this->assign('title', "Change Password");
if($this->request->getSession()->check('temp_user'))
{
    $temp_user = $this->request->getSession()->read('temp_user');
}
else
{
     return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
}
?>
<script type="text/javascript">
<!--
$(function(){
	$("#change_password").validationEngine();
})
//-->
</script>
<section class="adduser-sec pdf-manage">
    <div class="row mx-0  g-0 mt-lg-0 mt-5">
        <div class="col-xl-6  col-lg-7 offset-lg-2 offset-xl-3">
            <div class="form-container">
                <div class="text-center">
                    <h4>Change Password</h4>
                </div>
                <div class="form-wrap">
                    <?php echo $this->Form->create("change_password", ['div'=>false, 'method'=>'post', 'id'=>'change_password', 'class'=>'']);?>
                    <?php echo $this->Form->unlockField('new_password');?>
                    <?php echo $this->Form->unlockField('confirm_password');?>
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-4" for="userid">Enter New Passord</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->control('new_password', ['autocomplete' => 'off', 'type' => 'password', 'class' => 'pass form-control form-style validate[required]', 'data-errormessage-value-missing'=>'New Password is required', 'label'=>false, 'div'=>false, 'required'=>false, 'placeholder'=>'Enter your New password']);?>
                            </div>
                        </div>
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-4" for="password">Confirm New Password::</label>
                            <div class="col-sm-8">
                                <?php echo $this->Form->control('confirm_password', ['autocomplete' => 'off', 'type' => 'password', 'class' => 'pass form-control form-style validate[required]', 'data-errormessage-value-missing'=>'Confirm Password is required', 'label'=>false, 'div'=>false, 'required'=>false, 'placeholder'=>'Retype your New password']);?>
                                <input type="checkbox" id="showPassword">
                                <label for="showPassword" id="">Show Password</label>
                            </div>
                        </div>
                </div>
                <div class=" form-button text-end">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
                <?php echo $this->Form->end();?>
            </div>
        </div>
        <?php echo $this->element('footer');?>
    </div>
</section>
<script type='text/javascript'>
    $(document).ready(function(){
        $('#showPassword').click(function(){
            //if($('.pass').attr('type', 'text'));
            $(this).is(':checked') ? $('.pass').attr('type', 'text') : $('.pass').attr('type', 'password');
        });
    });
</script>
