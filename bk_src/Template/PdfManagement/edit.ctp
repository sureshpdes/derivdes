<?php
$this->assign('title', "PDF management");
$find_exchange_list = $this->Common->find_exchange_list();
$exchange_list = [];
foreach($find_exchange_list as $exchange) {
    $exchange_list[$exchange->exchange_master_id] = $exchange->exchange_master_name;
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
<section class="adduser-sec pdf-manage">
    <div class="row mx-0  g-0 mt-lg-0 mt-5">
        <div class="col-xl-6  col-lg-7 offset-lg-2 offset-xl-3">
            <div class="form-container">
                <div class="pdf_management">
                    <div class="text-center">
                        <h4>PDF Management</h4>
                    </div>
                    <?php echo $this->Form->create($pdfmanagement, ['id' => 'edit_form', 'type' => 'file']) ?>
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
                        </div>
                        <div class=" form-button text-end">
                            <button type="button" class="btn btn-info upload_pdf">Upload PDF</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    <?php echo $this->Form->end() ?> 
                </div>
                <div class="upload_div d-none">
                    <div class="text-center">
                        <h4>Upload PDF</h4>
                    </div>
                    <?php echo $this->Form->create($pdfupload, ['id' => 'add_form', 'type' => 'file']) ?>
                    <?php echo $this->Form->control('form_name', ['type' => 'hidden', 'value' => 'pdf_form']);?>
                        <div class="form-wrap">
                            <div class="form-group row mx-0 ">
                                <label class="control-label col-sm-3" for="userid">Exchanges:</label>
                                <div class="col-sm-9">
                                    <?php
                                        echo $this->Form->input('exchange_id', [
                                            'type' => 'select',
                                            'options' => $exchange_list,
                                            'label' => false,
                                            'class'=>"selectClass mw-100 w-100",
                                        ]);
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row mx-0 ">
                                <label class="control-label col-sm-3">PDF File:</label>
                                <div class="col-sm-9">
                                    <?php echo $this->Form->control('input-file-1', ['type'=>'file', 'label'=>false, 'required'=>false, 'div'=>false, 'class'=>'form-control form-style']);?>
                                </div>
                            </div>
                        </div>
                        <div class=" form-button text-end">
                            <button type="button" class="btn btn-danger cancel_upload">Cancel</button>
                            <button type="submit" class="btn btn-success">Save</button>

                        </div>
                    <?php echo $this->Form->end() ?> 
                </div>
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
        $('.upload_pdf').click(function(){
            $('.pdf_management').addClass('d-none');
            $('.upload_div').removeClass('d-none');
        });
        $('.cancel_upload').click(function(){
            $('.pdf_management').removeClass('d-none');
            $('.upload_div').addClass('d-none');
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
