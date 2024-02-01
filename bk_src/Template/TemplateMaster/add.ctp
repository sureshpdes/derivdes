<?php
$this->assign('title', "Template Add");
$find_template_list = $this->Common->find_template_list();
$find_common_field = $this->Common->find_common_field();
$mand_arr = ['true' => 'Yes', 'false' => 'No'];
$templates = [];
if($find_template_list)
{
    foreach($find_template_list as $key =>$find_template)
    {
       $templates[$find_template->template_master_id] = $find_template->template_master_name;
    }
}
$find_exchange_list = $this->Common->find_exchange_list();
$exchanges = [];
if($find_exchange_list)
{
    foreach($find_exchange_list as $key =>$find_exchange)
    {
       $exchanges[$find_exchange->exchange_master_id] = $find_exchange->exchange_master_name;
    }
}
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
<section class="adduser-sec pdf-manage add-new-template-table">
    <div class="row mx-0  g-0 mt-lg-0 mt-5">
        <div class="col-xl-8  col-lg-9 offset-lg-2 offset-xl-2">
            <div class="form-container">
                 <?php echo $this->Form->create($TemplateMaster, ['id' => 'add_form', 'type' => 'file']) ?>
                 <?php echo $this->Form->unlockField('field');?>
                    <div class="text-center">
                        <h4>Create Template</h4>
                    </div>
                    <div class="form-wrap">
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-3 text-lg-end text-start px-0">Exchange:
                            </label>
                            <div class="col-sm-9 ">
                                <?php
                                    echo $this->Form->input('exchange_list', [
                                        'type' => 'select',
                                        'options' => $exchanges,
                                        'label' => false,
                                        'class'=>"w-100 py-2 rounded",
                                    ]);
                                ?>
                            </div>
                        </div>
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
                        <hr>
                        <div class="form-group row mx-0 ">
                            <label class="control-label col-sm-3 text-lg-end text-start px-0">Copy Fields From Template :
                            </label>
                            <div class="col-sm-9 ">
                                <?php
                                    echo $this->Form->input('template_list', [
                                        'type' => 'select',
                                        'options' => $templates,
                                        'label' => false,
                                        'class'=>"w-100 py-2 rounded template_val",
                                        'empty' => 'Select Template'
                                    ]);
                                ?>
                            </div>
                        </div>
                        <br>
                        <div class="all-check-box">
                            <div class="form-group row mx-0 field_ckeckbox_list">
                                
                            </div>
                        </div>
                        
                        <div class="form-group row mx-0 ">
                            <hr>
                            <label class="control-label col-sm-12 text-start mb-2"><b>Common Fields :</b>
                            </label>
                            <hr>
                            <?php 
                            if(!empty($find_common_field)):
                                foreach ($find_common_field as $admin): 
                            ?>
                            <div class="col-xl-6 col-lg-6">
                                <label> <?php echo $admin->common_field_name?> <span> <?php echo $admin->common_field_type?> </span>
                                    <input type="checkbox" name="" checked disabled class="checkbox">
                                </label>
                            </div>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                    <div class=" form-button text-end">
                        <button class="btn btn-success"><i class="fa-solid fa-download"></i> Save</button>
                        <a href="<?php echo $this->Url->build(['controller' => 'TemplateMaster', 'action' => 'index']);?>"><button type="button" class="btn dele-btn "><i class="fa-solid fa-ban"></i> Cancel</button></a>
                    </div>
                <?php echo $this->Form->end() ?> 
            </div>
        </div>
        <?php echo $this->element('footer');?>
    </div>
</section>
<script type="text/javascript">
$(function(){
    $(".hide_form").click(function () {
       $(".show-form").slideUp();
    });
    
});
</script>
<script type="text/javascript">
$(function(){
    $(".template_val").change(function(){
        findRelatedData($(".template_val").val());
    });
});
function findRelatedData(temp_id)
{
	if(temp_id=='')
	{
		$(".field_ckeckbox_list").html('');
	}
	else
	{
		$.ajax({
			type:'post',
			url:"<?=$this->Url->build(['controller'=>'TemplateMaster', 'action'=>'fetchRelatedData'])?>",
			data:{
				temp_id:temp_id,
			},
			dataType:'json',
			beforeSend: function() {
				//
			},
			headers : {
				'X-CSRF-Token': "<?php echo $this->request->getParam('_csrfToken');?>"
			},
			success:function(response){
				if(response.find_templates)
				{
					var field_list = '';
					field_list+= "<div class='col-12'>";
						field_list+= "<label> Select All </span>";
							field_list+= "<input type='checkbox' name='' class='select-all'>";
						field_list+= "</label>";
					field_list+= "</div>";
					field_list+= "<hr>";


					$.each(response.find_templates, function(key, val){
						if(val!='')
						{
							field_list+="<div class='col-xl-6 col-lg-6'>";
								field_list+="<label>"+val.field_name+"<span>"+val.field_type+"</span>";
									field_list+="<input type='checkbox' name='field[]' class='checkbox field_checkbox' value='"+val.field_master_id+"'>";
								field_list+="</label>";
							field_list+="</div>";
						}
					});
					$(".field_ckeckbox_list").html(field_list);
				}
			},
			error:function(){
				//alert('ERROR');
			},
			complete: function() {
				$(".checkbox").change(function () {
					if ($(".checkbox:checked").length === $(".checkbox").length) {
						$(".select-all").prop("checked", true);
					} else {
						$(".select-all").prop("checked", false);
					}
				});
				$(".select-all").click(function () {
					$(".checkbox").prop("checked", $(this).prop("checked"));
				});
			}
		});
	}
}
</script>
