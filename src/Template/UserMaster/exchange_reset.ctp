<?php
$this->assign('title', "Template Reset");
$exchanges_arr = [];
if($find_exchange)
{
    foreach($find_exchange as $key =>$exchange)
    {
       $exchanges_arr[$exchange->exchange_master_id] = $exchange->exchange_master_name;
    }
}
?>
<script type="text/javascript">
<!--
    $(function(){
        $("#entry_form").validationEngine();
        $("#entry_form").submit(function(){
            if($("#entry_form").validationEngine("validate"))
            {
                //$(".loading_img_div").show();
                if($(".entry_arr").val())
                {
                    //
                }
                else
                {
                    showError('<div class="message error">No data found.</div>');
                    return false;
                }
            }
        });
    });
//-->
</script>
<section class="adduser-sec templatereset mt-5 mt-lg-0">
    <div class="adduser-table">
        <div class="container-fluid">
            <div class="templatereset-heading ">
                <h4>Template Reset</h4>
            </div>
            <?php echo $this->Form->create(null, ['id' => 'entry_form', 'type' => 'file', 'action' => 'templateReset']) ?>
            <?php
                $this->Form->templates([
                    'inputContainer' => '{{content}}'
                ]);
            ?>
            <?php echo $this->Form->unlockField('entry');?>
            <?php echo $this->Form->unlockField('reset_type');?>
            <?php echo $this->Form->unlockField('action_name');?>
            
                <div class="row mx-0">
                    <div class=" col-md-5 col-lg-4">
                        <div class="templatereset-left">
                            <div class="templatereset-dropdown">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>Select Exchange:</label>
                                    </div>
                                    <?php
                                        echo $this->Form->input('exchange_list', [
                                            'type' => 'select',
                                            'options' => $exchanges_arr,
                                            'label' => false,
                                            'class'=>"selectClass col-md-7 exchange_list",
                                            'empty' => 'Select Exchange'
                                        ]);
                                    ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>Select Template :</label>
                                    </div>
                                    <?php
                                        echo $this->Form->input('template_list', [
                                            'type' => 'select',
                                            'label' => false,
                                            'class'=>"selectClass col-md-7 template_list",
                                            'empty' => 'Select Template'
                                        ]);
                                    ?>
                                </div>
                                <input type="hidden" name="reset_type" value="" id="reset_type">
                                <input type="hidden" name="action_name" value="exchangeReset" id="action_name">
                                <div class="text-end">
                                    <button class="btn reset-btn reset_all" type="button">Reset</button>
                                    <button class="btn reset-btn reset_sup" type="button">Reset to Supervisor Level</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-7 col-lg-8">
                        <div class="templatereset-right">
                            <h6>Engaged Document List: </h6>
                            <div class="table-box mt-2">
                                <table>
                                    <colgroup>
                                        <col width="20%">
                                        <col width="20%">
                                        <col width="20%">
                                        <col width="20%">
                                        <col width="20%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>Document</th>
                                            <th>Template</th>
                                            <th>Date</th>
                                            <th>User</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="entry_list">
                                        <tr><td colspan='4'>No record found</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php echo $this->Form->end() ?>
        </div>
    </div>
    <?php echo $this->element('footer');?>
</section>
<script type="text/javascript">
$(function(){
    $(".exchange_list").change(function(){
        $(".template_list").val('');
        var entry_type = 'all';
        findRelatedTemplate($(".exchange_list").val(), $(".template_list").val(), entry_type);

    });
    $(".template_list").change(function(){
        var entry_type = 'all';
        findRelatedTemplate($(".exchange_list").val(), $(".template_list").val(), entry_type);
    });
    $(".reset_all").click(function(){
        if($(".entry_arr").val())
        {
            if (!confirm('Resetting will result in removal of all entry data associated with this template (including publihed docs) and the docs will be freashly available at Operator Level')){
                return false;
            } 
            else
            {
                $("#reset_type").val('Reset all');
                $("#entry_form").submit();
            }
        }
        else
        {
            showError('<div class="message error">No data found.</div>');
            return false;
        }
    });
    $(".reset_sup").click(function(){
        if($(".entry_arr").val())
        {
            if (!confirm('Resetting will result in published data for the selected template being moved to supervisor level')){
                return false;
            } 
            else
            {
                $("#reset_type").val('Reset sup');
                $("#entry_form").submit();
            }
        }
        else
        {
            showError('<div class="message error">No data found.</div>');
            return false;
        }
    });
});
function findRelatedTemplate(exc_id, temp_id, entry_type)
{
    if(exc_id=='')
    {
        //
    }
    else
    {
        $.ajax({
            type:'post',
            url:"<?=$this->Url->build(['controller'=>'UserMaster', 'action'=>'findRelatedTemplate'])?>",
            data:{
                exc_id:exc_id,
                temp_id:temp_id,
                entry_type:entry_type,
            },
            dataType:'json',
            beforeSend: function() {
                //
            },
            headers : {
                'X-CSRF-Token': "<?php echo $this->request->getParam('_csrfToken');?>"
            },
            success:function(response){
                var entry_html = '';
                if(response.find_entry_list)
                {
                    $.each(response.find_entry_list, function(key, val){
                        entry_html+="<tr>";
                            entry_html+="<td>"+val.value.primary_pdf_name+"</td>";
                            entry_html+="<td>"+val.template_name+"</td>";
                            entry_html+="<td>"+val.value.mod_time+"</td>";
                            entry_html+="<td>"+val.user+"</td>";
                            entry_html+="<td>"+val.value.entry_status+"<input type='checkbox' name='entry[]' class='entry_arr d-none' checked value='"+val.value.entry_master_id +"'></td>";
                        entry_html+="</tr>";
                    });
                    $(".entry_list").html(entry_html);
                    
                }
                else if(response.find_template)
                {
                    var template_html='<option value="">Select Template</option>';
                    $.each(response.find_template, function(key, val){
                        if(val!='')
                        {
                            template_html+='<option value="'+val.template_master_id+'">'+val.template_master_name+'</option>';
                        }
                    });
                    $(".template_list").html(template_html);
                }
                else
                {
                    entry_html+="<tr><td colspan='4'>No record found</td></tr>";
                    $(".entry_list").html(entry_html);
                }
            },
            error:function(){
                //alert('ERROR');
            },
            complete: function() {
                
            }
        });
    }
}
</script>

