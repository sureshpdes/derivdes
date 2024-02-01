<?php
$this->assign('title', "FTP Management");
?>
<section class="adduser-sec">
    <div class="adduser-table">
        <div class="container-fluid">
            <div class="row mb-1 align-items-center ">
                <div class=" col-xl-10 col-lg-9 col-md-9 mt-5 mt-lg-0  mt-xl-0 mt-sm-0">
                    <h4>FTP MANAGEMENT FOR CSV UPLOAD</h4>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-3 text-md-end mt-3 mt-lg-0  mt-xl-0">
                    <a href="<?php echo $this->Url->build(['controller' => 'FtpManagement', 'action' => 'add']);?>"><button class="btn btn-success "><i class="fa-solid fa-plus"></i> Add Host</button></a>
                </div>
            </div>
            <div class="table-box">
                <table>
                    <colgroup>
                        <col width="10%">
                        <col width="10%">
                        <col width="20%">
                        <col width="10%">
                        <col width="10%">
                        <col width="20%">
                        <col width="20%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Host IP</th>
                            <th>Port</th>
                            <th>User ID</th>
                            <th>Password</th>
                            <th >Show Pwd</th>
                            <th>Description</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(!$ftpmanagement->isEmpty()):
                            foreach ($ftpmanagement as $admin): 
                        ?>
                        <tr>
                            <td data-title="Host IP"><?php echo $admin->host_ip?></td>
                            <td data-title="Port"><?php echo $admin->port?></td>
                            <td data-title="User ID"><?php echo $admin->user_id?></td>
                            <td id="password" data-title="Password" value="<?php echo $admin->password?>">******</td>
                            <td data-title="Show Pwd">
                                <input type="checkbox" class="" id="showPassword">
                            </td>
                            <td data-title="Description"><?php echo $admin->description?></td>
                            <td data-title="&nbsp;">
                                <a href="<?php echo $this->Url->build(['controller' => 'FtpManagement', 'action' => 'edit', $admin->id]);?>"><button class="btn btn-info "><i class="fa-solid fa-pencil"> </i> Edit</button></a>
                                <?php 
                                    echo $this->Form->postLink('<button class="btn dele-btn "><i class="fa-solid fa-xmark"></i> Delete</button>',
                                        ['controller' => 'FtpManagement', 'action' => 'delete', $admin->id],
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
        </div>
    </div>
</section>
<script type='text/javascript'>
    $(document).ready(function(){
        $('#showPassword').click(function(){
            var pass = $('#password').attr('value');
            $(this).is(':checked') ? $('#password').text(pass) : $('#password').text('******');
        });
    });
</script>
