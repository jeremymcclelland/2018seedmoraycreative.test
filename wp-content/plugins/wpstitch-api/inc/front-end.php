<?php 		

	echo $before_widget;
	echo $before_title . $title . $after_title;	

?>

<ul class="wpstitch-badges frontend">

	<?php 

		$total_badges = count( $stitch_widget_data->{'badges'} );

		for( $i = $total_badges - 1; $i >= $total_badges - $num_badges; $i-- ):		

	;?>

	<li class="wpstitch-badge">

		<img src="<?php echo $stitch_widget_data->{'badges'}[$i]->{'icon_url'}; ?>">		


		<?php if( $display_tooltips == '1' ): ?>


			<div class="wpstitch-badge-info">
																		
				<p class="wpstitch-badge-name">			
					<a href="<?php echo $stitch_widget_data->{'badges'}[$i]->{'url'};; ?>">
						<?php echo $stitch_widget_data->{'badges'}[$i]->{'name'}; ?>
					</a>								
				</p>							

							
				<?php if ( $stitch_widget_data->{'badges'}[$i]->{'courses'}[0]->{'title'} != '' ): ?>
				
				<p class="wpstitch-badge-project">
					<a href="<?php echo $stitch_widget_data->{'badges'}[$i]->{'courses'}[0]->{'url'}; ?>">
						<?php echo $stitch_widget_data->{'badges'}[$i]->{'courses'}[0]->{'title'} ;?>
					</a>
				</p>
				<?php endif; ?>

				<a href="http://teamtreehouse.com" alt="Team Treehouse | A Better Way to Learn Technology" class="wpstitch-logo">
					<img src="<?php echo plugins_url( 'wpstitch-api/images/treehouse-logo.png' ); ?>" alt="Treehouse" />
				</a>
					
				<span class="wpstitch-tooltip bottom"></span>							

			</div>

		<?php endif; ?>


	</li>


	<?php endfor; ?>

</ul>


<?php
	echo $after_widget; 

?>