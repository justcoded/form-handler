<p align="center">
    <h1 align="center">Form2email</h1>
    <br>
</p>

This library allows you rapidly implement contact forms on your site.

## 1. Installation

### 1.1 Install via Composer (preferable)

The recommended way to install Form2email is through
[Composer](http://getcomposer.org).

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install Form2email using the following command:

~~~
1. Create folder 
2. Create composer.json file (if it doesn't exist) with json record:

{
    "require": {
        "monolog/monolog": "1.0.*"
    }
}

3. composer create-project 

4. composer require justcoded/form-handler
~~~
### 1.2 Install by download directly (alternative)

### 1.2.1 Clone the project
~~~
git clone https://github.com/justcoded/form-handler.git
~~~
### 1.2.2 Install dependency
~~~
cd form-handler

composer install
~~~

## 2. Contact form
Create contact form with 'action' attribute where to send the form-data when a form is submitted. You can implement 
multiple forms. You can find an example in the file examples/index.php

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

## 3. Entry file

You must create entry file handler for created contact form. To do this, copy the file 
form2email-basic.php or form2email-mandrill.php from the Example folder to the root of the site. 
Make sure that the file is accessible from the browser (for example: http://MY-DOMAIN.COM/path/to/form2email-basic.php).
After that in the /path/to/form2email-basic.php file, include the path to the file vendor/autoload.php.

```php
// init autoload.
require __DIR__ . '/../vendor/autoload.php';
```

## 4. Configuration

In the action file (path/to/form2email-basic.php), we must write a configuration of validation, mailer and message:
 For validation of text fields we use [Valetron](https://github.com/vlucas/valitron#built-in-validation-rules) library.
 
```php

// In the $validation array are listed the form fields and the corresponding rule 
// and labels (not neccessery)


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

// Configure the location of attachments directory 
// and set the write permission (chmod -R 777 path/to/directory)
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

## 5. Template
You can customize the email templates. You can place the form fields anywhere. To do this, put the name of the form 
field in curly braces. For example, in the file 'template-html.php':

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

Creating another contact form
-----------------------------
You must create a new folder in the root of the site and make the steps(2-5) described above again.

Validation of text field generated automatically
------------------------------------------------

## 1. Format of generated fields should be:
```html
    <input class="text-field" type="text" name="links[]" id="link-1">
    <input class="text-field copy" type="text" name="links[]" id="link-2">
    <input class="text-field copy" type="text" name="links[]" id="link-3">
```

## 2. Validation format:
```php
$validation = [
    'fields' => [
        ...,
        'links.*' => ['url'], // or another rule
    ],
    'labels' => [
        ...
    ]
];
```

## 3. Template format:
In the template add name of generated form fields grouped by name. In our example the name is 'links':
```html
...
{links}
...
```
These fields will be render as a string, listed through a comma.
For example: "value1, value2, value3, ..."