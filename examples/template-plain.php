<?php
/* @var array $data */
?>
Name:    {name}
Email:   {email}
Subject: {subject}
Message:

	{message}

-------

User IP address: <?php echo @$_SERVER['REMOTE_ADDR']; ?>
Browser: <?php echo @$_SERVER['HTTP_USER_AGENT']; ?>
