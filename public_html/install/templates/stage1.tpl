<form method="post" action="index.php">
	<div class="header-title">
		<p class="header-title">General configuration</p>
		{if $not_writable}
		<p class="error">Error: The root folder is not writeable.</p>
		{/if}
	</div>
	<div class="section">
		<ol>
			<li>
				<label for="author">Author: </label>
				<input id="author" name="author" type="text" size="20" />
			</li>
			<li>
				<label for="title">Blog title: </label>
				<input id="title" name="title" type="text" size="20" />
			</li>
			<li>
				<label for="email">E-mail address (public): </label>
				<input id="email" name="email" type="text" size="20" />
			</li>
			<li>
				<label for="disabled_plugins">Install as disabled: </label>
				<input id="disabled_plugins" name="disabled_plugins" type="checkbox" />
				<label class="explanation" for="disabled_plugins">Will install plugins as disabled by default. </label>
			</li>
		</ol>
	</div>
	<div class="header-title">
		<p class="header-title">Database configuration</p>
		{if $mysql_error}
			<p class="error">Database error: {$mysql_error_msg}</p>
		{/if}
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
			<li>
				<label for="tbl_prefix">*  Table Prefix: </label>
				<input id="tbl_prefix" name="tbl_prefix" type="text" size="20" />
			</li>
		</ol>
		<p class="optional">* Denotes optional</p>
		<input class="submit" type="submit" value="Submit" />
	</div>
	</form>