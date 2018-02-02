<?php
/* @var array $data */
?>
<html>
<body>
<p><b>Name:</b> {name}</p>
<p><b>Email:</b> {email}</p>
<p><b>Message:</b><br>
	{message}</p>

<p>{cv}</p>
<p>{links}</p>

<hr>
<p>User IP address: <?php echo @$_SERVER['REMOTE_ADDR']; ?></p>
<p>Browser: <?php echo @$_SERVER['HTTP_USER_AGENT']; ?></p>

</body>
</html>
