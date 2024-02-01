<?php
$this->assign('title', "Users Edit");
$find_user_types = $this->Common->find_user_type();
$find_user_type_list = [];
foreach ($find_user_types as $find_user_type) {
    $find_user_type_list[$find_user_type->user_type_id] = $find_user_type->user_type;
}
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
<?php
$find_exchange_list = $this->Common->find_exchange_list();
$find_exchanges = $this->Common->find_exchange($user->user_id);

?>
<section class="adduser-sec pdf-manage">
    <div class="row mx-0  g-0 mt-lg-0 mt-5">
        <div class="col-xl-8  col-lg-9 offset-lg-2 offset-xl-2">
            <div class="form-container">
                <?php echo $this->Form->create($user, ['id' => 'edit_form', 'type' => 'file']) ?>
                    <div class="text-center">
                        <h4>Update User</h4>
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
                            <div class="col-md-6 mb-2 mb-lg-0">
                                <?php echo $this->Form->control('new_password', ['autocomplete' => 'off', 'class' => 'form-control form-style', 'data-errormessage-value-missing'=>'User Password is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Password', 'maxlength'=>255]);?>
                            </div>
                            <div class="col-md-6 mb-2 mb-lg-0">
                                <?php echo $this->Form->control('confirm_password', ['autocomplete' => 'off', 'class' => 'form-control form-style ', 'data-errormessage-value-missing'=>'Password is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Confirm Password', 'maxlength'=>255]);?>
                            </div>
                        </div>
                        <div class="form-group row mx-0 ">
                            <div class="col-md-6 mb-2 mb-lg-0">
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
                            <div class="col-md-6 mb-2 mb-lg-0">
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
                        </div>
                    </div>
                    <div class=" form-button text-end">
                        <button class="btn btn-primary show_exchange" type="button">
                            <i class="fa-solid fa-pencil"> </i>
                            Edit Exchanges
                        </button>
                        <button  class="btn btn-success"><i class="fa-solid fa-download"></i> Save</button>
                        <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'users']);?>"><button type="button"  class="btn dele-btn "><i class="fa-solid fa-ban"></i> Cancel</button></a>
                    </div>
                <?php echo $this->Form->end() ?>
                <?php echo $this->Form->create(null, ['id' => 'add_form', 'type' => 'file', 'class' => 'd-none pt-5', 'action' => 'addUserExc']) ?>
                <?php echo $this->Form->unlockField('exchange');?>
                <?php echo $this->Form->unlockField('user_id');?>
                
                    <div class="row mb-1 add_form">
                        <div class=" col-xl-6 col-lg-6  mt-5 mt-lg-0  mt-xl-0 mt-sm-0">
                            <h4>Add User Exchange</h4>
                        </div>
                    </div>
                    <div class="form-wrap">
                        <div class="form-group row mx-0 text-left">
                           <?php 
                            if(!empty($find_exchange_list)):
                                foreach ($find_exchange_list as $exchange_list): 
                            ?>
                            <div class="col-xl-6 col-lg-6">
                                <label> <?php echo $exchange_list->exchange_master_name?> 
                                    <input type="checkbox" name="exchange[]"  class="checkbox" value="<?php echo $exchange_list->exchange_master_id?>">
                                </label>
                            </div>
                            <?php
                                endforeach;
                            endif;
                            ?>
                            <input type="hidden" name="user_id" value="<?php echo $user->user_id?>">
                        </div>
                    </div>
                    <div class=" form-button text-end">
                        <button  class="btn btn-success submit_btn" type="button"><i class="fa-solid fa-download"></i> Save</button>
                        <button type="button"  class="btn dele-btn cancel_add"><i class="fa-solid fa-ban"></i> Cancel</button>
                    </div>
                <?php echo $this->Form->end() ?>
                <div class="exchange_div d-none pt-5 adduser-table">
                    <div class="row mb-1">
                        <div class=" col-xl-6 col-lg-6  mt-5 mt-lg-0  mt-xl-0 mt-sm-0">
                            <h4>Exchange List</h4>
                        </div>
                        <div class="col-xl-6 col-lg-6  text-md-end mt-3 mt-lg-0  mt-xl-0">
                            <button class="btn btn-success add_exchange"><i class="fa-solid fa-pencil"></i> Add</button>
                        </div>
                    </div>
                    <div class="table-box " id="no-more-tables">
                        <table>
                            <colgroup>
                                <col width="50%">
                                <col width="40%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Exchange Name</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(!empty($find_exchanges)):
                                    foreach ($find_exchanges as $admin): 
                                ?>
                                <tr>
                                    <td data-title="Field Name"><?php echo $admin->exchange_master_name?></td>
                                    <td data-title="&nbsp;">
                                        <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'DeleteUserExc', $admin->_matchingData['ExcOptMap']->exh_opt_map_id, $user->user_id]);?>">
                                            <button class="btn dele-btn "><i class="fa-solid fa-xmark"></i> Delete</button>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                    endforeach;
                                else:
                                ?>
                                <tr>
                                     <td class="text-center" colspan="2">No Exchange found for this user</td>
                                </tr>
                                <?php
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $this->element('footer');?>
    </div>
</section>
<script type='text/javascript'>
    $(document).ready(function(){
        $('.show_exchange').click(function(){
            $('.exchange_div').removeClass('d-none');
        });
        $('.cancel_add').click(function(){
            $('#add_form').addClass('d-none');
            $('.add_exchange').removeClass('d-none');
        });
        $('.add_exchange').click(function(){
            $('#add_form').removeClass('d-none');
            $('.add_exchange').addClass('d-none');
            
        });
        $('.submit_btn').click(function(){
            if ($(".checkbox:checked").length == 0) {
                showError('<div class="message error">Please select exchange.</div>');
                return false;
            }
            else
            {
                $('#add_form').submit();
            } 
        });
        
    });
</script>
