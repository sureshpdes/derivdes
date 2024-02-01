<?php
$this->assign('title', "Dashboard");
$action = 'dashboard';
if($this->request->getSession()->check('user'))
{
    if($this->request->getSession()->read('user')=='supervisor')
    {
        $action = 'supervisorHome';
    }
    elseif($this->request->getSession()->read('user')=='operator')
    {
        $action = 'operatorHome';
    }
    $currentuser = $this->request->getSession()->read('user_data');
}
else{
    return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
}
$find_user_types = $this->Common->find_user_type();
$find_user_type_list = [];
foreach ($find_user_types as $find_user_type) {
    $find_user_type_list[$find_user_type->user_type_id] = $find_user_type->user_type;
}
?>
<section class="adduser-sec dashboard-sec mt-5 mt-lg-0 ">
    <div class="container-fluid">
        <div class="templatereset-heading ">
            <h4><?php echo $find_user_type_list[$currentuser['user_type_id']]?> Dashboard (<span><?php echo $currentuser['user_first_name']?></span>)</h4>
        </div>
        <div class="row mx-0  g-0 mt-lg-0 mt-5">
            <div class="col-xl-8  col-lg-9 offset-lg-2 offset-xl-2">
                <form>
                    <a href="<?php echo $this->Url->build(['controller' => 'EntryMaster', 'action' => $action, 0]);?>" class="btn btn-primary d-block mb-1">DERIV-ACTION DATABASE</a>
                    <a href="<?php echo $this->Url->build(['controller' => 'EntryMaster', 'action' => $action, 1]);?>" class="btn dele-btn d-block">SPILL-OVER DATABASE</a>
                </form>
            </div>
            <?php echo $this->element('footer');?>
        </div>
    </div>

</section>

