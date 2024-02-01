<?php
$this->assign('title', "Common Field Add");
$mand_arr = ['true' => 'Yes', 'false' => 'No'];
?>
<script type="text/javascript">
<!--
    $(function(){
        $("#add_form").validationEngine();
        $("#add_form").submit(function(){
            if($("#add_form").validationEngine("validate"))
            {
                //$(".loading_img_div").show();
            }
        });
    });
//-->
</script>
<section class="adduser-sec pdf-manage">
    <div class="row mx-0  g-0 mt-lg-0 mt-5">
        <div class="col-xl-6  col-lg-7 offset-lg-2 offset-xl-3">
            <div class="form-container">
                <?php echo $this->Form->create($CommonFields, ['id' => 'add_form', 'type' => 'file']) ?>
                    <div class="text-center">
                        <h4>Add Common Fields</h4>
                    </div>
                    <div class="form-wrap">
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-3" for="userid"> Field Name : </label>
                            <div class="col-sm-9">
                                <?php echo $this->Form->control('common_field_name', ['autocomplete' => 'off', 'class' => 'form-control form-style validate[required]', 'data-errormessage-value-missing'=>'Field Name is required', 'label'=>false, 'required'=>false, 'div'=>false, 'placeholder'=>'Field Name', 'maxlength'=>255]);?>
                            </div>
                        </div>
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-3" for="password">Field Type </label>
                            <div class="col-sm-9">
                                <?php
                                    echo $this->Form->input('common_field_type', [
                                        'type' => 'select',
                                        'options' => $field_type,
                                        'label' => false,
                                        'class'=>"w-100 py-2 rounded",
                                    ]);
                                ?>
                            </div>
                        </div>
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-3" for="password">Mandatory </label>
                            <div class="col-sm-9">
                                <?php
                                    echo $this->Form->input('is_mand', [
                                        'type' => 'select',
                                        'options' => $mand_arr,
                                        'label' => false,
                                        'class'=>"w-100 py-2 rounded",
                                    ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class=" form-button text-end">
                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-plus"></i>  Save</button>
                        <a href="<?php echo $this->Url->build(['controller' => 'TemplateMaster', 'action' => 'commonField']);?>">
                            <button type="button" class="btn  dele-btn"><i class="fa-solid fa-ban"></i>  Cancel</button>
                        </a>
                    </div>
                <?php echo $this->Form->end() ?> 
            </div>
        </div>
    </div>
</section>
