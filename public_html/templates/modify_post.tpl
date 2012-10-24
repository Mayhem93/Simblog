<form name="addPost" class="well form-horizontal" action="?action=modifypost" method="POST">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="postTitle">Title: </label>
			<div class="controls">
				<input name="title" type="text" class="input-xxlarge" id="postTitle" value="{$post.title}">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="postCategory">Category: </label>
			<div class="controls">
				<select {if !$categories}disabled{/if} id="postCategory" name="category">
				{if $categories}
					{foreach $categories as $cat}
						<option value="{$cat.name}">{$cat.name}</option>
					{/foreach}
				{/if}
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="postTags">Tags: </label>
			<div class="controls">
				<input class="input-xxlarge" id="postTags" name="tags">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="postPinned">Pinned</label>
			<div class="controls">
				<input name="pinned" id="postPinned" type="checkbox">
			</div>
		</div>
		<textarea id="postContent" name="content"></textarea>
	</fieldset>
</form>