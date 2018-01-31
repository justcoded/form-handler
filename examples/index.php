<html>
<body>

<form action="form2email-basic.php" method="post" enctype="multipart/form-data">
    Name: <input type="text" name="name"><br>
    E-mail: <input type="text" name="email"><br>
    Subject: <input type="text" name="subject"><br>
	Message: <textarea name="message"></textarea>
    <p>File1:<input type="file" name="cv_file"></p>
    <p>File2:<input type="file" name="image_file"></p>
    <input type="submit">
</form>
<hr>
<form action="form2email-mandrill.php" method="post" enctype="multipart/form-data">
    Name: <input type="text" name="name"><br>
    E-mail: <input type="text" name="email"><br>
    Subject: <input type="text" name="subject"><br>
    Message: <textarea name="message"></textarea>
    <p>File1:<input type="file" name="cv_file"></p>
    <p>File2:<input type="file" name="image_file"></p>
    <input type="submit">
</form>

</body>
</html>