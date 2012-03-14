<form method="post" action="">
	<div class="header-title">
		<p class="header-title">General configuration</p>
		{if isset($not_writable)}
		<p class="error">Error: The root folder is not writeable.</p>
		{/if}
	</div>
	<div class="section">
		<ol>
			<li>
				<label for="author">Author: </label>
				<input id="author" name="author" type="text" size="20" />
				{if isset($inputErrors.author)}
				<p class="error">Author cannot be empty.</p>
				{/if}
			</li>
			<li>
				<label for="title">Blog title: </label>
				<input id="title" name="title" type="text" size="20" />
				{if isset($inputErrors.title)}
				<p class="error">Title cannot be empty.</p>
				{/if}
			</li>
			<li>
				<label for="description">Short description: </label>
				<input id="description" name="description" type="text" size="20" />
				{if isset($inputErrors.description)}
				<p class="error">Description cannot be empty.</p>
				{/if}
			</li>
			<li>
				<label for="email">E-mail address (public): </label>
				<input id="email" name="email" type="text" size="20" />
				{if isset($inputErrors.email)}
				<p class="error">E-mail address cannot be empty.</p>
				{/if}
			</li>
			<li>
				<label for="disabled_plugins">Install as disabled: </label>
				<input id="disabled_plugins" name="disabled_plugins" type="checkbox" />
				<span class="explanation">Will install plugins as disabled by default. </span>
			</li>
			<li>
				<label for="db_support">SQL database support: </label>
				<input id="db_support" name="db_support" type="checkbox" checked="checked" />
				<span class="explanation">You can install this without a SQL database. </span>
			</li>
			<li>
				<label for="admin_username">Administrator username: </label>
				<input id="admin_username" name="admin_username" type="text" size="20" />
				{if isset($inputErrors.admin_username)}
				<p class="error">This field cannot be empty.</p>
				{/if}
			</li>
			<li>
				<label for="admin_password">Administrator password: </label>
				<input id="admin_password" name="admin_password" type="text" size="20" />
				{if isset($inputErrors.admin_password)}
				<p class="error">This field cannot be empty.</p>
				{/if}
			</li>
		</ol>
	</div>
	<div class="header-title">
		<p class="header-title">Database configuration</p>
		{if isset($mysql_error)}
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