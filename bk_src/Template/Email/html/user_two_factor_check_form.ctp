Hi <?php echo  $user['user_first_name'];?>,<br/><br/>It seems you are trying to login.<br/>Here is your One Time Password (OTP). Please copy it and verify your identity.<br/><br/>
<b>OTP:</b> <?php echo  $generate_otp;?><br/><br/>
<b>This OTP will expire with in <?php echo $login_expiring_time?>.</b><br/><br/>
Do not share this OTP with anyone.<br/>
If this wasn't you please ignor this email or contact your admin.<br/><br/><br/>

Yours sincerely,<br/>
<?php echo $website_title;?>