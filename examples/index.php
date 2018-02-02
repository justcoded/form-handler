<?php
function check_response($key) {
	if (!empty($_COOKIE[$key])) {
		setcookie($key, null);
		return json_decode($_COOKIE[$key], true);
	}
	return false;
}

function print_errors($errors) {
	$errors = array_map(function ($value){ return array_shift($value); }, $errors);
	print implode('<br>', $errors);
}
?><html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Form Handler forms examples</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<div class="container theme-showcase" role="main">

		<!-- Main jumbotron for a primary marketing message or call to action -->
		<div class="jumbotron">
			<h1>Form Handler examples</h1>
			<p>Here you can find forms to work with example form processing scripts.</p>
			<ul>
				<li><a href="#basic">Basic Example</a></li>
				<li><a href="#mandrill">Mandrill Example</a></li>
				<li><a href="#advanced">Advanced Example</a></li>
			</ul>
		</div>

		<!-- ============================================================= -->
		<div id="basic" class="page-header">
			<h1>Basic Example</h1>
		</div>
		<div class="panel">
			<div class="panel-body">
				<?php $resp = check_response('basic_response'); ?>
				<?php if (!empty($resp['errors'])) : ?>
					<div class="alert alert-danger" role="alert">
						<?php print_errors($resp['errors']); ?>
					</div>
				<?php endif; ?>
				<?php if (!empty($resp['status'])) : ?>
					<div class="alert alert-success" role="alert">
						Mail send.
					</div>
				<?php endif; ?>
				<form action="basic.php" method="post">
					<div class="form-group">
						<label for="basic-name">Name</label>
						<input type="text" name="name" class="form-control" id="basic-name" placeholder="Name">
					</div>
					<div class="form-group">
						<label for="basic-email">Email address</label>
						<input type="email" name="email" class="form-control" id="basic-email" placeholder="Email">
					</div>
					<div class="form-group">
						<label for="basic-message">Message</label>
						<textarea name="message" class="form-control" id="basic-message" placeholder="Write what you think"></textarea>
					</div>
					<button type="submit" class="btn btn-info">Submit</button>
				</form>
			</div>
		</div>

		<!-- ============================================================= -->
		<div id="mandrill" class="page-header">
			<h1>Mandrill Example</h1>
		</div>
		<div class="panel">
			<div class="panel-body">
				<?php $resp = check_response('mandrill_response'); ?>
				<?php if (!empty($resp['errors'])) : ?>
					<div class="alert alert-danger" role="alert">
						<?php print_errors($resp['errors']); ?>
					</div>
				<?php endif; ?>
				<?php if (!empty($resp['status'])) : ?>
					<div class="alert alert-success" role="alert">
						Mail send.
					</div>
				<?php endif; ?>
				<form action="mandrill.php" method="post">
					<div class="form-group">
						<label for="mandrill-name">Name</label>
						<input type="text" name="name" class="form-control" id="mandrill-name" placeholder="Name">
					</div>
					<div class="form-group">
						<label for="mandrill-email">Email address</label>
						<input type="email" name="email" class="form-control" id="mandrill-email" placeholder="Email">
					</div>
					<div class="form-group">
						<label for="mandrill-message">Message</label>
						<textarea name="message" class="form-control" id="mandrill-message" placeholder="Write what you think"></textarea>
					</div>
					<button type="submit" class="btn btn-info">Submit</button>
				</form>
			</div>
		</div>

		<!-- ============================================================= -->
		<div id="advanced" class="page-header">
			<h1>Advanced Example</h1>
		</div>
		<div class="panel">
			<div class="panel-body">
				<?php $resp = check_response('advanced_response'); ?>
				<?php if (!empty($resp['errors'])) : ?>
					<div class="alert alert-danger" role="alert">
						<?php print_errors($resp['errors']); ?>
					</div>
				<?php endif; ?>
				<?php if (!empty($resp['status'])) : ?>
					<div class="alert alert-success" role="alert">
						Mail send.
					</div>
				<?php endif; ?>
				<form action="advanced.php" method="post">
					<div class="form-group">
						<label for="adv-name">Name</label>
						<input type="text" name="name" class="form-control" id="adv-name" placeholder="Name">
					</div>
					<div class="form-group">
						<label for="adv-email">Email address</label>
						<input type="email" name="email" class="form-control" id="adv-email" placeholder="Email">
					</div>
					<div class="form-group">
						<label for="adv-message">About you</label>
						<textarea name="message" class="form-control" id="adv-message" placeholder="Write a few words about yourself"></textarea>
					</div>
					<div class="form-group">
						<label for="adv-cv">CV</label>
						<input type="file" id="adv-cv" name="cv">
						<p class="help-block">Upload your CV.</p>
					</div>
					<div class="form-group">
						<label>Links</label>
						<input type="url" name="links[]" class="form-control" placeholder="http://">
						<input type="url" name="links[]" class="form-control" placeholder="http://">
						<input type="url" name="links[]" class="form-control" placeholder="http://">
						<p class="help-block">Provide links of your latest projects.</p>
					</div>
					<button type="submit" class="btn btn-info">Submit</button>
				</form>
			</div>
		</div>

	</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>