<li id="comment_{$comment.id}" class="comment">
	<div>
		<div class="comment-author vcard">
			<div class="avatar-image-container vcard"></div>
			<cite class="fn"> {$comment.name} </cite> <span class="says">says:</span>
			{if smarty_isAdminSession()} <img id="delete_comment_{$comment.id}"
				class="comment-delete" src="img/delete-button.png"
				alt="Delete Comment" /> 
			{/if} <span class="commentDate">{$comment.date}</span>
		</div>
		<div class="comment-meta commentmetadata"></div>
		<div class="comment-body">
			<p>{$comment.text}</p>
		</div>
	</div>
</li>