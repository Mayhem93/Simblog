{*<li id="comment_{$comment.id}" class="comment">
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
</li>*}

<div class="row comment" data-id="{$comment.id}">
    <header class="row">
        <img class="commentAvatar" src="http://0.gravatar.com/avatar/6dcff53ff6b67932d8f9c76171aa87c8?s=80&d=http%3A%2F%2F0.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D40&r=G" alt="avatar">
        <h4>{$comment.name} says:</h4>
        <time class="label" pubdate datetime="2012-06-30T07:13+02:00">June 30, 2012 @ 07:13</time>
    </header>
    <div class="row commentContent">
		{$comment.content}
    </div>
</div>