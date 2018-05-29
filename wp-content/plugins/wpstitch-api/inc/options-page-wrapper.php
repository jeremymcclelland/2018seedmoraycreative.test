<h2><?php _e( 'Stitch Boiler Plate API Plugin', 'WpAdminStyle' ); ?></h2>

<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1><?php esc_attr_e( 'Stitch API JSON Feed', 'WpAdminStyle' ); ?></h1>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">


					<?php if(!isset( $wpstitch_username ) || $wpstitch_username == '' ) : ?>


					<div class="postbox">

						<h2><span><?php esc_attr_e( 'API Credentials', 'WpAdminStyle' ); ?></span></h2>

						<div class="inside">

							<form name="wpstitch_username_form" method="post" action="">
								<input type="hidden" name="wpstitch_form_submitted" value="Y">

							<table class="form-table">
								<tr>
									<td>
										<label for="wpstitch_username">API User Name</label>
									</td>
									<td>
										<input name="wpstitch_username" id="wpstitch_username" type="text" value="" class="regular-text" />
									</td>
								</tr>
								
							</table>

							<p><input class="button-primary" type="submit" name="wpstitch_username_submit" value="Save" /></p>
						</form>

						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				<?php else : ?>



					<div class="postbox">



						<h2><span>Most Recent Badges</span></h2>

						<div class="inside">
							<p>Below are 20 most recent badges</p>

							<ul class="wpstitch-badges">

							<?php 

							$total_badges = count( $wpstitch_data->{'badges'} );

							for( $i = $total_badges - 1; $i >= $total_badges - 20; $i-- ):		

							?>

							<li>
								<ul>
									<li>
										<!-- <img width="120px" src="<?php //echo $plugin_url . '/images/wp-badge.png'; ?>">	 -->
										<img width="120px" src="<?php echo $wpstitch_data->{'badges'}[$i]->{'icon_url'}; ?>">								
									</li>	
									<?php if($wpstitch_data->{'badges'}[$i]->{'url'} != $wpstitch_data->{'profile_url'} ) : ?>

									<li class="wpstitch-badge-name">
										<a href="<?php echo $wpstitch_data->{'badges'}[$i]->{'url'}; ?>">
											<?php echo $wpstitch_data->{'badges'}[$i]->{'name'}; ?>	
										</a>
									</li>
									<li class="wpstitch-project-name">
										<a href="<?php echo $wpstitch_data->{'badges'}[$i]->{'url'}; ?>">
											<?php echo $wpstitch_data->{'badges'}[$i]->{'courses'}[0]->{title}; ?>
										</a>
									</li>

								<?php else: ?>
									<li class="wpstitch-badge-name">
										<?php echo $wpstitch_data->{'badges'}[$i]->{'name'}; ?>
									</li>
								<?php endif; ?>

								</ul>									
							</li>								
							<?php endfor; ?>

						</ul>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->
					<?php if($display_json == true): ?>

					<div class="postbox">

						<h2><span>JSON Feed</span></h2>

						<div class="inside">

							<p><?php echo $wpstitch_data->{'name'}; ?></p>
							<p><?php echo $wpstitch_data->{'badges'}[0]->{'name'}; ?></p>
							<p><?php echo $wpstitch_data->{'badges'}[1]->{'courses'}[1]->{title}; ?></p>

							<pre><code>
								<?php var_dump($wpstitch_data); ?>
							</code></pre>

						</div>
						<!-- .inside -->
					</div>
				<?php endif; ?>
					<!-- .postbox -->
				<?php endif; ?>

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<?php if(isset( $wpstitch_username ) && $wpstitch_username != '' ) : ?>

					<div class="postbox">

						<h3><span><?php echo $wpstitch_data->{'name'}; ?>'s Profile</span></h3>
						<div class="inside">
							
							<p><img width="100%" class="wpstitch-gravatar" src="<?php echo $wpstitch_data->{'gravatar_url'}; ?>" alt="Mike the Frog Gravatar"></p>

							<ul class="wpstitch-badges-and-points">							

								<li>Badges: <strong><?php echo count($wpstitch_data->{'badges'}); ?></strong></li>
								<li>Points: <strong><?php echo $wpstitch_data->{'points'}->{'total'}; ?></strong></li>

							</ul>
							<br>
							<form name="wpstitch_username_form" method="post" action="">
									<input type="hidden" name="wpstitch_form_submitted" value="Y">

									<p>
										<label for="wpstitch_username">API User Name</label>
									</p>
									<p>
									<input name="wpstitch_username" id="wpstitch_username" type="text" value="<?php echo $wpstitch_username; ?>" class="" />
									</p>

								<p><input class="button-primary" type="submit" name="wpstitch_username_submit" value="Update" /></p>
							</form>

						</div>

						
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				<?php endif; ?>

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->