{*<div id="post_{$post.id}" class="post-outer">
	<div class="post-hentry">
		<h2 class="entry-title">
			<a href="?action=post&id={$post.id}">{$post.title}</a>
		</h2>
		{if smarty_isAdminSession() }
		<div class="admin_buttons">
			<img class="post-delete" src="img/close-button.png" alt="Delete" />
			<a href="?action=modifyPost&id={$post.id}" title="Modify Post"><img class="post-modify" src="img/edit.png" alt="Modify post" /></a>
		</div>
		{/if}
		<div class="entry-meta">
			<span class="author vcard">Category: 
			{if $post.category == ""}no category
			{else}<a href="?action=category&name={urlencode($post.category)}">{$post.category}</a>
			{/if}
			| </span> <span
				class="onDate">{$post.date_posted}</span> <span class="comments-link"><a href="?action=post&id={$post.id}#comments-title">{blog_getCommentsNumber({$post.id})} Comment(s)</a></span>
		</div>
		<div class="entry-content">{$post.content|prepare }</div>
	</div>
</div>*}

<article class="post-entry" id="post_{$post.id}" data-postid="{$post.id}">
    <h2 title="Heading"><a href="?action=post&id={$post.id}" title="{$post.title}">{$post.title}</a></h2>
    <header class="postMetadata">
        <span>Published: <time class="label" pubdate datetime="{date('Y-m-d\Th:iP', $post.utime)}">{date('F jS, Y', $post.utime)}</time></span>

        <a class="comments-count" href="#"><span class="badge badge-info">2</span>
            Comment(s)</a>
        <p class="categories">Categories:
        {if $post.category == ""}no category
        {else}
            <span class="label label-info">{$post.category}</span>
        {/if}
        </p>
    </header>
    <div class="postBody">
        {$post.content|prepare }
    </div>
    <footer>
        <span class="tags">Tags:
        {foreach $post.tags as $tag}
            <a href="#">$tag</a>
        {/foreach}
			<button class="btn btn-primary reply-button">Leave a reply</button>
    </footer>
</article>