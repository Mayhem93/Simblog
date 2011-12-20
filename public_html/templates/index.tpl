<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="main.css" />
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="main.js"></script>
<title>{$simblog_conf.blog_title}</title>
</head>
<body class="loading">
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
								<span> <a href="http://mantra-btemplates.blogspot.com/">{$simblog_conf.blog_title}</a>
								</span>
							</h1>
							<div id="site-description">Improving myself every second.
							</div>
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
											<li class="page_item"><a
												href="http://mantra-btemplates.blogspot.com/">Home</a></li>
											<li class="page_item"><a
												href="http://mantra-btemplates.blogspot.com/p/about.html">About</a></li>
											<li class="page_item"><a
												href="http://mantra-btemplates.blogspot.com/p/contact.html">Contact</a></li>
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
					<div id="content">
						<div class="section" id="main1">
							<div class="widget Blog" id="Blog1">
								<div class="blog-posts hfeed">
									<div class="date-outer">
										<div class="date-posts">
											
										</div>
									</div>
									<!-- google_ad_section_end -->
								</div>
								<div class="clear"></div>
								<div class="navigation" id="nav-below"></div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
					<!-- #content -->
				</div>
				<!-- #container -->
				<div class="widget-area" id="primary">
					<div class="widget-container widget_search" id="search">
						<form action="http://mantra-btemplates.blogspot.com/search/"
							id="searchform" method="get">
							<input id="s" name="q" type="text" value="Search" /> <input
								id="searchsubmit" type="submit" value="OK" />
						</form>
					</div>
					<div class="clear"></div>
					<div class="section" id="sidebar">
						<div class="widget HTML" id="HTML3">
							{if smarty_isAdminSession() }
								{include "bits/admin_box.tpl"}
							{else}
								{include "bits/admin_login.tpl"}
							{/if}
							<div class="clear"></div>
							<div class="clear"></div>
						</div>
						<div class="widget PopularPosts" id="PopularPosts1">
							<h2>Popular Posts</h2>
							<div class="widget-content popular-posts">
								<ul>
									<li>
										<div class="item-content">
											<div class="item-thumbnail">
												<a
													href="http://mantra-btemplates.blogspot.com/2011/08/lorem-ipsum_8310.html"
													target="_blank"> <img alt="" border="0" height="72"
													src="http://lh3.googleusercontent.com/_Zuzii37VUO4/Ta0nUeMwXoI/AAAAAAAAFoc/7f0Um7OTgNg/s72-c/Antartic-by-Peter-Rejcek.jpg"
													width="72" />
												</a>
											</div>
											<div class="item-title">
												<a
													href="http://mantra-btemplates.blogspot.com/2011/08/lorem-ipsum_8310.html">Lorem
													Ipsum</a>
											</div>
											<div class="item-snippet">Download this and more
												Blogger Templates at . For tutorials, tips and tricks about
												Blogger visit our Blog . &#187; A normal paragraph Ea eam
												lab...</div>
										</div>
										<div style="clear: both;"></div>
									</li>
								</ul>
								<div class="clear"></div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="widget HTML" id="HTML2">
							<h2 class="title">Blogger news</h2>
							<div class="clear"></div>
							<div class="clear"></div>
						</div>
						<div class="widget HTML" id="HTML1">
							<h2 class="title">Blogroll</h2>
							<div class="widget-content">
								<ul>
									<li><a href="http://btemplates.com"
										title="Blogger templates">BTemplates</a></li>
									<li><a href="http://blog.btemplates.com">BTemplates
											Blog</a></li>
									<li><a href="http://www.litethemes.com/">LiteThemes</a></li>
								</ul>
							</div>
							<div class="clear"></div>
							<div class="clear"></div>
						</div>
						<div class="widget Label" id="Label1">
							<h2>Categories</h2>
							<div class="widget-content list-label-widget-content">
								<ul>
									<li><a dir="ltr"
										href="http://mantra-btemplates.blogspot.com/search/label/Lorem%201"
										title="View all posts filed under Lorem 1">Lorem 1</a> <span
										dir="ltr">(3)</span></li>
									<li><a dir="ltr"
										href="http://mantra-btemplates.blogspot.com/search/label/Lorem%202"
										title="View all posts filed under Lorem 2">Lorem 2</a> <span
										dir="ltr">(2)</span></li>
									<li><a dir="ltr"
										href="http://mantra-btemplates.blogspot.com/search/label/Lorem%203"
										title="View all posts filed under Lorem 3">Lorem 3</a> <span
										dir="ltr">(3)</span></li>
								</ul>
								<div class="clear"></div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="widget BlogArchive" id="BlogArchive1">
							<h2>Archives</h2>
							<div class="widget-content">
								<div id="ArchiveList">
									<div id="BlogArchive1_ArchiveList">
										<ul class="hierarchy">
											<li class="archivedate expanded"><a class="toggle"
												href="javascript:void(0)"> <span
													class="zippy toggle-open">&#9660;&#160;</span>
											</a> <a class="post-count-link"
												href="http://mantra-btemplates.blogspot.com/search?updated-min=2011-01-01T00:00:00-08:00&updated-max=2012-01-01T00:00:00-08:00&max-results=4"
												title="2011">2011</a> <span class="post-count" dir="ltr">(4)</span>
												<ul class="hierarchy">
													<li class="archivedate expanded"><a class="toggle"
														href="javascript:void(0)"> <span
															class="zippy toggle-open">&#9660;&#160;</span>
													</a> <a class="post-count-link"
														href="http://mantra-btemplates.blogspot.com/2011_08_01_archive.html"
														title="August">August</a> <span class="post-count"
														dir="ltr">(4)</span>
														<ul class="posts">
															<li><a
																href="http://mantra-btemplates.blogspot.com/2011/08/lorem-ipsum_8310.html"
																title="Lorem Ipsum">Lorem Ipsum</a></li>
															<li><a
																href="http://mantra-btemplates.blogspot.com/2011/08/lorem-ipsum_29.html"
																title="Lorem Ipsum">Lorem Ipsum</a></li>
															<li><a
																href="http://mantra-btemplates.blogspot.com/2011/08/lorem-ipsum.html"
																title="Lorem Ipsum">Lorem Ipsum</a></li>
															<li><a
																href="http://mantra-btemplates.blogspot.com/2011/08/template-images.html"
																title="Template images">Template images</a></li>
														</ul></li>
												</ul></li>
										</ul>
									</div>
								</div>
								<div class="clear"></div>
								<div class="clear"></div>
							</div>
						</div>
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
						target="_blank" title="Cryout Creations">Cryout Creations</a> | Modified by <a href="mailto:utherr.ghujax@gmail.com">Răzvan Botea</a>
				</div>
				<!-- #site-info -->
			</div>
		</div>
		<!-- #footer -->
	</div>
	<!-- #wrapper -->
</body>
</html>