<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="bootstrap-responsive.css">
<link rel="stylesheet" type="text/css" href="style.css" >
<title>Simblog Installation</title>
</head>
<body>
<div class="container">
{if $stage == 'stage1'}
	{include file='stage1.tpl'}
{else}
	{include file='success.tpl'}
{/if}
</div>
</body>
</html>