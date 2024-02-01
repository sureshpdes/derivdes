<?php
$this->assign('title', "Common Fields");
$find_common_field = $this->Common->find_common_field();
?>
<section class="adduser-sec">
    <div class="adduser-table">
        <div class="container-fluid">
            <div class="row mb-1 align-items-center">
                <div class=" col-xl-10 col-lg-9 col-md-9 mt-5 mt-lg-0  mt-xl-0 mt-sm-0">
                    <h4>Common Fields</h4>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-3 text-md-end mt-3 mt-lg-0  mt-xl-0">
                    <a href="<?php echo $this->Url->build(['controller' => 'TemplateMaster', 'action' => 'commonFieldAdd']);?>">
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
                        if(!empty($find_common_field)):
                            foreach ($find_common_field as $admin): 
                        ?>
                        <tr>
                            <td data-title="Field Name"><?php echo $admin->common_field_name?></td>
                            <td data-title="Field Typ"><?php echo $admin->common_field_type?></td>
                            <td data-title="Mandatory"><?php echo $admin->is_mand?></td>
                            <td data-title="&nbsp;">
                                <a href="<?php echo $this->Url->build(['controller' => 'TemplateMaster', 'action' => 'commonFieldEdit', $admin->common_field_id]);?>">
                                    <button class="btn btn-primary "><i class="fa-solid fa-pencil"> </i> Edit</button></a>
                                <?php 
                                    echo $this->Form->postLink('<button class="btn dele-btn "><i class="fa-solid fa-xmark"></i> Delete</button>',
                                        ['controller' => 'TemplateMaster', 'action' => 'commonFieldDelete', $admin->common_field_id],
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
            <?php echo $this->element('footer');?>
        </div>
    </div>
</section>
