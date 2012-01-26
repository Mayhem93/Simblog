{include file="bits/post.tpl" post=$post}
<div id="comments">
	<h3 id="comments-title">
		{blog_getCommentsNumber($post.id)} Response(s) to <em>{$post.title}</em>
	</h3>
	<ol class="commentlist">
	{foreach $comments as $comment}
		<li id="comment_{$comment.id}" class="comment">
			<div>
				<div class="comment-author vcard">
					<div class="avatar-image-container vcard">
						
					</div>
					<cite class="fn"> {$comment.name}
					</cite> <span class="says">says:</span>
					{if smarty_isAdminSession()}
					<img id="delete_comment_{$comment.id}" class="comment-delete" src="img/delete-button.png" alt="Delete Comment"/>
					{/if}
					<span class="commentDate">{$comment.date}</span>
				</div>
				<div class="comment-meta commentmetadata">
					
				</div>
				<div class="comment-body">
					<p>
						{$comment.text}
					</p>
				</div>
			</div>
		</li>
	{/foreach}
	</ol>
	<div id="respond">
		<h3 id="reply-title">Leave a Reply</h3>
		<form name="commentForm" id="commentForm" action="" method="post">
			 <label for="commentName">Name: </label><input name="commentName" type="text" />
			 <textarea name="commentBody" rows="8" cols="60" id="commentBodyField"></textarea>
			 <input id="submitComment" type="submit" value="Comment" />
		</form>
	</div>
	<p></p>
	<div id="backlinks-container">
		<div id="Blog1_backlinks-container"></div>
	</div>
</div>