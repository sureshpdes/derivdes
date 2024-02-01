<?php
$this->assign('title', "Template Fields");
?>
<section class="adduser-sec">
    <div class="adduser-table">
        <div class="container-fluid">
            <div class="row mb-1 align-items-center">
                <div class=" col-xl-10 col-lg-9 col-md-9 mt-5 mt-lg-0  mt-xl-0 mt-sm-0">
                    <h4>Template Fields</h4>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-3 text-md-end mt-3 mt-lg-0  mt-xl-0">
                    <a href="<?php echo $this->Url->build(['controller' => 'TemplateMaster', 'action' => 'FieldAdd', $temp_id]);?>">
                        <button class="btn btn-success "><i class="fa-solid fa-plus"></i>Add New Field</button>
                    </a>
                </div>
            </div>
            <div class="table-box " id="no-more-tables">
                <table>
                    <colgroup>
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                        <col width="40%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Field Name</th>
                            <th>Field Type</th>
                            <th>Mandatory</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(!empty($FieldMaster)):
                            foreach ($FieldMaster as $admin): 
                        ?>
                        <tr>
                            <td data-title="Field Name"><?php echo $admin->field_name?></td>
                            <td data-title="Field Typ"><?php echo $admin->field_type?></td>
                            <td data-title="Mandatory"><?php echo $admin->is_mand?></td>
                            <td data-title="&nbsp;">
                                <a href="<?php echo $this->Url->build(['controller' => 'TemplateMaster', 'action' => 'FieldEdit', $admin->field_master_id, $admin->template_master_id]);?>">
                                    <button class="btn btn-primary " type="button">
                                        <i class="fa-solid fa-pencil"> </i>
                                        Edit Fields
                                    </button>
                                </a>
                                <?php 
                                    echo $this->Form->postLink('<button class="btn dele-btn "><i class="fa-solid fa-xmark"></i> Delete</button>',
                                        ['controller' => 'TemplateMaster', 'action' => 'FieldDelete', $admin->field_master_id, $admin->template_master_id],
                                        ['confirm' => 'Are you sure you wish to delete this record? All the related data will be also get deleted.',
                                        'escape' => false]
                                    );
                                ?>
                            </td>
                        </tr>
                        <?php
                            endforeach;
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
