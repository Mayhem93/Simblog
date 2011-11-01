<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="style.css" />
<title>Simblog Installation</title>
</head>
<body>
<div class="container">
	<form method="post" action="index.php">
	<div class="header-title">
		<p class="header-title">General configuration</p>
	</div>
	<div class="section">
		<ol>
			<li>
				<label for="title">Blog title: </label>
				<input id="title" name="title" type="text" size="20" />
			</li>
			<li>
				<label for="email">E-mail address (public): </label>
				<input id="email" name="email" type="text" size="20" />
			</li>
			<li>
				<label for="disabled_plugins">E-mail address (public): </label>
				<input id="disabled_plugins" name="disabled_plugins" type="checkbox" />
			</li>
		</ol>
	</div>
	<div class="header-title">
		<p class="header-title">Database configuration</p>
	</div>
	<div class="section">
		<ol>
			<li>
				<label for="hostname">Host name: </label>
				<input id="hostname" name="hostname" type="text" size="20" />
			</li>
			<li>
				<label for="port">*  Port: </label>
				<input id="port" name="port" type="text" size="20" />
			</li>
			<li>
				<label for="username">User name: </label>
				<input id="username" name="username" type="text" size="20" />
			</li>
			<li>
				<label for="password">Password: </label>
				<input id="password" name="password" type="text" size="20" />
			</li>
			<li>
				<label for="database_name">Database name: </label>
				<input id="database_name" name="database" type="text" size="20" />
			</li>
		</ol>
		<p class="optional">* Denotes optional</p>
	</div>
	</form>
</div>
</body>
</html>