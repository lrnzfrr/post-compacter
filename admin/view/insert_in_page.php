<div><h2><?php _e( 'Redirects Generated', 'post-compacter' ); ?></h2>
<textarea cols=200 rows=<?php echo count($redirects) +1; ?> readonly="readonly">
<?php echo implode("\n",$redirects); ?>
</textarea>
</p>

<p>
<?php _e( 'Page View', 'post-compacter' ); ?>
<a href="<?php echo $mainRedirect; ?>" target="_blank"><?php echo $mainRedirect; ?></a>

<form action="" method="POST">
<input type="hidden" value="delete_posts" name="post_compacter_action" />
<input type="hidden" value="<?php echo $_POST['post_compacter_ids']; ?>" name="post_compacter_ids" />
<input class="button-primary" type="submit" name="submit" value="Eliminare i Posts" />
</form>
</p>
</div>