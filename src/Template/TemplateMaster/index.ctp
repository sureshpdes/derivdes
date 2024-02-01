<?php
$this->assign('title', "Template Master");
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
?>
<section class="adduser-sec addtemplate-sec">
    <div class="adduser-table">
        <div class="container-fluid">
            <div class="row mb-1 align-items-center">
                <div class=" col-xl-4 mt-5 mt-lg-0  mt-xl-0 mt-sm-0">
                    <div class="templatereset-heading ">
                        <h4>TEMPLATE MANAGEMENT</h4>
                    </div>
                </div>
                <div class="col-xl-8  text-xl-end mt-3 mt-lg-0  mt-xl-0">
                    <?php echo $this->Form->create(null, ['id' => 'search_form', 'type' => 'file', 'action' => 'index']) ?>
                        <div class="supervisor-home-btn">
                            
                            <div class="exchange_dropdown d-inline mb-5">
                                <label for="">Exchange :</label>
                                <div class="exchangebox">
                                    <?php
                                        echo $this->Form->input('exchange', [
                                            'type' => 'select',
                                            'options' => $exchanges,
                                            'label' => false,
                                            'class'=>"w-100 py-2 rounded exchange_list",
                                            'value' => $exchange_id,
                                            'empty' => 'Select Exchange',
                                        ]);
                                    ?>
                                </div>
                            </div>
                            
                            <a href="<?php echo $this->Url->build(['controller' => 'TemplateMaster', 'action' => 'commonField']);?>" class="btn btn-success my-1"><i class="fa-solid fa-plus"></i> Define Common
                                Fields</a>
                            <a href="<?php echo $this->Url->build(['controller' => 'TemplateMaster', 'action' => 'add']);?>" class="btn btn-success my-1"><i class="fa-solid fa-plus"></i> Add New
                                Template </a>
                        </div>
                    <?php echo $this->Form->end() ?>
                </div>
            </div>
            <div class="table-box">
                <table>
                    <colgroup>
                        <col width="20%">
                        <col width="30%">
                        <col width="50%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Template Name</th>
                            <th>Description</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(!$TemplateMaster->isEmpty()):
                            $i=0;
                            foreach ($TemplateMaster as $admin): 
                                $i++;
                                $find_field_list = $this->Common->find_field_list($admin->template_master_id);
                        ?>
                        <tr>
                            <td data-title="Template Name"><?php echo $admin->template_master_name?></td>
                            <td data-title="Description"><?php echo $admin->template_master_desc?></td>
                            <td data-title="">
                                <button type="button" class="btn btn-light text-dark border"
                                    data-bs-toggle="modal" data-bs-target="#show-data-<?php echo $i?>"><i
                                        class="fa-solid fa-list"></i>
                                    View Fields
                                </button>
                                <a href="<?php echo $this->Url->build(['controller' => 'TemplateMaster', 'action' => 'edit', $admin->template_master_id]);?>">
                                    <button class="btn btn-primary "><i class="fa-solid fa-pencil"> </i>
                                    Edit</button>
                                </a>
                                <?php 
                                    echo $this->Form->postLink('<button class="btn dele-btn "><i class="fa-solid fa-xmark"></i> Delete</button>',
                                        ['controller' => 'TemplateMaster', 'action' => 'delete', $admin->template_master_id],
                                        ['confirm' => 'Are you sure you wish to delete this record? All the related data will be also get deleted.',
                                        'escape' => false]
                                    );
                                ?>
                                <div class="modal fade  text-wrap text-start" id="show-data-<?php echo $i?>" tabindex="-1"
                                    aria-labelledby="exampleModalLabel">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">View Fields</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mx-0">
                                                    <div class="col-6 text-end">
                                                        <h6>Selected Template :</h6>
                                                    </div>
                                                    <div class="col-6 ">
                                                        <p><?php echo $admin->template_master_name?> </p>
                                                    </div>
                                                    <div class="col-12 ">
                                                        &nbsp;
                                                    </div>
                                                    <?php
                                                    if(!empty($find_field_list))
                                                    {
                                                        foreach ($find_field_list as $list) 
                                                        {
                                                    ?>
                                                    <div class="col-9 text-end">
                                                        <h6><?php echo $list->field_name?></h6>
                                                    </div>
                                                    <div class="col-3 ">
                                                        <p><?php echo $list->field_type?></p>
                                                    </div>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn dele-btn"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php
                            endforeach;
                        else:
                        ?>
                        <tr>
                            <td colspan="3"><p class="text-center">No Template Found</p></td>
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
$(function(){
    $(".exchange_list").change(function(){
        $("#search_form").submit();
    });
});
</script>
