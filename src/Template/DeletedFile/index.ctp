<?php
$this->assign('title', "Deteled PDF");
?>
<script type="text/javascript">
<!--
    $(function(){
        $(".restore_btn").click(function(){
            var selected_pdf = $(".field_checkbox:checked").map(function(){
                return this.value;
            }).toArray();
            if(selected_pdf.length == 0){
                showError('<div class="message error">Please Select Pdf first.</div>');
            }
            else{
                $("#restore_form").submit();
            }
        });
    });
//-->
</script>
<section class="adduser-sec supervisor_home">
    <div class="adduser-table">
        <div class="container-fluid">
            <div class="row mb-1 align-items-center">
                <div class=" col-xl-10 col-lg-9 col-md-9 mt-5 mt-lg-0  mt-xl-0 mt-sm-0">
                    <h4>Deteled PDF</h4>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-3 text-md-end mt-3 mt-lg-0  mt-xl-0">
                    <button class="btn btn-success restore_btn"><i class="fa-solid fa-rotate-right"></i> Restore</button>
                </div>
            </div>
            <?php echo $this->Form->create(null, ['id' => 'restore_form', 'type' => 'file', 'action' => 'restore']) ?>
            <?php echo $this->Form->unlockField('pdffile');?>
                <div class="table-box " id="no-more-tables">
                    <table>
                        <colgroup>
                            <col width="25%">
                            <col width="20%">
                            <col width="25%">
                            <col width="20%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Exchange Directory</th>
                                <th>Deleted By</th>
                                <th>Date</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(!$deletedfile->isEmpty()):
                                foreach ($deletedfile as $admin): 
                                $find_user = $this->Common->find_user_name($admin->user_id);
                            ?>
                            <tr>
                                <td data-title="File Name"><a href="#"><?php echo $admin->file_name?></a></td>
                                <td data-title="Exchange Directory"><?php echo $admin->file_directory?></td>
                                <td data-title="Deleted By"><?php echo $find_user->user_first_name?> <?php echo $find_user->user_last_name?> </td>
                                <td data-title="Date"><?php echo $admin->delete_time?></td>
                                <td data-title="&nbsp;">
                                    <input type="checkbox" name="pdffile[]" class='checkbox field_checkbox' value="<?php echo $admin->deleted_file_id ?>">
                                </td>
                            </tr>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php echo $this->Form->end() ?>
            <?php echo $this->element('footer');?>
        </div>
    </div>
</section>
