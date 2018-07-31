<p align="center">
    <h1 align="center">Static forms FormHandler library</h1>
</p>

Small library to validate simple html forms data and send requests to email.
Furthermore you can write your own "handler" to process valid data, for example if you need to save
 it through API to a 3d-party service like Mailchimp, SalesForce, CRM system, etc.).

# Why FormHandler

It's very easy to find some ready-to-use solution to process a contact form. Usually this is pure PHP
script, which collect data and send email with php `mail()` function. It's not bad, but you can find
numerous problems with such scripts:

* `mail()` function can be blocked on production server, because it's not secure. Also it's often goes to SPAM folder, when you use `mail()` function.
* You need to validate, that the data is valid. Manual validation of the `$_POST` array is time consuming and require knowledge of PHP, RegExp's, etc. 

We decide to create small library, which fix all these issues, so to process a form you need:

* set validation rules with simple configuration array
* set your Mail settings (SMTP settings OR Mandrill API key)
* set your message params (From, To, Subject, Body template)

And that's it!

# Requirements

* PHP 7.0+
* [Composer](http://getcomposer.org/download)
* Working SMTP server to send emails, or Mandrill account with configured mail domain.

# Usage

Imagine you have simple html website with a contact form and you want to process it.
We have `name`, `email`, and `message` form fields.  
We will guide you through the whole process of creating PHP script to process a form request.

## 1. Init your environment

We suggest to create separate folder to place code into it. Let's call it `form`. 
File structure will looks like this:

	|- /form/        # folder for our code
	|- contact.php   # simple HTML page with a form

Inside `/form/` folder we need to create `composer.json` file to set our library requirement:

	{
        "require": {
            "justcoded/form-handler": "*"
        }
    }

Now we need to download all required files with a composer, by running a bash command:

	composer install

## 2. Entry file

You must create entry file, which will handle the form request. 
You can copy one of our examples `examples/basic.php` or `examples/advanced.php` inside package folder.  

Let's call our file `form.php` and create it from scratch. 
It should be accessible from browser (for example: `http://MY-DOMAIN.COM/form/form.php`).

After that you need to include composer autoloader script and then set use part for library classes:

```php
<?php
// init autoload.
require __DIR__ . '/vendor/autoload.php';

use JustCoded\FormHandler\FormHandler;
use JustCoded\FormHandler\Handlers\MailHandler;
use JustCoded\FormHandler\DataObjects\MailMessage;
use JustCoded\FormHandler\FileManager\FileManager;
```

## 3. Form processing

Form processing idea is super easy. We have main `FormHandler` object, which will validate data and 
run some handler (right now we have only one Handler - email sender). And as the result we can get info
about errors found during the whole process.

All this code is placed at the end of the `form.php` and looks like this:

```php
$mailer = new MailHandler($mailerConfig, new MailMessage($messageConfig));
$form = new FormHandler($validationRules, $mailer);

if ($form->validate($_POST)) {
	$form->process();
}

$result = $form->response();

// TODO: do somethign with the results. For example write to a session and redirect back.
```

## 4. Set Configurations

As you can see above we need to set 3 configuration arrays:

* `$validationRules` - defines validation rules and messages
* `$mailerConfig` - defines mailer component (PHPMailer or Mailchimp) and it's params
* `$messageConfig` - defines From/To/Body fields

### 4.1. Validation Rules

For validation we use popular [Valitron](https://github.com/vlucas/valitron) PHP library. We use 
`mapFieldsRules()` method to set fields rules and `labels()` method to set field labels to show
error messages correctly. So what you need to do is to set `'fields'` and `'labels'` keys in 
`$validationRules` array:

 
```php
$validationRules = [
	'fields' => [
		'name' => ['required'],
		'email' => ['required', 'email'],
		'message' => [
			'required',
			['lengthMin', 5]
		],
	], // according to Valitron doc for mapFieldsRules().
	'labels' => [
		'name'  => 'Name',
		'email' => 'Email address',
		'message' => 'Message',
	] // according to Valitron doc for labels().
];
```

### 4.2. Mailer Config

There are two options for Mailer: [PHPMailer](https://github.com/PHPMailer/PHPMailer) and implementation
of [Mandrill API](https://mandrillapp.com/api/docs/).

* PHPMailer is used to send through `SMTP` protocol or PHP `mail()` function.
* Mandrill API is used to send through [Mandrill](https://www.mandrill.com/) mail service using it's API.  

Below you can find examples of configuration arrays for both methods:

```php
// PHPMailer config:
$mailerConfig = [
	'mailer'   => MailHandler::USE_PHPMAILER,
	'host'     => 'SMTP HOST',     // set your smtp host.
	'user'     => 'YOUR EMAIL',    // set email.
	'password' => 'YOUR PASSWORD', // set password.
	'protocol' => 'tls',           // 'tls', 'ssl' or FALSE for not secure protocol/
	'port'     => 587,             // your port.
];

// Mandrill config:
$mailerConfig = [
	'mailer'   => MailHandler::USE_MANDRILL,
	'apiKey' => 'YOUR API KEY',  // set correct API KEY.
];
```

### 4.3. Message configuration

The latest configuration you have to set is options for your email: From, To addresses; Subject and Body.
Optional you can set CC and BCC headers as well.

Example:

```php
$messageConfig = [
    'from' => ['noreply@my-domain.com' => 'My Domain Support'],
    'to' => ['admin@my-domain.com' => 'John Doe'],
    'replyTo' => ['REPLY.EMAIL@DOMAIN.COM' => 'REPLY NAME'],     // OPTIONAL
    'cc'      => ['cc-email@gmail.com', 'more-cc@gmail.com'],    // OPTIONAL
    'bcc'     => ['bcc-email@gmail.com'],                        // OPTIONAL
    'subject' => 'Contact request from {name}',
    'bodyTemplate' => __DIR__ . '/template-html.php',        // Path to HTML template
    'altBodyTemplate' => __DIR__ . '/template-plain.php',    // Path to TEXT template
];
```

For each address field you can set numerous emails in such format:

	[ email1 => name1, email2 => name2, ... ]
	OR
	[email1, email2, email3 ...]

`bodyTemplate` and `altBodyTemplate` are paths to usual PHP template files, which will be used to generate
email message (HTML and plain versions accordingly).

## 5. All together

If we combine all parts we can get file similar to this one:

```php
<?php

// init autoload.
require __DIR__ . '/../vendor/autoload.php';

use JustCoded\FormHandler\FormHandler;
use JustCoded\FormHandler\Handlers\MailHandler;
use JustCoded\FormHandler\DataObjects\MailMessage;

$validationRules = [
	'fields' => [
		'name' => ['required'],
		'email' => ['required', 'email'],
		'message' => [
			'required',
			['lengthMin', 5]
		],
	], // according to Valitron doc for mapFieldsRules.
	'labels' => [
		'name'  => 'Name',
		'email' => 'Email address',
		'message' => 'Message',
	] // according to Valitron doc.
];

// SMTP config.
$mailerConfig = [
	'mailer'   => MailHandler::USE_PHPMAILER,
	'host'     => 'SMTP HOST',     // set your smtp host.
	'user'     => 'YOUR EMAIL',    // set email.
	'password' => 'YOUR PASSWORD', // set password.
	'protocol' => 'tls',           // 'tls', 'ssl' or FALSE for not secure protocol/
	'port'     => 587,             // your port.
];

// Message settings.
$messageConfig = [
	'from' => ['FROM.EMAIL@DOMAIN.COM' => 'FROM NAME'],     // set correct FROM.
	'to' => ['TO.EMAIL@DOMAIN.COM' => 'TO NAME'],           // set correct TO.
	'replyTo' => ['REPLY.EMAIL@DOMAIN.COM' => 'REPLY NAME'],// set correct REPLY.
	'subject' => 'Contact request from {name}',
	'bodyTemplate' => __DIR__ . '/template-html.php',
	'altBodyTemplate' => __DIR__ . '/template-plain.php',
];

// Run processing.
$mailer = new MailHandler($mailerConfig, new MailMessage($messageConfig));
$form   = new FormHandler($validationRules, $mailer);

if ($form->validate($_POST)) {
	$form->process();
}

// write errors and return back.
setcookie('basic_response', $form->response());
header('Location: index.php');
exit;
```

In this example we write errors to cookies to be able to get them on the HTML page via JavaScript or PHP code.

## 6. Body templates

Templates are usual PHP files, which can print any PHP code you leave inside. However to make editing
easier we added tokens support. So any keys, which are passed as data to FormHandler can be used as a 
token like this: `{key}`.

**template-html.php** example:
```html
<?php
/* @var array $data */
?>
<html>
<body>
<p>Hi John,</p>
<p>Someone submitted a contact form on your site with such data:</p>
<p><b>Name:</b> {name}</p>
<p><b>Email:</b> {email}</p>
<p><b>Message:</b><br>
	{message}</p>

<hr>
<p>User IP address: <?php echo @$_SERVER['REMOTE_ADDR']; ?></p>
<p>Browser: <?php echo @$_SERVER['HTTP_USER_AGENT']; ?></p>

</body>
</html>
``` 

**template-plain.php** example:
```php
<?php
/* @var array $data */
?>
Hi John,
Someone submitted a contact form on your site with such data:

Name:    {name}
Email:   {email}
Subject: {subject}
Message:

	{message}

-------

User IP address: <?php echo @$_SERVER['REMOTE_ADDR']; ?>
Browser: <?php echo @$_SERVER['HTTP_USER_AGENT']; ?>
```

## Response formats

FormHandler can return response as ARRAY or as JSON. By default it return a JSON string. 
To change this behavior you need to add one more parameter to FormHandler object creation:

```php
$form   = new FormHandler($validationRules, $mailer, 'array');
```  

Once you get a response, you need to pass it to the page with a form to show errors. This can be done
in several ways:

### Pass response as JSON object

We recommend to send form request with AJAX request. In this case you will need single JSON object as
server side response:

```php
// print errors as json.
header('Content-Type: application/json; charset=utf-8');
echo $formHandler->response();
exit;
```

### Pass response through COOKIES

If you use cookies - you can use JavaScript to display errors on the site and don't need PHP knowledge
in this case.

```php
// set cookie with form status/errors and redirect back
setcookie('form_status', $form->response());
header('Location: index.php');
exit;
```

### Pass response through SESSION

In case you want to process errors with PHP code - then better option of passing errors is using
 a session:

```php
// start session if not started:
session_start();
// set sesson with form status/errors and redirect back
$_SESSION['form_status'] = $form->response();
header('Location: index.php');
exit;
```

### Response array

In case of success
```json
{"status":true,"errors":[]}
```

In case of errors:
```json
{"status":false,"errors":{"field1": ["Error1", "Error2"], "field2": ["Error3", "Error4"]}}
```

## File uploads and mail attachments

Form handler also supports File uploads and sending them as email attachments.

To add this feature you will need one more class, called `FileManager`:

```php
// Configure the location of attachments directory 
// it should be writable and accessible from browser
$fileManager = new FileManager([
    'uploadPath' => __DIR__ . '/attachments',           // folder path to save files to 
    'uploadUrl' => 'http://MY-DOMAIN.COM/attachments',  // site URL to this folder
]);
```

After that you need to specify which files should be uploaded in `$messageConfig`:

```php
$messageConfig = [
	'from' => ['FROM.EMAIL@DOMAIN.COM' => 'FROM NAME'],     // set correct FROM.
	'to' => ['TO.EMAIL@DOMAIN.COM' => 'TO NAME'],           // set correct TO.
	'replyTo' => ['REPLY.EMAIL@DOMAIN.COM' => 'REPLY NAME'],// set correct REPLY.
	'subject' => 'Contact request from {name}',
	'bodyTemplate' => __DIR__ . '/template-html.php',
	'altBodyTemplate' => __DIR__ . '/template-plain.php',
	
    'attachments' => $fileManager->upload([
        'input_file_name1', 'input_file_name2', // ...
    ])
];
```

`input_file_name1`, `input_file_name2` are the name attributes of file inputs:

```html
<input type="file" name="input_file_name1">
<input type="file" name="input_file_name2">
...
```

Of course each mail server has a limit of maximum attachments size. Usually it's not more than 10MB.
To set this limit correctly you need to update `$mailerConfig` with additional option:

```php
$mailerConfig = [
	'mailer'   => MailHandler::USE_PHPMAILER, // or USE_MANDRILL
	...

	'attachmentsSizeLimit' => 8388608, // 8MB in Bytes.
];
```

All attachments are uploaded to the specified directory and we recommend to add links to them inside
body/alternativeBody templates. To print file link to a file you need to write a token with input
file name. Like this:

```php
...
<p>Attachments: {input_file_name1}, {input_file_name2}</p>
...
```

If you need to validate your file with specific type or size you can use our custom "file" validator:

```php
$validationRules = [
	'fields' => [
		// ...
		'input_file_name1' => [  // this is file field.
			[
				'file',
				['jpeg', 'jpg', 'png', 'pdf'], // types.
				2000000,                       // size limit around 2 MB.
				'message' => '{field} should be up to 2MB and allows only file types jpeg, png.',
			],
		],
		...
];
```

## Multiple fields

Some forms may have multiple fields, like checkboxes, multiple selects or dynamically created inputs.

Example:

```html
<!-- multiple select -->
<select name="choice" multiple>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
</select>

<!-- text inputs -->
<input type="text" name="links[]">
<input type="text" name="links[]">
<input type="text" name="links[]">
```

You can validate each input using wildcard field name inside **validation rules*:

```php
$validationRules = [
	'fields' => [
		// ...
		'choice.*' => ['int'],
		'links.*'  => ['url'],
		...
];
```

Same as file attachments you can use tokens to print all values at once. They will be comma separated.

**Template usage:**

```html
...
<p>Choice: {choice}</p>
<p>Links: {links}</p>
```

# Examples

You can check working examples inside `examples` folder of the package, start your investigate from `index.php` file (contains forms HTML).
There you can find which files loaded next, when you submit forms.
