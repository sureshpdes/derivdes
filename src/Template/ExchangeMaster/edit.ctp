<?php
//echo $this->request->getSession()->read('administrator_data.id').'<br>';
//print_r($usermaster)$usermaster->password;
$this->assign('title', "Exchange Edit");
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
        <div class="col-xl-8  col-lg-9 offset-lg-2 offset-xl-2">
            <div class="form-container">
                 <?php echo $this->Form->create($exchange, ['id' => 'edit_form', 'type' => 'file']) ?>
                    <div class="text-center">
                        <h4>Edit-Exchange</h4>
                    </div>
                    <div class="form-wrap">
                        <div class="form-group row mx-0 align-items-start">
                            <div class="col-md-4 mb-2 mb-lg-0 px-1">
                                <?php echo $this->Form->control('exchange_master_code', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'Exchange Code is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Exchange Code', 'maxlength'=>255]);?>
                            </div>
                            <div class="col-md-4 mb-2 mb-lg-0 px-1">
                                <?php echo $this->Form->control('exchange_master_name', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'Exchange Name is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Exchange Name', 'maxlength'=>255]);?>
                            </div>
                            <div class="col-md-4 mb-2 mb-lg-0 px-1">
                                <?php echo $this->Form->control('exchange_master_location', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'Exchange Location is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Exchange Location', 'maxlength'=>255]);?>
                            </div>
                        </div>
                        <div class="form-group row mx-0 ">
                            <div class="col-md-6 mb-2 mb-lg-0  px-1 ">
                                <?php echo $this->Form->control('exchange_master_desc', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'Description is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Description']);?>
                            </div>
                            <div class="col-md-6 mb-2 mb-lg-0 px-1">
                                <?php echo $this->Form->control('exchange_master_directory', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'FTP Directory is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'FTP Directory']);?>
                            </div>
                            
                        </div>
                    </div>
                    <div class=" form-button text-end">
                        <button class="btn btn-success"><i class="fa-solid fa-download"></i> Save</button>
                        <a href="<?php echo $this->Url->build(['controller' => 'ExchangeMaster', 'action' => 'index']);?>"><button type="button" class="btn dele-btn "><i class="fa-solid fa-ban"></i> Cancel</button></a>
                    </div>
                <?php echo $this->Form->end() ?> 
            </div>
        </div>
        <?php echo $this->element('footer');?>
    </div>
</section>
