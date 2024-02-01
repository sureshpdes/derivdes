<?php
$this->assign('title', "FTP management");
?>
<script type="text/javascript">
<!--
    $(function(){
        $("#edit_form").validationEngine();
        $("#edit_form").submit(function(){
            if($("#edit_form").validationEngine("validate"))
            {
                $(".loading_img_div").show();
            }
        });
    });
    
//-->
</script>
<section class="adduser-sec pdf-manage">
    <div class="row mx-0  g-0 mt-lg-0 mt-5">
        <div class="col-xl-6  col-lg-7 offset-lg-2 offset-xl-3">
            <div class="form-container">
                <div class="text-center">
                    <h4>FTP Management</h4>
                </div>
                <?php echo $this->Form->create($FtpManagement, ['id' => 'edit_form', 'type' => 'file']) ?>
                    <div class="form-wrap">
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-3">Host IP:</label>
                            <div class="col-sm-5">
                                <?php echo $this->Form->control('host_ip', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'Host IP is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Host IP', 'maxlength'=>255]);?>
                            </div>
                            <label class="control-label col-sm-1">Port:</label>
                            <div class="col-sm-3">
                                <?php echo $this->Form->control('port', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'Port is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Port', 'maxlength'=>255]);?>
                            </div>
                        </div>
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-3" for="userid">User ID:</label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->control('user_id', ['autocomplete' => 'off', 'type' => 'text', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'User ID is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'User ID', 'maxlength'=>255]);?>
                            </div>
                        </div>
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-3" for="password">Password:</label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->control('password', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required] pass', 'data-errormessage-value-missing'=>'Password is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Password', 'maxlength'=>255]);?>
                                <input type="checkbox" id="showPassword">
                                <label for="showPassword" id="">Show Password</label>
                            </div>
                        </div>
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-3" for="userid">Description</label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->control('description', ['autocomplete' => 'off', 'type' => 'text', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'Description is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Description', 'maxlength'=>255]);?>
                            </div>
                        </div>
                    </div>
                    <div class=" form-button text-end">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="<?php echo $this->Url->build(['controller' => 'FtpManagement', 'action' => 'index']);?>"><button type="button"  class="btn dele-btn "><i class="fa-solid fa-ban"></i> Cancel</button></a>
                    </div>
                <?php echo $this->Form->end() ?> 
            </div>
        </div>
        <?php echo $this->element('footer');?>
    </div>
</section>
<script type='text/javascript'>
    $(document).ready(function(){
        $('#showPassword').click(function(){
            $(this).is(':checked') ? $('.pass').attr('type', 'text') : $('.pass').attr('type', 'password');
        });
    });
</script>
