<?php
$this->assign('title', "Management");
?>
<section class="administrator_home-sec mt-5 mt-lg-0">
    <div class="container-fluid ">
        <div class="bodycontainer">
            <div class="row g-0">
                <div class="col-sm-12 col-lg-8 col-lg-offset-2 mx-auto rightpanel">
                    <div data-example-id="simple-table" class="bs-example panel">
                        <div class="panel dashboard">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 ">
                                        <div class="dashboard-list">
                                            <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'templateReset']);?>"><span class="fa-solid fa-file"></span> <br>Template Reset
                                                </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="dashboard-list">
                                            <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'exchangeReset']);?>"><span class="fa-solid fa-file"></span> <br>
                                                Exchange Reset
                                                </a>
                                        </div>
                                    </div>
                                   
                                    <div class="col-sm-6 col-md-6 ">
                                        <div class="dashboard-list">
                                            <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'backupConfig']);?>"><span class="fa-solid fa-gear"></span>
                                                <br>Backup Management</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="adduser-sec">
    <div class="adduser-table">
        <div class="container-fluid">
            <div class="row mb-1 align-items-center">
                <div class=" col-xl-10 col-lg-9 col-md-9 mt-5 mt-lg-0  mt-xl-0 mt-sm-0">
                    <h4>User Management</h4>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-3 text-md-end mt-3 mt-lg-0  mt-xl-0">
                    <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'add']);?>"><button class="btn btn-success "><i class="fa-solid fa-plus"></i> Create User</button></a>
                </div>
            </div>
            <div class="table-box " id="no-more-tables">
                <table>
                    <colgroup>
                        <col width="11%">
                        <col width="8%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="14%">
                        <col width="10%">
                        <col width="17%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Salutation</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Exchange</th>
                            <th>Role</th>
                            <th></th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php 
                        if(!$usermaster->isEmpty()):
                            foreach ($usermaster as $admin): 
                                $find_exchanges = $this->Common->find_exchange($admin->user_id);
                                $exchanges = [];
                                if($find_exchanges)
                                {
                                    foreach($find_exchanges as $key =>$find_exchange)
                                    {
                                       $exchanges[$key] = $find_exchange->exchange_master_name;
                                    }
                                }
                                if(is_array($exchanges))
                                {
                                    $exchanges_list = implode(',', $exchanges);
                                }
                                else{
                                    $exchanges_list = $exchanges;
                                }
                                
                        ?>
                        <tr>
                            <td data-title="User Name"><?php echo $admin->user_name?></td>
                            <td data-title="Salutation"><?php echo $admin->user_salutation?></td>
                            <td data-title="First Name"><?php echo $admin->user_first_name?></td>
                            <td data-title="Last Name"><?php echo $admin->user_last_name?></td>
                            <td data-title="Email"><?php echo $admin->user_email?></td>
                            <td data-title="Exchange"><?php echo $exchanges_list?></td>
                            <td data-title="Role"><?php echo $find_user_type_list[$admin->user_type_id];?></td>
                            <td data-title="">
                                <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'edit', $admin->user_id]);?>"><button class="btn btn-info "><i class="fa-solid fa-pencil"> </i> Edit</button></a>

                                <?php 
                                    echo $this->Form->postLink('<button class="btn dele-btn "><i class="fa-solid fa-xmark"></i> Delete</button>',
                                        ['controller' => 'UserMaster', 'action' => 'delete', $admin->user_id],
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
