<?php
$this->assign('title', "Backup Management");
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
        <div class="col-xl-8  col-lg-7 offset-lg-2 offset-xl-2">
            <div class="form-container">
                <div class="text-center">
                    <h4>Backup Management</h4>
                </div>
                <?php echo $this->Form->create($backupconfig, ['id' => 'edit_form', 'type' => 'file']) ?>
                <div class="form-wrap">
                    <div class="form-group row mx-0 align-items-start">
                        <label class="control-label col-sm-3 px-0" for="userid">
                            Email Addresses: 
                            <br>
                            (Please use semicolon for multiple email addresses)
                        </label>
                        <div class="col-sm-9">
                            <?php
                                echo $this->Form->input('email_to', [
                                    'type' => 'textarea',
                                    'label' => false,
                                    'class'=>"form-control",
                                    'placeholder' => 'Email Address separated with semicolone',
                                    'rows' => 10,
                                    'cols' => 70,
                                    'id' => 'email_to',

                                ]);
                            ?>
                        </div>
                    </div>
                    <div class="form-group row mx-0 ">
                        <label class="control-label col-sm-3 px-0" for="password">Set Frequency To get CSV:</label>
                        <div class="col-sm-9">
                            <?php
                                echo $this->Form->input('interval_bkup', [
                                    'type' => 'select',
                                    'options' => $backup_interval,
                                    'label' => false,
                                    'class'=>"selectClass mw-100 w-100 interval_list",
                                    'empty' => 'Select Interval'
                                ]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class=" form-button text-end">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
                <?php echo $this->Form->end() ?>
            </div>
        </div>
        <?php echo $this->element('footer');?>
    </div>
</section>
