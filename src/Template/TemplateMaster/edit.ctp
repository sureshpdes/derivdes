<?php
//echo $this->request->getSession()->read('administrator_data.id').'<br>';
//print_r($usermaster)$usermaster->password;
$this->assign('title', "Template Edit");
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
                 <?php echo $this->Form->create($TemplateMaster, ['id' => 'edit_form', 'type' => 'file']) ?>
                    <div class="text-center">
                        <h4>Edit Template</h4>
                    </div>
                    <div class="form-wrap">
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-3" for="userid"> Template Name : </label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->control('template_master_name', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'Template Name is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Template Name', 'maxlength'=>255]);?>
                            </div>
                        </div>
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-3" for="password">Description  </label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->control('template_master_desc', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'Description is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Description', 'maxlength'=>255]);?>
                            </div>
                        </div>
                    </div>
                    <div class=" form-button text-end">
                        <a href="<?php echo $this->Url->build(['controller' => 'TemplateMaster', 'action' => 'editTempFields', $TemplateMaster->template_master_id ]);?>">
                            <button class="btn btn-primary " type="button">
                                <i class="fa-solid fa-pencil"> </i>
                                Edit Fields
                            </button>
                        </a>
                        <button class="btn btn-success"><i class="fa-solid fa-download"></i> Update</button>
                        <a href="<?php echo $this->Url->build(['controller' => 'TemplateMaster', 'action' => 'index']);?>"><button type="button" class="btn dele-btn "><i class="fa-solid fa-ban"></i> Cancel</button></a>

                    </div>
                <?php echo $this->Form->end() ?> 
            </div>
        </div>
        <?php echo $this->element('footer');?>
    </div>
</section>
