<form action="" method="POST">
<input type="hidden" value="insert_in_page" name="post_compacter_action" />
							<h2><span><?php _e( 'Post Compacter', 'post-compacter' ); ?></span></h2>
							<div class="inside">


								<table class="form-table">
									<tbody>
									<tr>
											<th scope="row"><?php _e( 'Post / Page ID', 'post-compacter' ); ?></th>
											<td>
												<fieldset>
						 <input name="post_compacter_page_id"  placeholder="post / page id" />
												</fieldset>
											</td>
										</tr>                                   
										<tr>
											<th scope="row"><?php _e( 'Post Ids (one for row)', 'post-compacter' ); ?></th>
											<td>
												<fieldset>
														<textarea cols=50 rows=10 name="post_compacter_ids" ></textarea>
												</fieldset>
											</td>
										</tr>
									</tbody>
								</table>
								<p>
									<input class="button-primary" type="submit" name="submit" value="<?php _e( 'Compact', 'post-compacter' ); ?>" />
								</p>

							</div> <!-- .inside -->
						</form>
