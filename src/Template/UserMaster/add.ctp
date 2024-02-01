<?php
$this->assign('title', "Users Add");
$find_user_types = $this->Common->find_user_type();
$find_user_type_list = [];
foreach ($find_user_types as $find_user_type) {
    $find_user_type_list[$find_user_type->user_type_id] = $find_user_type->user_type;
}
?>
<script type="text/javascript">
<!--
    $(function(){
        $("#add_form").validationEngine();
        $("#add_form").submit(function(){
            if($("#add_form").validationEngine("validate"))
            {
                $(".loading_img_div").show();
            }
        });
    });
//-->
</script>
<section class="adduser-sec pdf-manage">
    <div class="row mx-0  g-0 mt-lg-0 mt-5">
        <div class="col-xl-8  col-lg-9 offset-lg-2 offset-xl-2">
            <div class="form-container">
                <?php echo $this->Form->create($user, ['id' => 'add_form', 'type' => 'file']) ?>
                    <div class="text-center">
                        <h4>Create New User</h4>
                    </div>
                    <div class="form-wrap">
                        <div class="form-group row mx-0 align-items-start">
                            <div class="col-md-2 mb-2 mb-lg-0">
                                <?php
                                    echo $this->Form->input('user_salutation', [
                                        'type' => 'select',
                                        'options' => $salutation_type,
                                        'label' => false,
                                        'class'=>"selectClass mw-100 w-100",
                                    ]);
                                ?>
                            </div>
                            <div class="col-md-5 mb-2 mb-lg-0">
                                <?php echo $this->Form->control('user_first_name', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'First name is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'First name', 'maxlength'=>255]);?>
                            </div>
                            <div class="col-md-5 mb-2 mb-lg-0">
                                <?php echo $this->Form->control('user_last_name', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'Last name is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Last name', 'maxlength'=>255]);?>
                            </div>
                        </div>
                        <div class="form-group row mx-0 ">
                            <div class="col-md-2 mb-2 mb-lg-0 ">
                                <?php
                                    echo $this->Form->input('user_type_id', [
                                        'type' => 'select',
                                        'options' => $find_user_type_list,
                                        'label' => false,
                                        'class'=>"selectClass mw-100 w-100",
                                    ]);
                                ?>
                            </div>
                            <div class="col-md-5 mb-2 mb-lg-0">
                                <?php echo $this->Form->control('user_name', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'User name is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'User name', 'maxlength'=>255]);?>
                            </div>
                            <div class="col-md-5 mb-2 mb-lg-0">
                                <?php echo $this->Form->control('user_email', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required,custom[email]]', 'data-errormessage-value-missing'=>'User Email is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'User Email', 'maxlength'=>255]);?>
                            </div>
                        </div>
                        <div class="form-group row mx-0 ">
                            <div class="col-md-4 mb-2 mb-lg-0">
                                <?php
                                    echo $this->Form->input('is_active', [
                                        'type' => 'select',
                                        'options' => $is_active,
                                        'label' => false,
                                        'empty' => 'Select Active Status',
                                        'class'=>"selectClass mw-100 w-100",
                                    ]);
                                ?>
                            </div>
                            <div class="col-md-4 mb-2 mb-lg-0">
                                <?php
                                    echo $this->Form->input('2fa_status', [
                                        'type' => 'select',
                                        'options' => $two_fa_status,
                                        'label' => false,
                                        'empty' => 'Select 2Fa Status',
                                        'class'=>"selectClass mw-100 w-100",
                                    ]);
                                ?>
                            </div>
                            <div class="col-md-4 mb-2 mb-lg-0">
                                <?php
                                    echo $this->Form->input('invite_link_status', [
                                        'type' => 'select',
                                        'options' => $invite_status,
                                        'label' => false,
                                        'empty' => 'Invite Link',
                                        'class'=>"selectClass mw-100 w-100",
                                    ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class=" form-button text-end">
                        <button  class="btn btn-success"><i class="fa-solid fa-download"></i> Save</button>
                       <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'users']);?>"><button type="button"  class="btn dele-btn "><i class="fa-solid fa-ban"></i> Cancel</button></a>
                    </div>
                <?php echo $this->Form->end() ?>
            </div>
        </div>
        <?php echo $this->element('footer');?>
    </div>
</section>
