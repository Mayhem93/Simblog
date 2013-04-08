{ include file='header.tpl' }

<div class="span9" id="main" role="main">
	<?php
	/*
	 * Afisare articol/pagina curenta.
	 */
	?>
	<?php // ---> ?>
	<article class="row entry entry-excerpt">
		<div class="span3">
			<?php
			/*
			 * O tema trebuie sa poata specifica ce dimensiune foloseste pentru thumbnail-uri.
			 * O imagine va fi salvata si la aceasta dimensiune in momentul incarcarii. Fiecare
			 * tema decide daca afiseaza sau nu thumbnail-ul.
			 */
			?>
			<p class="entry-thumbnail">
				<a class="thumbnail" href="{ $entry.url }">
					<?php // Thumbnail-ul asociat unui articol sau thumbnail-ul implicit daca nu este setat nici unul ?>
					<img alt="{ $entry.title }" height="200" src="{ $template.uri }/assets/img/thumbnail-default.jpg" width="270">
					<?php
					/*
					 * Secventa urmatoare apare doar daca articolul este pinned.
					 */
					?>
					<?php // ---> ?>
					<span class="thumbnail-featured">
						<!-- -->
					</span><!-- .thumbnail-featured -->
					<?php // <--- ?>
				</a><!-- .thumbnail -->
			</p><!-- .entry-thumbnail -->
			<p class="entry-meta align-right">
				Posted on
				<a href="{ $entry.url }" title="{ $entry.time }" rel="bookmark">
					<time class="entry-date" datetime="{ $entry.date('c') }">
						{ $entry.date }
					</time><!-- .entry-date -->
				</a>
				<i class="icon-time">
					<!-- -->
				</i><!-- .icon-time -->
				<br>
				In
				<a href="{ $category.url }" rel="category" title="Viewl all posts in { $entry.category }">{ $entry.category }</a><?php // Trebuie enumerate toate cu virgula intre ele ?>
				<i class="icon-inbox">
					<!-- -->
				</i><!-- .icon-inbox -->
				<br>
				Tagged
				<a href="{ $tag.url }" rel="tag">{ $entry.tag }</a><?php // Trebuie enumerate toate cu virgula intre ele ?>
				<i class="icon-tags">
					<!-- -->
				</i><!-- .icon-tags -->
				<br>
				By
				<a class="url fn n" href="{ $author.url }" title="View all posts by { $entry.author }" rel="author">
					{ $entry.author }
				</a><!-- .url .fn .n -->
				<i class="icon-user">
					<!-- -->
				</i><!-- .icon-user -->
				<br>
				With
				<a href="{ $entry.url#reply }" title="Comment on { $entry.title }"><strong>{ $entry.comments_count }</strong> Comments</a>
				<i class="icon-comment">
					<!-- -->
				</i><!-- .icon-comment -->
				<br>
				Bookmark the
				<a href="{ $entry.url }" rel="bookmark" title="Permalink to { $entry.title }">Permalink</a>
				<i class="icon-share-alt">
					<!-- -->
				</i><!-- .icon-share-alt -->
			</p><!-- .entry-meta .align-right -->
		</div><!-- .span3 -->
		<div class="span6">
			<header class="entry-header">
				<h1 class="entry-title">
					<a href="{ $entry.url }" title="Permalink to { $entry.title }!" rel="bookmark">{ $entry.title }</a>
				</h1><!-- .entry-title -->
				<p class="entry-meta">
					<span class="posted-on">
						Posted on
						<a href="{ $entry.url }" title="{ $entry.time }" rel="bookmark">
							<time class="entry-date" datetime="{ $entry.date('c') }">
								{ $entry.date }
							</time><!-- .entry-date -->
						</a>
						by
						<a class="url fn n" href="{ $author.url }" rel="author" title="View all posts by { $entry.author }">
							{ $entry.author }
						</a><!-- .url .fn .n -->
					</span><!-- .posted-on -->
				</p><!-- .entry-meta -->
			</header><!-- .entry-header -->
			<div class="entry-content">
				{ $entry.excerpt }
				<p>
					<a class="btn btn-primary" href="{ $entry.url }" rel="bookmark">
						Read More &rarr;
					</a><!-- .btn .btn-primary -->
				</p>
			</div><!-- .entry-content -->
			<footer class="entry-meta">
				<p>
					Posted in
					<a href="{ $category.url }" rel="category" title="Viewl all posts in { $entry.category }">{ $entry.categories }</a><?php // Trebuie enumerate toate cu virgula intre ele ?>
					and tagged
					<a href="{ $tag.url }" rel="tag">{ $entry.tag }</a><?php // Trebuie enumerate toate cu virgula intre ele ?>
					|
					<a href="{ $entry.url }#reply" title="Comment on { $entry.title }"><strong>{ $entry.comments_count }</strong> Comments</a>
				</p>
			</footer><!-- .entry-meta -->
		</div><!-- .span6 -->
	</article><!-- .row .entry .entry-excerpt -->
	<?php // <--- ?>
	<?php
	/*
	 * TODO: Afisare comentarii.
	 */
	?>
</div><!-- .span9 #main -->

{ include file='sidebar.tpl' }
{ include file='footer.tpl' }
