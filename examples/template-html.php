<?php
/* @var array $data */
?>

<html>
<body>
<p><b>Name:</b> {name}</p>
<p><b>Email:</b> {email}</p>
<p><b>Subject:</b> {subject}</p>
<p><b>Message:</b><br>
	{message}</p>

<?php if (count($links) > 0): ?>
    <ul>
        <?php foreach ($links as $link): ?>
            <li>
                <a href="<?php echo $link->getPath() ?>"><?php echo $link->getName() ?> </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

</body>
</html>
