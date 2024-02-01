<?php
if($this->request->getSession()->check('user'))
{
    $currentuser = $this->request->getSession()->read('user_data');
    if($this->request->getSession()->read('user')=='supervisor')
    {
        $action = 'supervisorHome';
    }
    elseif($this->request->getSession()->read('user')=='operator')
    {
        $action = 'operatorHome';
    }
}
elseif($this->request->getSession()->check('temp_user'))
{
    
}
else{
    return $this->redirect(array("controller" => "UserMaster", "action" => "login"));
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<header class="header-area">
    <section class="header-sec">
        <div class="container-fluid ">
            <div class="row align-items-center">
                <div class="col-12 col-sm-4 col-md-3 col-lg-3 ">
                    <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'dashboard']);?>">
                    	<?php echo $this->Html->image("/images/logo.png", ['alt' => 'exchange data international', 'class' => 'mw-100']);?>
                    </a>
                </div>
                <div class="col-12 col-sm-8 col-md-9 col-lg-9 text-md-end mt-3 mt-xl-0">
                    <ul>
                        <?php
                        if($this->request->getSession()->check('database_type')) {
                            $database_type = $this->request->getSession()->read('database_type');
                        ?>
                        <li>
                            <a href="<?php echo $this->Url->build(['controller' => 'EntryMaster', 'action' => $action]);?>"> <?php echo $database_type_arr[$database_type]?></a>
                        </li>
                        <?php
                        }
                        ?>
                        <li>
                            <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'changePassword']);?>"><i class="fa-sharp fa-solid fa-lock"></i> Change Password</a>
                        </li>
                        <li>
                            <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'dashboard']);?>"> <i class="fa-solid fa-house"></i>Home</a>
                        </li>
                        <li>
                            <a href="<?php echo $this->Url->build(['controller' => 'UserMaster', 'action' => 'logout']);?>"> <i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</header>