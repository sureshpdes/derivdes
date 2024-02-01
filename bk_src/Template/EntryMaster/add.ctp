<?php
$this->assign('title', "New Entry");
$find_exchange_list = $this->Common->find_exchange_list();


$exchange_arr = [];
$template_arr = [];
$files_arr = [];
if($find_exchange_list)
{
    foreach($find_exchange_list as $key =>$find_exchange)
    {
       $exchange_arr[$find_exchange->exchange_master_id] = $find_exchange->exchange_master_name;
    }
}
if($exchange_id!='')
{
    if(!empty($find_templates))
    {
        foreach($find_templates as $key =>$template)
        {
           $template_arr[$template->template_master_id] = $template->template_master_name;
        }
    }
    $find_files = $this->Common->find_files($exchange_id);
    $find_exchange_by_id = $this->Common->find_exchange_by_id($exchange_id);
    $exchange_master_directory = $find_exchange_by_id->exchange_master_directory;
    $path = $this->Url->build('/'.$pdfmaster_folder.'/'.$exchange_master_directory);
    if(!empty($find_files)){
        foreach($find_files as $key =>$file)
        {
            if($file!='')
            {
                $dir = WWW_ROOT.$pdfmaster_folder.DS.$exchange_master_directory;
                //
                if (count(glob($dir . '/'.$file->file_name.'.pdf')) === 0) {
                    
                } else {
                    $files_arr[$file->file_name] = $file->file_name;
                   // echo $dir; 
                }
            }
        }
    }
?>
<script type="text/javascript">
    $(function(){
        $('#exchange_master_directory').val('<?php echo $path?>');
    })
</script>
<?php
}

?>
<script type="text/javascript">
<!--
    $(function(){
        $("#add_form").validationEngine();
        $("#add_form").submit(function(){
            //alert($("#entry_status").val());
            if($("#entry_status").val()!=''){

                if($("#add_form").validationEngine("validate"))
                {
                    if($("#primary-pdf-name").val()!=''){
                        //
                        $('#loader_section').show();
                        $("#add_form")[0].submit();
                    }
                    else
                    {
                        showError('<div class="message error">Please select primary PDF file.</div>');
                        return false;
                    }
                }
            }
            else
            {
                $("#add_form")[0].submit();
            }
        });
    });
    function findRelatedData(template_id)
    {
        $.ajax({
            type:'post',
            url:"<?=$this->Url->build(['controller'=>'EntryMaster', 'action'=>'fetchRelatedData'])?>",
            data:{
                template_id:template_id,
            },
            dataType:'json',
            beforeSend: function() {
                //
            },
            headers : {
                'X-CSRF-Token': "<?php echo $this->request->getParam('_csrfToken');?>"
            },
            success:function(response){
                //alert(response)
                if(response.find_fields)
                {
                    var field_html=[];
                    $.each(response.find_fields, function(key, val){
                        var validation_val = '';
                        var star_val = '&nbsp;';
                        if(val!='')
                        {
                            if(val.is_mand == 'true')
                            {
                                star_val = '*';
                                validation_val = 'validate[required]';
                            }
                            field_html+='<div class="row align-items-center">';
                                field_html+='<div class="col-md-6">';
                                    field_html+='<h6>'+val.field_name+'</h6>';
                                field_html+='</div>';
                                field_html+='<div class="col-md-6 d-flex gap-1">';
                                    field_html+='<label for="">'+star_val+'</label>';
                                    field_html+='<input class="form-control" type="hidden" name="field_id[]" value="'+val.field_master_id+'">';
                                    field_html+='<textarea class="form-control '+validation_val+'" type="text" name="field_value[]" data-errormessage-value-missing="'+val.field_name+' is required" ></textarea>';
                                field_html+='</div>';
                            field_html+='</div>';
                        }
                    });
                    $(".field_list").html(field_html);
                }
                else
                {
                    var field_html=[];
                    field_html+='<div class="col-md-12">';
                        field_html+='<p>No Fields Available for this Template</p>';
                    field_html+='</div>';
                    $(".field_list").html(field_html);
                } 
            },
            error:function(){
                //alert('ERROR');
            },
            complete: function() {
                //
            }
        });
    }
    function uploadPdf(form_data)
    {
        $.ajax({
            type:'post',
            url:"<?=$this->Url->build(['controller'=>'EntryMaster', 'action'=>'uploadPdf'])?>",
            data:form_data,
            dataType:'json',
            processData: false,
            contentType: false,
            headers : {
                'X-CSRF-Token': "<?php echo $this->request->getParam('_csrfToken');?>"
            },
            success:function(response){
               //alert(response);
                if(response.success)
                {
                    if(response.file_arr)
                    {
                        var files_list='<option value="">Select Pdf</option>';
                        $.each(response.file_arr, function(key, val){
                            if(val!='')
                            {
                                files_list+='<option value="'+key+'">'+val+'</option>';
                            }
                        });
                        $(".pdf_list").html(files_list);
                    }
                    
                }
            },
            error:function(){
                //alert('ERROR');
            },
            complete: function() {
                showSuccess('<div class="message success">PDF file uploded successfully.</div>');
            }
        });
    }
    function dublicatePdf(exchange_id, pdf)
    {
        $.ajax({
            type:'post',
            url:"<?=$this->Url->build(['controller'=>'EntryMaster', 'action'=>'dublicatePdf'])?>",
            data:{
                exchange_id:exchange_id,
                pdf:pdf,
            },

            dataType:'json',
            headers : {
                'X-CSRF-Token': "<?php echo $this->request->getParam('_csrfToken');?>"
            },
            success:function(response){
                if(response.success)
                {
                    if(response.file_arr)
                    {
                        var files_list='<option value="">Select Pdf</option>';
                        $.each(response.file_arr, function(key, val){
                            if(val!='')
                            {
                                files_list+='<option value="'+key+'">'+val+'</option>';
                            }
                        });
                        $(".pdf_list").html(files_list);
                        $(".pdf_list").val(pdf);
                    }
                    
                }
            },
            error:function(){
                //alert('ERROR');
            },
            complete: function() {
                showSuccess('<div class="message success">PDF file Dublicate successfully.</div>');
            }
        });
    }
    function deletePdf(exchange_id, pdf)
    {
        $.ajax({
            type:'post',
            url:"<?=$this->Url->build(['controller'=>'EntryMaster', 'action'=>'deletePdf'])?>",
            data:{
                exchange_id:exchange_id,
                pdf:pdf,
            },

            dataType:'json',
            headers : {
                'X-CSRF-Token': "<?php echo $this->request->getParam('_csrfToken');?>"
            },
            success:function(response){
                if(response.success)
                {
                    $("#pdf_content").attr('data','');
                    $(".pdf_list").val('');
                    $("#primary_pdf").text('');
                    if(response.file_arr)
                    {
                        var files_list='<option value="">Select Pdf</option>';
                        $.each(response.file_arr, function(key, val){
                            if(val!='')
                            {
                                files_list+='<option value="'+key+'">'+val+'</option>';
                            }
                        });
                        $(".pdf_list").html(files_list);
                    }
                    
                }
            },
            error:function(){
                //alert('ERROR');
            },
            complete: function() {
                showSuccess('<div class="message success">PDF file Deleted successfully.</div>');
            }
        });
    }
//-->
</script>
<section class="pdfentry-sec">
    <div class="container-fluid">
        <div class="row mb-1 align-items-center">
            <div class=" col-xl-10 col-lg-9 col-md-9 mt-5 mt-lg-0  mt-xl-0 mt-sm-0">
                <h4>PDF Entry</h4>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-3 text-md-end mt-3 mt-lg-0  mt-xl-0">
                <a href="<?php echo $this->Url->build(['controller' => 'EntryMaster', 'action' => 'operatorHome']);?>"><button class="btn btn-success "><i class="fa-solid fa-house"></i> Operator Home</button></a>
            </div>
        </div>
        <div class="pdfentry-body">
            <?php echo $this->Form->create($entry, ['id' => 'add_form', 'type' => 'file']) ?>
            <?php echo $this->Form->unlockField('primary_pdf_name');?>
            <?php echo $this->Form->unlockField('field_id');?>
            <?php echo $this->Form->unlockField('field_value');?>
            <?php echo $this->Form->unlockField('template_master_id');?>
            <?php echo $this->Form->unlockField('fileToUpload');?>
            <?php echo $this->Form->unlockField('upload');?>
            <?php echo $this->Form->unlockField('dublicate');?>
            <?php echo $this->Form->unlockField('delete');?>
            <?php echo $this->Form->unlockField('entry_status');?>
            <?php echo $this->Form->unlockField('exchange_master_directory');?>
            
            
            <div class="row mx-0">
                <div class="col-md-5 mb-3 mb-md-0">
                    <div class="pdfentry-sec-left">
                        <div class="pdfentry-sec-left-top">
                            <div class="row mx-0 align-items-center">
                                <div class="col-md-4 px-0">
                                    <p>
                                        Select Exchange :
                                    </p>
                                </div>
                                <div class="col-md-8 px-0">
                                    <div class="dropdown w-100">
                                         <?php
                                            echo $this->Form->input('exchange', [
                                                'type' => 'select',
                                                'options' => $exchange_arr,
                                                'label' => false,
                                                'class'=>"w-100 py-2 rounded exchange_list",
                                                'value' => $exchange_id,
                                                'empty' => 'Select Exchange',
                                            ]);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3 px-0">
                                    <p>
                                        Select Template :
                                    </p>
                                </div>
                                <div class="col-md-8 px-0 mt-3">
                                    <div class="dropdown w-100">
                                        <?php
                                            echo $this->Form->input('template', [
                                                'type' => 'select',
                                                'options' => $template_arr,
                                                'label' => false,
                                                'class'=>"w-100 py-2 rounded template_list",
                                                'value' => $template_id,
                                                'empty' => 'Select Template',
                                            ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pdfentry-sec-left-btm-sec">
                            <div class="pdfentry-sec-left-btm">
                                <div class="row align-items-center field_list">
                                    <div class="col-md-12">
                                        <p>No Fields Available for this Template</p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <input class="form-control" type="hidden" name="template_master_id" id="template_master_id">
                                <input class="form-control" type="hidden" name="entry_status" value="" id="entry_status">
                                <input class="form-control" type="hidden" name="exchange_master_directory" value="" id="exchange_master_directory">
                                <?php
                                if($this->request->getSession()->read('database_type')!=1)
                                {
                                ?>
                                <button class="btn btn-success mt-3 btn_submit" id="ongoing">Save </button>
                                <?php
                                }
                                else
                                {
                                ?>
                                <button class="btn btn-success mt-3 btn_submit" id="published">Save &amp; Publish </button>
                                <?php
                                }
                                ?>
                                <button class="btn btn-primary mt-3 btn_submit" id="submitted">Save &amp; Submit to Supervisor</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="pdfentry-sec-right-sec">
                        <div class="pdfentry-sec-left-top">
                            <div class="row mx-0 align-items-center">
                                <div class="col-md-3 px-0">
                                    <p>
                                        Select PDF From List:
                                    </p>
                                </div>
                                <div class="col-md-6 px-0">
                                    <input type="file" name="fileToUpload" class="file-upload" id="input-file-1">
                                    <input type="button" value="Upload PDF" name="upload" id="upload_pdf">
                                </div>
                                <div class="col-md-3 px-0 mt-3 mt-md-0">
                                    <div class="dropdown w-100">
                                       <?php
                                            echo $this->Form->input('primary_pdf_name', [
                                                'type' => 'select',
                                                'options' => $files_arr,
                                                'label' => false,
                                                'class'=>"w-100 py-2 rounded pdf_list",
                                                'empty' => 'Select Pdf',
                                            ]);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-9 mt-3 px-0">
                                    <p>Primary PDF Name: <span id="primary_pdf"></span></p>
                                </div>
                                <div class="col-md-3 px-0 ">
                                    <input type="button" value="Make Duplicate" name="dublicate" class="mt-3" id="dublicate">
                                    <input type="button"  name="delete" class="mt-3" id="deletepdf" value="Delete">
                                </div>
                            </div>
                        </div>
                    </div>
                    <object width="100%" height="600" type="application/pdf" data="" id="pdf_content">
                  
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
    $("#entry_status").val('');
    $(".exchange_list").change(function(){
        $(".template_list").val('');
        $("#entry_status").val('');
        $("#add_form").submit();
    });
    $(".template_list").change(function(){
        $("#entry_status").val('');
        $("#template_master_id").val($(this).val());
        findRelatedData($(this).val());
    });
    $(".btn_submit").click(function(){
        var status = $(this).attr('id');
        $("#entry_status").val(status);
        $("#add_form").submit();
    });
    $(".pdf_list").change(function(){
        $("#pdf_content").attr('data','');
        var pdf_name = $(this).val();
        var pdf_path = $('#exchange_master_directory').val();
        $("#primary_pdf").text(pdf_name);
        if(pdf_path!='')
        {
            $("#pdf_content").attr('data',pdf_path+'/'+pdf_name+'.pdf');
        }
    });
    $("#upload_pdf").click(function(){
        if($(".exchange_list").val()=='')
        {
            showError('<div class="message error">Please select exchange first.</div>');
        }
        else
        {
            var exchange_id = $(".exchange_list").val();
            //var fuData = document.getElementById('input-file-1').files[0];
            var fuData = $('#input-file-1')[0].files[0];
            var form_data = new FormData();
            form_data.append("file",fuData);
            form_data.append('exchange_id', exchange_id);
            if(fuData!='')
            {
                //console.log(form_data);
                uploadPdf(form_data);
            }
            else
            {
                showError('<div class="message error">Please select PDF file first then click upload.</div>');
            }
        }
        
    });
    $("#dublicate").click(function(){
        
        if($(".exchange_list").val()=='')
        {
            showError('<div class="message error">Please select exchange first.</div>');
        }
        else
        {
            if($(".pdf_list").val()==''){
                showError('<div class="message error">Please select Primary PDF file from PDF list.</div>');
            }
            else{
                var pdf = $(".pdf_list").val();
                var exchange_id = $('.exchange_list').val();
                dublicatePdf(exchange_id, pdf);
            }
        }
    });
    $("#deletepdf").click(function(){
        if($(".exchange_list").val()=='')
        {
            showError('<div class="message error">Please select exchange first.</div>');
        }
        else
        {
            if($(".pdf_list").val()==''){
                showError('<div class="message error">Please select Primary PDF file from PDF list.</div>');
            }
            else{
                if (confirm("Are you sure you wish to delete this primary pdf?") == true)
                {
                    var pdf = $(".pdf_list").val();
                    var exchange_id = $('.exchange_list').val();
                    deletePdf(exchange_id, pdf);
                }
                else
                {
                    return false;
                }
                
            }
        }
    });
});
</script>
<script type="text/javascript">
<!--
    $("#input-file-1").change(function(){
        if($("#input-file-1").val()!=''){
            ValidateFileUpload(1);
        }   
    });
    function ValidateFileUpload(id) {
    var fuData = document.getElementById('input-file-'+id+'');
    var FileUploadPath = fuData.value;

//To check if user upload any file

    var Extension = FileUploadPath.substring(
            FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

//The file uploaded is an image
if (Extension == "pdf") {
// To Display
        var image_size = fuData.files[0].size;
        image_size=image_size/=1024;
        var exactSize = (Math.round(image_size*100)/100);
        if(exactSize<=4096)
        {
            if (fuData.files && fuData.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    //$('#blah').attr('src', e.target.result);
                    //$('.photo_upload').html(FileUploadPath.replace(/C:\\fakepath\\/i, ''));
                }

                reader.readAsDataURL(fuData.files[0]);
            }
        }
        else
        {
            showError('<div class="message error">The file exceeds the maximum allowed size of 4MB.</div>');
            document.getElementById('input-file-'+id+'').value = '';
            //$('.photo_upload').removeAttr("style");
        }
    }
    else
    {
        showError('<div class="message error">Only PDF file types can be used.</div>');
            document.getElementById('input-file-'+id+'').value = '';
    }
    
}
//-->
</script>
