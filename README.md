<p align="center">
    <h1 align="center">Form2email</h1>
    <br>
</p>

This library allows you rapidly implement contact forms on your site.

DIRECTORY STRUCTURE
-------------------

      examples/                       contains application configurations 
        |-- attachments                  contains attachments file
        |-- form2email-basic.php         contains form handler sript that uses PHPMailer
        |-- form2email-mandrill.php      contains form handler sript that uses Mandrill
        |-- index.php                    contains test forms
        |-- template-html.php            contains markup of email template
        |-- template-plain.php           contains markup plain of email template
      src/                           source code
      vendor/                        contains dependent libraries

INSTALLATION
------------

### Install via Composer

The recommended way to install Form2email is through
[Composer](http://getcomposer.org).

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install Form2email using the following command:

~~~
php composer.phar create-project --prefer-dist --stability=dev justcoded/form2email my-project
~~~

## Contact form
Create contact form with 'action' attribute where to send the form-data when a form is submitted. You can implement multiple forms. 
```html
<form action="/path/to/form2email-basic.php" method="post" enctype="multipart/form-data">
    Name: <input type="text" name="name"><br>
    E-mail: <input type="text" name="email"><br>
    Subject: <input type="text" name="subject"><br>
	Message: <textarea name="message"></textarea>
    <p>File1:<input type="file" name="cv_file"></p>
    <p>File2:<input type="file" name="image_file"></p>
    <input type="submit">
</form>
```

CONFIGURATION
-------------
All configuration files are located in the Example folder.
In the action file (example/form2email-basic.php), we must write a configuration of validation, mailer and message:
 For validation of text fields we use [Valetron](https://github.com/vlucas/valitron#built-in-validation-rules) library.
 
```php

// In the $validation array are listed the form fields and the corresponding rule and labels (not neccessery)

$validation = [
    'fields' => [
        'name' => ['required'],
        'email' => ['required', 'email'],
        'subject' => ['required'],
        'message' => [
            'required',
            ['lengthMin', 5]
        ],
        'cv_file' => [
            [
                'required',
                'message' => 'Please upload {field}',
            ],
            [
                'file',
                ['jpeg', 'jpg', 'png'], // types.
                2000000, // size limit 2 MB.
                'message' => '{field} should be up to 2MB and allows only file types jpeg, png.',
            ],
        ],
    ], // according to Valitron doc for mapFieldsRules.
    'labels' => [
        'name'  => 'Name',
        'email' => 'Email address'
    ] // according to Valitron doc.
];

$mailerConfig = [
    'mailer'   => MailHandler::USE_PHPMAILER, // (or USE_POSTMARKAPP, USE_MANDRILL)
    'host'     => 'smtp.gmail.com',
    'user'     => 'YOUR EMAIL',
    'password' => 'YOUR PASSWORD',
    'protocol' => 'tls',
    'port'     => 587,
    'attachmentsSizeLimit' => 8000000, // around 8MB.
];

// Configure the location of attachments directory and set the write permission (chmod -R 777 path/to/directory)
$fileManager = new FileManager([
    'uploadPath' => __DIR__ . '/attachments',
    'uploadUrl' => 'http://MY-DOMAIN.COM/attachments',
]);

$message = [
    'from' => ['hello@justcoded.co.uk' => 'FROM NAME'],
    'to' => ['kostant21@yahoo.com' => 'TO NAME'],
    //	'cc'      => ['email' => 'name'],
    //	'bcc'     => ['email' => 'name'],
    'subject' => 'Contact request from {name}',
    'bodyTemplate' => __DIR__ . '/template-html.php', // Path to 
    'altBodyTemplate' => __DIR__ . '/template-plain.php',
    'attachments' => $fileManager->upload([
        'cv_file', 'image_file'
    ])
];
```
## Template
You can customize the email templates. For example, in the file 'template-html.php':

```html
<html>
<body>
<p><b>Name:</b> {name}</p>
<p><b>Email:</b> {email}</p>
<p><b>Subject:</b> {subject}</p>
<p><b>Message:</b><br>
	{message}</p>
<hr>
<p>User IP address: <?php echo @$_SERVER['REMOTE_ADDR']; ?></p>
<p>Browser: <?php echo @$_SERVER['HTTP_USER_AGENT']; ?></p>

{cv_file}

{image_file}

</body>
</html>
```
You can place the form fields anywhere. To do this, put the name of the form field in curly braces.
