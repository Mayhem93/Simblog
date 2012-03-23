<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="jquery-ui-1.8.18.css" />
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="jquery-ui-1.8.18.min.js"></script>
<script type="text/javascript" src="main.js"></script>
{include file="bits/js.tpl"}
<title>{$page_title}</title>
</head>
<body class="loading">
	<div id="dialogMessage">
	</div>
	<div id="notificationMessages">
	</div>
	<div class="navbar section" id="navbar">
		<div class="widget Navbar" id="Navbar1">
			<div></div>
		</div>
	</div>
	<div class="hfeed" id="wrapper">
		<div id="header">
			<div id="masthead">
				<div id="branding">
					<div class="section" id="header1">
						<div class="widget Header" id="Header1">
							<h1 id="site-title">
								<span> <a href="/">{smarty_getSetting('blog_title')}</a>
								</span>
							</h1>
							<div id="site-description">{smarty_getSetting('blog_description')}</div>
						</div>
					</div>
				</div>
				<!-- #branding -->
				<div id="access">
					<div class="skip-link screen-reader-text">
						<a href="#content" title="Skip to content">Skip to content</a>
					</div>
					<!-- begin menu -->
					<div class="menu">
						<div>
							<!-- Pages -->
							<div class="section" id="pages">
								<div class="widget PageList" id="PageList1">
									<div>
										<ul>
											<li class="page_item"><a href="/">Home</a></li>
											<li class="page_item"><a href="">About</a></li>
											<li class="page_item"><a href="">Contact</a></li>
										</ul>
										<div class="clear"></div>

										<div class="clear"></div>
									</div>
								</div>
							</div>
							<!-- /Pages -->
						</div>
					</div>
					<!-- end menu -->
				</div>
				<!-- #access -->
			</div>
			<!-- #masthead -->
			<div style="clear: both;"></div>
		</div>
		<!-- #header -->
		<div id="main">
			<div id="forbottom">
				<div id="socials"></div>
				<div style="clear: both;"></div>
				<div id="container">
					<div id="content">{include file=$page_template}</div>
					<!-- #content -->
				</div>
				<!-- #container -->
				<div class="widget-area" id="primary">
					<div class="widget-container widget_search" id="search">
						<form action="" id="searchform" method="get">
							<input id="s" name="q" type="text" value="Search" /> <input
								id="searchsubmit" type="submit" value="OK" />
						</form>
					</div>
					<div class="clear"></div>
					<div class="section" id="sidebar">
						<div class="widget HTML" id="HTML3">
							{if smarty_isAdminSession() } {include "bits/admin_box.tpl"}
							{else} {include "bits/admin_login.tpl"} {/if}
							<div class="clear"></div>
							<div class="clear"></div>
						</div>
						<div class="widget PopularPosts" id="PopularPosts1">
							<h2>Popular Posts</h2>
							<div class="widget-content popular-posts">
								<ul>
									<li>
										<div class="item-content">
											<div class="item-thumbnail">empty-thumbnail</div>
											<div class="item-title">
												<a href="">Post title</a>
											</div>
											<div class="item-snippet">Post snippet</div>
										</div>
										<div style="clear: both;"></div>
									</li>
								</ul>
								<div class="clear"></div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="widget Label" id="Label1">
							<h2>Categories</h2>
							<div class="widget-content list-label-widget-content">
								<ul>
									{if $categories} 
										{foreach $categories as $cat}
									<li><a href="?action=category&name={urlencode($cat.name)}">{$cat.name}</a></li>
										{/foreach}
									{else}
									<li>No categories.</li>
									{/if}
								</ul>
								<div class="clear"></div>
								<div class="clear"></div>
							</div>
						</div>
						{include file="bits/archives.tpl"}
					</div>
				</div>
				<!-- #primary .widget-area -->
				<div style="clear: both;"></div>
			</div>
			<!-- #forbottom -->
		</div>
		<!-- #main -->
		<div id="footer">
			<div id="colophon"></div>
			<!-- #colophon -->
			<div id="footer2">
				<div id="site-info">
					<a href="http://mantra-btemplates.blogspot.com/" rel="home"
						title="Mantra">Mantra</a> | <b title="Mantra 1.0">Mantra</b> Theme
					designed by <a href="http://www.cryoutcreations.com"
						target="_blank" title="Cryout Creations">Cryout Creations</a> |
					Modified by <a href="mailto:utherr.ghujax@gmail.com">Răzvan
						Botea</a>
				</div>
				<!-- #site-info -->
			</div>
		</div>
		<!-- #footer -->
	</div>
	<!-- #wrapper -->
</body>
</html>