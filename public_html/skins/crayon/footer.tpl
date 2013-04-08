{*			</div><!-- .row -->
			<hr>
			<footer class="footer">
				<aside class="row widget-area">
					<?php
					/*
					 * Secventa urmatoare trebuie generata automat. Unele
					 * widget-urile sunt implicite, altele trebuie sa poate fi
					 * adaugate de teme sau plugin-uri. Vezi
					 * http://codex.wordpress.org/Function_Reference/dynamic_sidebar
					 * pentru model. Ce widget-uri sunt afisate, cat si oridnea lor
					 * trebuie sa se poate personaliza din panoul de administrare.
					 *
					 * Exemplu: { $simblog.sidebar }
					 */
					?>
					<?php // ---> ?>
					<div class="span4 widget">
						<h3>
							Simblog
							<small>Blogging made simple</small>
						</h3>
						<p>Simblog aims to be one of the most easy-to-use and lightweight blogging platforms. It emphasizes user experience, workflow, and ease of use without neglecting usability and extensibility. It takes full advantage of what HTML5 and CSS3 have to offer without delving too deeply into experimental features.</p>
					</div><!-- .span4 .widget -->
					<div class="span4 widget">
						<h3>Twitter</h3>
						<blockquote>
							<p>
								<a href="#" rel="external nofollow">@johndoe</a>
								Glad you found something you liked! Thanks for the shout out John!
							</p>
							<small>
								<a href="#">1 day ago</a>
							</small>
						</blockquote>
						<blockquote>
							<p>
								<a href="#" rel="external nofollow">@richardroe</a>
								Glad you’re enjoying it Richard. Let us know if you need any help with it.
							</p>
							<small>
								<a href="#">2 days ago</a>
							</small>
						</blockquote>
					</div><!-- .span4 .widget -->
					<div class="span4 widget">
						<h3>Flickr</h3>
						<div class="row-fluid">
							<ul class="thumbnails">
								<li class="span3">
									<a class="thumbnail" href="#">
										<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
									</a><!-- .thumbnail -->
								</li><!-- .span3 -->
								<li class="span3">
									<a class="thumbnail" href="#">
										<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
									</a><!-- .thumbnail -->
								</li><!-- .span3 -->
								<li class="span3">
									<a class="thumbnail" href="#">
										<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
									</a><!-- .thumbnail -->
								</li><!-- .span3 -->
								<li class="span3">
									<a class="thumbnail" href="#">
										<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
									</a><!-- .thumbnail -->
								</li><!-- .span3 -->
							</ul><!-- .thumbnails -->
							<ul class="thumbnails">
								<li class="span3">
									<a class="thumbnail" href="#">
										<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
									</a><!-- .thumbnail -->
								</li><!-- .span3 -->
								<li class="span3">
									<a class="thumbnail" href="#">
										<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
									</a><!-- .thumbnail -->
								</li><!-- .span3 -->
								<li class="span3">
									<a class="thumbnail" href="#">
										<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
									</a><!-- .thumbnail -->
								</li><!-- .span3 -->
								<li class="span3">
									<a class="thumbnail" href="#">
										<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
									</a><!-- .thumbnail -->
								</li><!-- .span3 -->
							</ul><!-- .thumbnails -->
						</div><!-- .row-fluid -->
					</div><!-- .span4 .widget -->
					<?php // <--- ?>
				</aside><!-- .row .widget-area -->
				<hr>
				<div class="row">
					<div class="span12 page-meta">
						<p class="pull-right">
							<a href="#">Back to top</a>
						</p><!-- .pull-right -->
						<p>Designed and built with love by <a href="http://simblog.org/" rel="external nofollow">the Simblog team</a>.</p>
					</div><!-- .span12 .page-meta -->
				</div><!-- .row -->
			</footer><!-- .footer -->
		</div><!-- .container -->
		<?php
		/*
		 * Secventa urmatoare trebuie generata automat. Unele elemente sunt
		 * implicite, altele trebuie sa poate fi adaugate de tema activa sau
		 * de plugin-uri.
		 *
		 * Exemplu: { $simblog.footer }
		 */
		?>
		<?php // ---> ?>
		<script src="{ $template.uri }/assets/js/jquery.js"></script>
		<script src="{ $template.uri }/assets/js/google-code-prettify/prettify.js"></script>
		<script src="{ $template.uri }/assets/js/bootstrap.min.js"></script>
		<script>
			!function($) {
				$(function() {
					$('#featured-post-area').carousel()
				})
			} (window.jQuery)
		</script>
		<?php // <--- ?>
	</body>
</html>
*}
<footer class="footer">
	<aside class="row widget-area">
		<div class="span4 widget">
			<h3>
				Simblog
				<small>Blogging made simple</small>
			</h3>
			<p>Simblog aims to be one of the most easy-to-use and lightweight blogging platforms. It emphasizes user experience, workflow, and ease of use without neglecting usability and extensibility. It takes full advantage of what HTML5 and CSS3 have to offer without delving too deeply into experimental features.</p>
		</div><!-- .span4 .widget -->
		<div class="span4 widget">
			<h3>Twitter</h3>
			<blockquote>
				<p>
					<a href="#" rel="external nofollow">@johndoe</a>
					Glad you found something you liked! Thanks for the shout out John!
				</p>
				<small>
					<a href="#">1 day ago</a>
				</small>
			</blockquote>
			<blockquote>
				<p>
					<a href="#" rel="external nofollow">@richardroe</a>
					Glad you’re enjoying it Richard. Let us know if you need any help with it.
				</p>
				<small>
					<a href="#">2 days ago</a>
				</small>
			</blockquote>
		</div><!-- .span4 .widget -->
		<div class="span4 widget">
			<h3>Flickr</h3>
			<div class="row-fluid">
				<ul class="thumbnails">
					<li class="span3">
						<a class="thumbnail" href="#">
							<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
						</a><!-- .thumbnail -->
					</li><!-- .span3 -->
					<li class="span3">
						<a class="thumbnail" href="#">
							<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
						</a><!-- .thumbnail -->
					</li><!-- .span3 -->
					<li class="span3">
						<a class="thumbnail" href="#">
							<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
						</a><!-- .thumbnail -->
					</li><!-- .span3 -->
					<li class="span3">
						<a class="thumbnail" href="#">
							<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
						</a><!-- .thumbnail -->
					</li><!-- .span3 -->
				</ul><!-- .thumbnails -->
				<ul class="thumbnails">
					<li class="span3">
						<a class="thumbnail" href="#">
							<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
						</a><!-- .thumbnail -->
					</li><!-- .span3 -->
					<li class="span3">
						<a class="thumbnail" href="#">
							<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
						</a><!-- .thumbnail -->
					</li><!-- .span3 -->
					<li class="span3">
						<a class="thumbnail" href="#">
							<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
						</a><!-- .thumbnail -->
					</li><!-- .span3 -->
					<li class="span3">
						<a class="thumbnail" href="#">
							<img alt="" height="90" src="http://lorempixel.com/90/90/food?<?php echo uniqid(); ?>" width="90">
						</a><!-- .thumbnail -->
					</li><!-- .span3 -->
				</ul><!-- .thumbnails -->
			</div><!-- .row-fluid -->
		</div><!-- .span4 .widget -->
		<?php // <--- ?>
	</aside><!-- .row .widget-area -->
	<hr>
	<div class="row">
		<div class="span12 page-meta">
			<p class="pull-right">
				<a href="#">Back to top</a>
			</p><!-- .pull-right -->
			<p>Designed and built with love by <a href="http://simblog.org/" rel="external nofollow">the Simblog team</a>.</p>
		</div><!-- .span12 .page-meta -->
	</div><!-- .row -->
</footer><!-- .footer -->