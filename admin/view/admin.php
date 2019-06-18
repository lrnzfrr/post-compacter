
<div class="notice notice-info">
<p><?php _e( 'Posts compacter will insert posts content in page / post selected', 'post-compacter' ); ?></p>
</div>

<div >

    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <!-- main content -->
            <div id="post-body-content">

                <div class="meta-box-sortables ui-sortable">

                    <div class="postbox">
                    <?php if(!$_POST):
                    include 'form.php';
                    endif; ?>
                        
                    </div> <!-- .postbox -->

                </div> <!-- .meta-box-sortables .ui-sortable -->

            </div> <!-- post-body-content -->

            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">

                <div class="meta-box-sortables">

                    <div class="postbox hcfw-postbox hcfw-copyright">

                        <h3 style="color:red"><span><?php _e( 'Warning', 'post-compacter' ); ?></span></h3>
                        <div class="inside">
                           <p>
                           <?php _e( 'Posts will be deleted', 'post-compacter' ); ?></b>
                           </p>
                        </div> <!-- .inside -->

                    </div> <!-- .postbox -->

                </div> <!-- .meta-box-sortables -->

            </div> <!-- #postbox-container-1 .postbox-container -->

        </div> <!-- #post-body .metabox-holder .columns-2 -->

        <br class="clear">
    </div> <!-- #poststuff -->

</div> <!-- .wrap -->