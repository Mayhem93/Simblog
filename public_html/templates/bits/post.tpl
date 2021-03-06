<div id="post_{$post.id}" class="post-outer">
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
</div>