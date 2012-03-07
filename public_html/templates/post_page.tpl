{include file="bits/post.tpl" post=$post}
<div id="comments">
	<h3 id="comments-title">
		{blog_getCommentsNumber($post.id)} Response(s) to <em>{$post.title}</em>
	</h3>
	<ol class="commentlist">
	{foreach $comments as $comment}
		{include file="bits/comment.tpl"}
	{/foreach}
	</ol>
	<div id="respond">
		<h3 id="reply-title">Leave a Reply</h3>
		<form name="commentForm" id="commentForm" action="" method="post">
			 <label for="commentName">Name: </label><input name="commentName" type="text" />
			 <textarea name="commentBody" rows="8" cols="60" id="commentBodyField"></textarea>
			 <input id="submitComment" type="button" value="Comment" />
		</form>
	</div>
	<p></p>
	<div id="backlinks-container">
		<div id="Blog1_backlinks-container"></div>
	</div>
</div>