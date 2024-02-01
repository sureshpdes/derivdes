<?php
$this->assign('title', "Exchange Master");
?>
<section class="adduser-sec">
    <div class="adduser-table">
        <div class="container-fluid">
            <div class="row mb-1 align-items-center">
                <div class=" col-xl-10 col-lg-9 col-md-9 mt-5 mt-lg-0  mt-xl-0 mt-sm-0">
                    <h4>Exchange Management</h4>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-3 text-md-end mt-3 mt-lg-0  mt-xl-0">
                    <a href="<?php echo $this->Url->build(['controller' => 'ExchangeMaster', 'action' => 'add']);?>"><button class="btn btn-success "><i class="fa-solid fa-plus"></i> Add New</button></a>
                </div>
            </div>
            <div class="table-box " id="no-more-tables">
                <table>
                    <colgroup>
                        <col width="15%">
                        <col width="20%">
                        <col width="15%">
                        <col width="15%">
                        <col width="15%">
                        <col width="20%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Exchange Code</th>
                            <th>Exchange Name</th>
                            <th>Exchange Location</th>
                            <th>Description</th>
                            <th>FTP Directory</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(!$exchangemaster->isEmpty()):
                            foreach ($exchangemaster as $admin): 
                        ?>
                        <tr>
                            <td data-title="Exchange Code"><?php echo $admin->exchange_master_code?></td>
                            <td data-title="Exchange Name"><?php echo $admin->exchange_master_name?></td>
                            <td data-title="Exchange Location"><?php echo $admin->exchange_master_location?></td>
                            <td data-title="Description"><?php echo $admin->exchange_master_desc?></td>
                            <td data-title="FTP Directory"><?php echo $admin->exchange_master_directory?></td>
                            <td data-title="&nbsp;">
                                <a href="<?php echo $this->Url->build(['controller' => 'ExchangeMaster', 'action' => 'edit', $admin->exchange_master_id]);?>">
                                    <button class="btn btn-primary ">
                                        <i class="fa-solid fa-pencil"> </i>
                                        Edit
                                    </button>
                                </a>
                                <a href="<?php echo $this->Url->build(['controller' => 'ExchangeMaster', 'action' => 'delete', $admin->exchange_master_id]);?>"><button class="btn dele-btn "><i class="fa-solid fa-xmark"></i> Delete</button></a>
                            </td>
                        </tr>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
