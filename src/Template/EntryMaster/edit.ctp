<?php
$this->assign('title', "Edit Entry");
$find_template = $this->Common->find_template($entry->template_master_id);
$exchange_id = $find_template->exchange_master_id;
$find_fields = $this->Common->find_field_list($entry->template_master_id);
?>
<script type="text/javascript">
<!--
    $(function(){
        $("#edit_form").validationEngine();
        $("#edit_form").submit(function(){
            if($("#entry_status").val()!=''){
                if($("#add_form").validationEngine("validate"))
                {
                    $('#loader_section').show();
                    $("#edit_form")[0].submit();
                }
            }
        });
    });
//-->
</script>
<section class="pdfentry-sec">
    <div class="container-fluid">
        <div class="row mb-1 align-items-center">
            <div class=" col-xl-8 col-lg-8 col-md-8 mt-5 mt-lg-0  mt-xl-0 mt-sm-0">
                <h4>Edit Entry</h4>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 text-md-end mt-3 mt-lg-0  mt-xl-0">
                <?php
                if($this->request->getSession()->read('user')=='operator')
                {
                ?>
                <a href="<?php echo $this->Url->build(['controller' => 'EntryMaster', 'action' => 'operatorHome']);?>"><button class="btn btn-success "><i class="fa-solid fa-house"></i> Operator Home</button></a>
                <?php
                }
                else
                {
                ?>
                <a href="<?php echo $this->Url->build(['controller' => 'EntryMaster', 'action' => 'supervisorHome']);?>"><button class="btn btn-success "><i class="fa-solid fa-house"></i> Supervisor Home</button></a>
                <a href="<?php echo $this->Url->build(['controller' => 'EntryMaster', 'action' => 'movetoSpill', $entry->entry_master_id]);?>">
                    <button class="btn dele-btn "> Delete</button>
                </a>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="pdfentry-body">
            <?php echo $this->Form->create(null, ['id' => 'edit_form', 'type' => 'file']) ?>
            <?php echo $this->Form->unlockField('field_id');?>
            <?php echo $this->Form->unlockField('field_value');?>
            <?php echo $this->Form->unlockField('entry_status');?>
            
            <div class="row mx-0 entry_body">
                <div class="col-md-5 mb-3 mb-md-0">
                    <div class="pdfentry-sec-left">
                        <div class="pdfentry-sec-left-top">
                            <div class="row mx-0 align-items-center">
                                
                                <div class="col-md-4">
                                    <p>
                                        Template :
                                    </p>
                                </div>
                                <div class="col-md-8">
                                    <p> <?php echo $find_template->template_master_name?></p>
                                </div>
                            </div>
                        </div>
                        <div class="pdfentry-sec-left-btm-sec">
                            <div class="pdfentry-sec-left-btm">
                                <?php 
                                if(!empty($find_fields)):
                                    $i=0;
                                    foreach ($find_fields as $field): 
                                        $i++;
                                        $edit_field_val = '';
                                        foreach ($enrty_txn as $txn_val)
                                        {
                                            if($field->field_master_id == $txn_val->field_id)
                                            {
                                                $edit_field_val = $txn_val->field_value;
                                            }
                                        }
                                ?>
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6><?php echo $field->field_name?></h6>
                                    </div>
                                    <div class="col-md-6 d-flex gap-1">
                                        <label for=""><?php echo $field->is_mand=='true' ? '*' : '&nbsp;';?> </label>
                                        <input class="form-control" type="hidden" name="field_id[]" value="<?php echo $field->field_master_id?>">
                                        <textarea class="form-control <?php echo $field->is_mand=='true' ? 'validate[required]' : '';?>" type="text" name="field_value[]" data-errormessage-value-missing="<?php echo $field->field_name ?> is required" value="<?php echo $edit_field_val?>"></textarea>
                                    </div>
                                </div>
                                <?php
                                    endforeach;
                                else:
                                ?>
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <p>No Fields Available for this Template</p>
                                    </div>
                                </div>
                                <?php
                                endif;
                                ?>
                                <input class="form-control" type="hidden" name="entry_status" value="<?php echo $entry->entry_status?>" id="entry_status">
                            </div>
                            <?php
                            if($entry->entry_status!='published')
                            {
                            ?>
                            <div class="text-end">
                                <?php
                                if($this->request->getSession()->read('user')=='operator')
                                {
                                ?>
                                <button class="btn btn-success mt-3" id="ongoing">Save</button>
                                <?php
                                    if($this->request->getSession()->read('database_type')!=1)
                                    {
                                ?>
                                <button class="btn btn-primary mt-3" id="submitted">Save &amp; Submit to Supervisor</button>
                                <?php
                                    }
                                }
                                else
                                {
                                ?>
                                <button class="btn btn-success mt-3" id="reverted">Revert to Operator</button>
                                <button class="btn btn-success mt-3" id="published">Save &amp; Publish</button>
                                <?php
                                }
                                ?>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="pdfentry-sec-right-sec">
                        <div class="pdfentry-sec-left-top">
                            <div class="row mx-0 align-items-center">
                                <div class="col-md-9">
                                    <p>Primary PDF Name:</p>
                                </div>
                                <div class="col-md-3">
                                    <p><?php echo $entry->primary_pdf_name?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <object width="100%" height="100%" type="application/pdf" data="<?php echo $this->Url->build('/'.$pdfdump_folder.'/'.$entry->primary_pdf_name.'.pdf')?>" id="pdf_content">
                  
                    </object>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
    <?php echo $this->element('footer');?>
</section>
<?php echo $this->element('loader-section');?>
<script type="text/javascript">
$(function(){
    $("#ongoing").click(function(){
        $("#entry_status").val('ongoing');
        $("#edit_form").submit();
    });
    $("#submitted").click(function(){
        $("#entry_status").val('submitted');
        $("#edit_form").submit();
    });
    $("#published").click(function(){
        $("#entry_status").val('published');
        $("#edit_form").submit();
    });
    $("#reverted").click(function(){
        $("#entry_status").val('reverted');
        $("#edit_form").submit();
    });
});
</script>
