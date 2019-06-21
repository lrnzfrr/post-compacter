<h2>Redirects</h2>
<form action="" method="post">
<input type="hidden" value="1" name="add_redirect" />
<table class="form-table" style="width:70%">
            <tbody>
            <tr>
                    <th scope="row"><?php _e( 'Redirect From', 'post-compacter' ); ?></th>
                    <td>
                        <fieldset>
                    <input name="post_compacter_redirect_from"  placeholder="es /news/myarticle.html" />
                        </fieldset>
                    </td>
                    <th scope="row"><?php _e( 'Redirect To', 'post-compacter' ); ?></th>
                    <td>
                        <fieldset>
                        <input name="post_compacter_redirect_to"  placeholder="es https://mysite.com/post_compacted.html" />
                        </fieldset>
                    </td>
                    <td>
                    <input class="button-primary" type="submit" name="submit" value="<?php _e( 'Add Redirect', 'post-compacter' ); ?>" />
                    </td>
                </tr>
            </tbody>
        </table>
     
</form>

<?php if(empty($result)): ?>
<?php _e( 'No Redirects founds', 'post-compacter' ); ?>
<?php else : ?>
<table class="wp-list-table widefat fixed striped ">
    <thead>
    <tr>
        <td>Url</td>
        <td>Redirect</td>
        <td></td>
    </tr>
    </thead>
    <?php foreach($result as $redirect): ?>
    <tr>
        <td><?php echo $redirect->old_url; ?></td>
        <td><?php echo $redirect->new_url; ?></td>
        <td><?php echo $redirect->created; ?></td>
        <td>
        <form action="" method="post"><input type="hidden" value="<?php echo $redirect->id; ?>" name="delete_redirect">
        <input class="button-primary" type="submit" name="submit" value="<?php _e( 'Delete Redirect', 'post-compacter' ); ?>"
         onclick="return confirm('<?php _e( 'Delete Redirect?', 'post-compacter' ); ?>')" />
         </form>
    </tr>
    <?php endforeach; ?> 
</table>   
<?php endif ;?>
