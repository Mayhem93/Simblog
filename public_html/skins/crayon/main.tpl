<div class="span9" id="main" role="main">
	{foreach $blog_posts as $post}
		<article class="row entry entry-excerpt">
			<div class="span3">
				<p class="entry-thumbnail">
					<a class="thumbnail" href="/index.php?post={$post.id}">
						<img alt="{$post.title}" height="200" src="{$skin->getSkinWWWuri()}/assets/img/thumbnail-default.jpg" width="270">
					{if $post.pinned == 1}
						<span class="thumbnail-featured">
						<!-- -->
						</span><!-- .thumbnail-featured -->
					{/if}
					</a><!-- .thumbnail -->
				</p><!-- .entry-thumbnail -->
			</div><!-- .span3 -->
			<div class="span6">
				<header class="entry-header">
					<h1 class="entry-title">
						<a href="/index.php?post={$post.id}" title="Permalink to {$post.title}!" rel="bookmark">{$post.title}</a>
					</h1><!-- .entry-title -->
					<p class="entry-meta">
					<span class="posted-on">
						Posted on
						<a href="#" title="{date('c',$post.utime)}" rel="bookmark">
							<time class="entry-date" datetime="{date('c',$post.utime)}">
								{date('l jS \of F Y h:i:s A', $post.utime)}
							</time><!-- .entry-date -->
						</a>
						by
						<a class="url fn n" href="$author.url" rel="author" title="View all posts by $entry.author">
							$entry.author
						</a><!-- .url .fn .n -->
					</span><!-- .posted-on -->
					</p><!-- .entry-meta -->
				</header><!-- .entry-header -->
				<div class="entry-content">
					<p>
						{$post.content}
					</p>
				</div><!-- .entry-content -->
				<footer class="entry-meta">
					<p>
						Posted in
						<a href="/index.php?category={$post.category}" rel="category" title="Viewl all posts in {$post.category}">{$post.category}</a>
						and tagged
						{foreach $post.tags as $tag}
							<a href="/" rel="tag">{$tag}</a>,
						{/foreach}
						|
						<a href="/index.php?post={$post.id}#reply" title="Comment on {$post.title}"><strong>-1</strong> Comments</a>
					</p>
				</footer><!-- .entry-meta -->
			</div><!-- .span6 -->
		</article><!-- .row .entry .entry-excerpt -->
		<hr class="visible-phone">
	{/foreach}
	<div class="row">
		<nav class="span9">
			<ul class="pager">
				<li class="previous disabled"><?php // Clasa 'disabled' apare doar daca nu exista pagina anterioara ?>
					<a href="/index.php?page={$page-1}">&larr; Older</a>
				</li><!-- .previous .disabled --><?php // Clasa 'disabled' apare doar daca nu exista pagina anterioara ?>
				<li class="next disabled"><?php // Clasa 'disabled' apare doar daca nu exista pagina urmatoare ?>
					<a href="/index.php?page={$page+1}">Newer &rarr;</a>
				</li><!-- .next .disabled --><?php // Clasa 'disabled' apare doar daca nu exista pagina urmatoare ?>
			</ul><!-- .pager -->
		</nav><!-- .span9 -->
	</div><!-- .row -->
</div><!-- .span9 #main -->