<?php
//print_r($find_entry_list);exit();
$this->assign('title', "Supervisor Home");
if($this->request->getSession()->check('database_type'))
{
   $database_type = $this->request->getSession()->read('database_type');
}
else{
    return $this->redirect(array("controller" => "UserMaster", "action" => "dashboard"));
}
$currentuser = $this->request->getSession()->read('user_data');
$find_exchange_list = $this->Common->find_exchange_list();
$exchanges = [];
if($find_exchange_list)
{
    foreach($find_exchange_list as $key =>$find_exchange)
    {
       $exchanges[$find_exchange->exchange_master_id] = $find_exchange->exchange_master_name;
    }
}
$search_data = [];
if(!empty($get_data))
{
    $search_data= $get_data;
}
elseif($this->request->getSession()->check('get_data')){
    $search_data = $this->request->getSession()->read('get_data');
}
else{
    $search_data['radio'] = 'submitted';
}
//print_r($find_entry_list);
?>

<script type="text/javascript">
<!--
    $(function(){
        $("#downloadData").submit(function(){
            $('#loader_section').show();
            $("#downloadData")[0].submit();
        });
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
                        <h4>Supervisor Dashboard (<span><?php echo $currentuser['user_first_name']?></span>)</h4>
                    </div>
                </div>
                <div class="col-xl-8  text-xl-end mt-3 mt-lg-0  mt-xl-0">
                    <div class="supervisor-home-btn">
                        <a href="<?php echo $this->Url->build(['controller' => 'ExchangeMaster', 'action' => 'index']);?>" class="btn btn-primary mb-1">Exchange Management</a>
                        <a href="<?php echo $this->Url->build(['controller' => 'TemplateMaster', 'action' => 'index']);?>" class="btn btn-primary mb-1">Template Management</a>
                        <a href="<?php echo $this->Url->build(['controller' => 'DeletedFile', 'action' => 'index']);?>" class="btn btn-primary mb-1">View Deleted PDF</a>
                        <button type="button" class="btn btn-primary mb-1" data-bs-toggle="modal"
                            data-bs-target="#download-data">
                            Download Data
                        </button>
                        <div class="modal fade  text-wrap text-start" id="download-data"
                            aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog">
                                <?php echo $this->Form->create('downloadData', ["name" => "downloadData", "class"=>"", "method" => "POST",  "id" => "downloadData", 'action' => 'downloadData']);?>
                                <?php
                                $this->Form->templates([
                                    'inputContainer' => '{{content}}'
                                ]);
                                ?>
                                <?php echo $this->Form->unlockField('from_datepicker');?>
                                <?php echo $this->Form->unlockField('to_datepicker');?>
                                
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <p class="modal-title" id="exampleModalLabel">Download Data</p>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                                echo $this->Form->input('exchange', [
                                                    'type' => 'select',
                                                    'options' => $exchanges,
                                                    'label' => false,
                                                    'class'=>"w-100 py-2 rounded exchange_list",
                                                    'empty' => 'Select Exchange',
                                                ]);
                                            ?>
                                            <div class="published-datetime2">From <input type="text" id="" class="hasDatepicker from_datepicker" name="from_datepicker">
                                                to <input type="text" id="" class="hasDatepicker  to_datepicker" name="to_datepicker">
                                            </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary download_btn">Download</button>
                                            <button type="button" class="btn dele-btn"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                <?php echo $this->Form->end() ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-box">
                <div class="table-top">
                    <?php echo $this->Form->create('entrySearch', ["name" => "entrySearch", "class"=>"", "method" => "POST",  "id" => "entrySearch", 'action' => 'supervisorHome']);?>
                    <?php echo $this->Form->unlockField('radio');?>
                    <?php echo $this->Form->unlockField('from_datepicker');?>
                    <?php echo $this->Form->unlockField('to_datepicker');?>
                    <?php echo $this->Form->unlockField('typeSearch');?>
                    <div class="row mx-0 align-items-center">
                        <div class="col-lg-12  pe-0">
                            <div class=" d-flex align-items-center gap-3">
                                <label>Pending for Review
                                    <input type="radio" name="radio" class="radiobox radio_pending" id=""  value="submitted" <?php echo (isset($search_data['radio']) && $search_data['radio']=='submitted') ? 'checked' : '';?> >
                                </label>
                                <label>Published
                                    <input type="radio" name="radio" class="radiobox me-3 radio_published" id=" toggleCheckbox" value="published" <?php echo (isset($search_data['radio']) && $search_data['radio']=='published') ? 'checked' : '';?>>
                                </label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="search-box">
                                        <div class="input-group ">
                                            <!--<div class="input-group-text typeSearch" ><i class="fa-solid fa-magnifying-glass"></i>
                                            </div>-->
                                            <input type="text" class="form-control" name="typeSearch" id="typeSearch" placeholder="Type Search">
                                        </div>
                                    </div>
                                    <div class="published-datetime">From <input type="text" name="from_datepicker" id="" class="from_datepicker">
                                        to <input type="text" name="to_datepicker" id="" class="to_datepicker">
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
                        <col width="25%">
                        <col width="30%">
                        <col width="15%">
                        <col width="13%">
                        <col width="17%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th>Template Name</th>
                            <th>User</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!$find_entry_list->isEmpty()):
                        foreach ($find_entry_list as $template): 
                            $find_template = $this->Common->find_template($template->template_master_id);
                            $find_user = $this->Common->find_user_name($template->user_id);
                    ?>
                        <tr>
                            <td data-title="Document Name"><?php echo $template->primary_pdf_name?></td>
                            <td data-title="Template Name"><?php echo $find_template->template_master_name?></td>
                            <td data-title="User"><?php echo $find_user->user_first_name?> <?php echo $find_user->user_last_name?></td>
                            <td data-title="Date"><?php echo $template->mod_time?></td>
                            <td>
                                <?php
                                if(file_exists(WWW_ROOT.$pdfdump_folder.DS.$template->primary_pdf_name.'.pdf'))
                                {
                                ?>
                                <a href="<?php echo $this->Url->build('/'.$pdfdump_folder.DS.$template->primary_pdf_name.'.pdf');?>" target="_blank" class="btn btn-info "><i class="fa-solid fa-eye"> </i> View</a>
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
        /*setTimeout(function(){
            if($('input[name=radio]:checked').val() == "published")
            {
                $('.published-datetime').removeClass('d-none');
            }
            else
            {
                $('.published-datetime').addClass('d-none');
            }
        }, 500);*/
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
    
    $("input[name=radio]").click(function(){
        if($('input[name=radio]:checked').val() == "published"){
            //alert($('input[name=radio]:checked').val());
            //$('.published-datetime').removeClass('d-none');
            
        }
        else{
            //alert($('input[name=radio]:checked').val());
            //$('.published-datetime').addClass('d-none');
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

