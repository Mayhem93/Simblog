<html lang="en-US" dir="ltr">
<head>
	<meta charset="UTF-8">
	<title>{$page_title}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Cătălin Dogaru" name="author">
	<meta content="{$blog_description}" name="description">
	<meta content="index, follow" name="robots">
	<link href="http://gmpg.org/xfn/11" rel="profile">
	<link href="cache/css/{$cssFile}" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<link href="{$skin->getSkinWWWuri()}/assets/ico/favicon.ico" rel="shortcut icon">
	<link href="{$skin->getSkinWWWuri()}/assets/ico/apple-touch-icon-144-precomposed.png" rel="apple-touch-icon-precomposed" sizes="144x144">
	<link href="{$skin->getSkinWWWuri()}/assets/ico/apple-touch-icon-114-precomposed.png" rel="apple-touch-icon-precomposed" sizes="114x114">
	<link href="{$skin->getSkinWWWuri()}/assets/ico/apple-touch-icon-72-precomposed.png" rel="apple-touch-icon-precomposed" sizes="72x72">
	<link href="{$skin->getSkinWWWuri()}/assets/ico/apple-touch-icon-57-precomposed.png" rel="apple-touch-icon-precomposed">
	<meta content="Simblog" name="generator">
	<link href="?feed=rss2" title="Simblog &raquo; Feed" type="application/rss+xml" rel="alternate">
	<link href="?feed=comments-rss2" title="Simblog &raquo; Comments Feed" type="application/rss+xml" rel="alternate">
</head>
<body>

{include file=$skin['0']['header']->getTplPath()}
	<div class="container">
		<div class="row">
			{include file=$page_template}
			{foreach $skin['3'] as $zone}
				{include file=$zone->getTplPath()}
			{/foreach}
		</div><!-- .row -->
		<hr>
{include file=$skin['1']['footer']->getTplPath()}
	</div><!-- .container -->
	<script src="cache/js/{$jsFile}"></script>
	<script>
		!function($) {
			$(function() {
				$('#featured-post-area').carousel()
			})
		} (window.jQuery)
	</script>
</body>
</html>
