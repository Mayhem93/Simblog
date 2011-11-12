<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{$blog_title}</title>
{foreach $plugin_css_files as $css}
<link rel="stylesheet" type="text/css" href="plugins/{$plugin_css[$css@index]}/{$css}" />
{/foreach}
{foreach $plugin_js_files as $js}
<script type="text/javascript" src="plugins/{$plugin_js[$js@index]}/{$js}"></script>
{/foreach}
</head>
<body>
<p>Work in progress, contact: {$email}.</p>
</body>
</html>