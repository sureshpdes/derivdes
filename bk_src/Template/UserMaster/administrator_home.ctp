<?php
$this->assign('title', "Administrator Home");
?>
<section class="administrator_home-sec mt-5 mt-lg-0">
    <div class="container-fluid ">
        <div class="bodycontainer">
            <div class="row g-0">
                <div class="row g-0">
                    <div class="col-sm-12 col-lg-8 col-lg-offset-2 mx-auto rightpanel">
                        <div data-example-id="simple-table" class="bs-example panel">
                            <div class="panel dashboard">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6 ">
                                            <div class="dashboard-list">
                                                <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'users']);?>"><span class="fa-solid fa-user"></span> <br>User
                                                    Management</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="dashboard-list">
                                                <a href="<?php echo $this->Url->build(['controller' => 'PdfManagement', 'action' => 'edit']);?>"><span class="fa-solid fa-file"></span> <br>Source
                                                    PDF
                                                    Management</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 ">
                                            <div class="dashboard-list">
                                                <a href="<?php echo $this->Url->build(['controller' => 'FtpManagement', 'action' => 'index']);?>"><span class="fa-solid fa-folder-open"></span>
                                                    <br>FTP
                                                    Management</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 ">
                                            <div class="dashboard-list">
                                                <a href="management.html"><span class="fa-solid fa-gear"></span>
                                                    <br>Management</a>
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
    </div>
</section>

