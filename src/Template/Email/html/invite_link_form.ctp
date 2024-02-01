Hi <?php echo  $user['user_first_name'];?>,<br/><br/><?php echo $website_title?> invites you to join their team as a <?php echo $user_type?>.<br/>Here is your Details. Please copy it and verify your identity.<br/><br/>
<b>Username:</b> <?php echo  $user['user_name'];?><br/>
<b>Temp Password:</b> <?php echo  $generate_password;?><br/><br/>

Do not share this details with anyone.<br/>
To accept the invitation, please click on te join button below.<br/><br/><br/>
<a href="<?php echo $invite_url.'/'.$user['user_email'];?>" style="background-color: #17abdb;font-family: 'Arial';font-size: 16px;color: #000000;font-weight: bold;letter-spacing: 1px;padding: 15px 60px;display:inline-block;text-decoration:none;">Join The Team</a>
<br/><br/>
Yours sincerely,<br/>
<?php echo $website_title;?>