<?php
$this->assign('title', "Operator Home");
if($this->request->getSession()->check('database_type'))
{
   $database_type = $this->request->getSession()->read('database_type');
}
else{
    return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
}
$currentuser = $this->request->getSession()->read('user_data');

$search_data = [];
if(!empty($get_data))
{
    $search_data= $get_data;
}
elseif($this->request->getSession()->check('get_data')){
    $search_data = $this->request->getSession()->read('get_data');
}
else{
    $search_data['radio'] = 'ongoing';
}
//print_r($search_data);
?>
<script type="text/javascript">
<!--
    $(function(){
        <?php
        if(isset($search_data['from_datepicker']) && $search_data['from_datepicker']!='')
        {
        ?>
        $('.from_datepicker').val('<?php echo $search_data['from_datepicker'];?>');
        <?php
        }
        if(isset($search_data['to_datepicker']) && $search_data['to_datepicker']!='')
        {
        ?>
        $('.to_datepicker').val('<?php echo $search_data['to_datepicker'];?>');
        <?php
        }
        if(isset($search_data['typeSearch']) && $search_data['typeSearch']!='')
        {
        ?>
        $('#typeSearch').val('<?php echo $search_data['typeSearch'];?>');
        <?php
        }
        ?>
    });
//-->
</script>
<section class="adduser-sec supervisor_home">
    <div class="adduser-table">
        <div class="container-fluid">
            <div class="row mb-1 align-items-center">
                <div class=" col-xl-4 mt-5 mt-lg-0  mt-xl-0 mt-sm-0">
                    <div class="templatereset-heading ">
                        <h4>Operator Dashboard ( <span><?php echo $currentuser['user_first_name']?></span> )</h4>
                    </div>
                </div>
                <div class="col-xl-8  text-xl-end mt-3 mt-lg-0  mt-xl-0">
                    <div class="supervisor-home-btn">
                        <a href="<?php echo $this->Url->build(['controller' => 'EntryMaster', 'action' => 'add']);?>" class="btn btn-success mb-1"><i class="fa-solid fa-plus"></i> Add new
                            entry</a>

                    </div>
                </div>
            </div>
            <div class="table-box">
                <div class="table-top">
                    <?php echo $this->Form->create('entrySearch', ["name" => "entrySearch", "class"=>"", "method" => "POST",  "id" => "entrySearch", 'action' => 'operatorHome']);?>
                    <?php echo $this->Form->unlockField('radio');?>
                    <?php echo $this->Form->unlockField('from_datepicker');?>
                    <?php echo $this->Form->unlockField('to_datepicker');?>
                    <?php echo $this->Form->unlockField('typeSearch');?>
                        <div class="row mx-0 align-items-center">
                            <div class="col-lg-12  pe-0">
                                <div class=" d-flex align-items-center gap-3">
                                    <label>Ongoing Documents
                                        <input type="radio" name="radio" class="radiobox" id="" value="ongoing" <?php echo (isset($search_data['radio']) && $search_data['radio']=='ongoing') ? 'checked' : '';?> >
                                    </label>
                                    <label>Reverted Documents
                                        <input type="radio" name="radio" class="radiobox me-3" id=" toggleCheckbox" value="reverted" <?php echo (isset($search_data['radio']) && $search_data['radio']=='reverted') ? 'checked' : '';?> >
                                    </label>
                                    <label>Published
                                        <input type="radio" name="radio" class="radiobox me-3" id=" toggleCheckbox" value="published" <?php echo (isset($search_data['radio']) && $search_data['radio']=='published') ? 'checked' : '';?> >
                                    </label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="search-box">
                                            <div class="input-group ">
                                                <!--<div class="input-group-text typeSearch" ><i class="fa-solid fa-magnifying-glass"></i>
                                                </div>-->
                                                <input type="text" class="form-control" name="typeSearch" id="typeSearch" placeholder="Type Search">
                                            </div>
                                        </div>
                                        <div class="published-datetime">From 
                                            <input type="text" name="from_datepicker" id="" class="from_datepicker"> to 
                                            <input type="text" name="to_datepicker" id="" class="to_datepicker">
                                            <button  type="button" class="btn btn-success btn-sm date_search">Search</button>
                                        </div>
                                        <a href="<?php echo $this->Url->build(['controller' => 'EntryMaster', 'action' => 'reset']);?>" class="btn btn-info btn-reset"><i class="fa-solid fa-refresh"> </i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php echo $this->Form->end(); ?>
                </div>

                <table>
                    <colgroup>
                        <col width="30%">
                        <col width="30%">
                        <col width="15%">
                        <col width="10%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Template Name</th>
                            <th>PDF Name</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(!$find_entry_list->isEmpty()):
                            foreach ($find_entry_list as $template): 
                                $find_template = $this->Common->find_template($template->template_master_id);
                        ?>
                        <tr>
                            <td data-title="Template Name"><?php echo $find_template->template_master_name?></td>
                            <td data-title="PDF Name"><?php echo $template->primary_pdf_name?></td>
                            <td data-title="Date"><?php echo $template->mod_time?></td>
                            <td data-title="Status"><?php echo $template->entry_status?>
                            
                            </td>
                            <td data-title="Date">
                            <?php
                            if($template->entry_status=='ongoing')
                            { 
                            ?>
                                <a href="<?php echo $this->Url->build(['controller' => 'EntryMaster', 'action' => 'delete', $template->entry_master_id]);?>">
                                    <button class="btn dele-btn "> Delete</button>
                                </a>
                            <?php
                            }
                            ?>
                                <a href="<?php echo $this->Url->build(['controller' => 'EntryMaster', 'action' => 'edit', $template->entry_master_id]);?>">
                                    <button class="btn btn-primary ">
                                        <i class="fa-solid fa-pencil"> </i>
                                        Edit
                                    </button>
                                </a>
                            </td>
                        </tr>
                        <?php
                            endforeach;
                        else:
                        ?>
                        <tr>
                            <td colspan="4"><p class="text-center">No Record Found</p></td>
                        </tr>
                        <?php
                        endif;
                        ?>

                    </tbody>
                </table>

            </div>
            <?php
            if($this->Paginator->counter('{{pages}}') > 1):
            ?>
            <div class="paginator">
                <ul class="pagination">
                    <?= $this->Paginator->first('<< ' . __('First')) ?>
                    <?= $this->Paginator->prev('< ' . __('Previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('Next') . ' >') ?>
                    <?= $this->Paginator->last(__('Last') . ' >>') ?>
                </ul>
                <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
            </div>
            <?php
            endif;
            ?>
            <?php echo $this->element('footer');?>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear(); //January is 0!
        var fromdate = dd+'-'+mm+'-'+(yyyy-1);
        var todate = dd+'-'+mm+'-'+yyyy;
        
        $('.from_datepicker').datepicker({
            format: 'dd-mm-yyyy',
            container: '.from_datepicker',
            setDate: ''
        });
        $('.to_datepicker').datepicker({
            format: 'dd-mm-yyyy',
            container: '.to_datepicker',
            setDate: ''
        });
        if($('.from_datepicker').val()=='')
        {
            //$('.from_datepicker').val(fromdate);
        }
        if($('.to_datepicker').val()=='')
        {
            //$('.to_datepicker').val(todate);
        }
    });
    $(".date_search").click(function(){
        //$('#typeSearch').val('');
        $('#entrySearch').submit();
    });
    $(".typeSearch").click(function(){
        $('#entrySearch').submit();
    });
    $(".download_btn").click(function(){
        $('#downloadData').submit();
    });
    $(".radiobox").change(function(){
        //$('#typeSearch').val('');
        //$('#entrySearch').submit();
    });
    
</script>

