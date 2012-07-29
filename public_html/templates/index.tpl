<!DOCTYPE html>
<html lang="en" >
<head>
	<title>{$page_title}</title>
	<meta name="author" content="Me">
	<meta name="description" content="{smarty_getSetting('blog_description')}">
	<link rel="stylesheet" href="cache/css/{$cssFile}">
	<script type="application/javascript" src="cache/js/{$jsFile}"></script>
</head>
<body class="simblog">
{if smarty_isAdminSession()}
	{include file="bits/admin_box.tpl"}
{/if}
<div class="container mainBody">
	<header class="row">
		<a class="blog-title" href="\" title="Home"><h1>{smarty_getSetting('blog_title')}</h1></a>
		<h3 class="blog-descr">{smarty_getSetting('blog_description')}</h3>
	</header>
	<section class="row">
		<div id="pinnedPosts" class="span4 offset2 carousel">
{* 			<div class="carousel-inner">
 				<div class="active item">
 					<h2 title="Heading">Heading1</h2>
 					<p>Donec id elit non mi porta gravida at eget metus. Fusce
 						dapibus, tellus ac cursus commodo, tortor mauris condimentum
 						nibh, ut fermentum massa justo sit amet risus. Etiam porta sem
 						malesuada magna mollis euismod. Donec sed odio dui...</p>
 				</div>
 				<div class="item">
 					<h2 title="Heading">Heading2</h2>
 					<p>She exposed painted fifteen are noisier mistake led
 						waiting. Surprise not wandered speedily husbands although yet
 						end. Are court tiled cease young built fat one man taken. We
 						highest ye friends is exposed equally in. Ignorant had too
 						strictly followed. Astonished as travelling assistance...</p>
 				</div>

 			</div>
 			<a class="carousel-control left" href="#pinnedPosts" data-slide="prev">&lsaquo;</a>
 			<a class="carousel-control right" href="#pinnedPosts" data-slide="next">&rsaquo;</a> *}
		</div>
	</section>
	<nav class="navbarBlog">
		<ul class="nav nav-pills">
			<li class="active"><a href="\" title="Home">Home</a></li>
		</ul>
	</nav>
	<div id="content" class="row">
		<div id="leftPanel" class="span8">
			{include file=$page_template}
		</div>
		<div id="rightPanel" class="span3">
			{include file="bits/archives.tpl"}
			<div id="categoriesBlock">{* TODO *}
				<h2>Categories</h2>
				<ul>
					<li><a href="#">Sample category 1</a></li>
					<li><a href="#">Sample category 2</a></li>
				</ul>
			</div>
		</div>
	</div>
	{include file="bits/footer.tpl"}
</div>
</body>
</html>