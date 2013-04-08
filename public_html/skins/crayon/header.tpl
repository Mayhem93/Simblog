<header class="header">
<div class="container navbar-wrapper">
	<nav class="navbar navbar-inverse" id="access" role="navigation">
		<div class="navbar-inner">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a><!-- .btn .btn-navbar -->
			<a class="brand" href="/">
				$blog.title
			</a><!-- .brand -->
			<div class="nav-collapse collapse">
				<ul class="nav pull-right">
					<li class="active">
						<a href="#">Home</a>
					</li><!-- .active -->
					<li>
						<a href="#about">About</a>
					</li>
					<li>
						<a href="#contact">Contact</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li>
								<a href="#">Action</a>
							</li>
							<li>
								<a href="#">Another action</a>
							</li>
							<li>
								<a href="#">Something else here</a>
							</li>
							<li class="divider">
								<!-- -->
							</li>
							<li class="nav-header">
								Nav header
							</li><!-- .nav-header -->
							<li>
								<a href="#">Separated link</a>
							</li>
							<li>
								<a href="#">One more separated link</a>
							</li>
						</ul><!-- .dropdown-menu -->
					</li><!-- .dropdown -->
				</ul><!-- .nav .pull-right -->
				<?php // <--- ?>
			</div><!-- .nav-collapse .collapse -->
		</div><!-- .navbar-inner -->
	</nav><!-- .navbar .navbar-inverse -->
</div><!-- .container .navbar-wrapper -->
<div id="featured-post-area" class="carousel slide">
	<div class="carousel-inner">
		<div class="item active"><?php // Atentie! Clasa 'active' apare doar la primul slide ?>
			<img alt="" src="{$skin->getSkinWWWuri()}/assets/img/slide-default.png">
			<div class="container">
				<div class="carousel-caption">
					<h1>$entry.title</h1>
					<p class="lead">
						$entry.excerpt
					</p><!-- .lead -->
					<a class="btn btn-large btn-primary" href="{ $entry.url }">
						Read More &rarr;
					</a><!-- .btn .btn-large .btn-primary -->
				</div><!-- .carousel-caption -->
			</div><!-- .container -->
		</div><!-- .item .active --><?php // Atentie! Clasa 'active' apare doar la primul slide ?>
		<?php // end for each ?>
	</div><!-- .carousel-inner -->
	<a class="left carousel-control" href="#featured-post-area" data-slide="prev">
		<img alt="" src="{$skin->getSkinWWWuri()}/assets/img/carousel-control-prev.png">
	</a>
	<a class="right carousel-control" href="#featured-post-area" data-slide="next">
		<img alt="" src="{$skin->getSkinWWWuri()}/assets/img/carousel-control-next.png">
	</a>
</div><!-- .carousel .slide #featured-post-area -->
</header><!-- .header -->

