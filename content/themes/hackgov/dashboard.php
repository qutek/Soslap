<?php
/**
 * The template for displaying all dashboard user
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package hackgov
 */

get_header(); 
$user_id = get_current_user_id();
$user = get_userdata( $user_id );
?>

	<div id="body">
		<div id="user-profile-box" class="page-section">
			<div class="container">
				<div class="avatar-box center">
					<div class="img-box">
						<?php echo get_avatar( get_current_user_id(), 512 ); ?>
					</div>
					<div class="user-title-box">
						<h2><?php echo $user->display_name; ?></h2>
						<p><?php echo get_user_meta( $user_id, 'address', true ); ?></p>
					</div>
				</div>
			</div>
			<div class="menu-box">
				<ul class="user-menu-box" role="tablist">
					<li class="active"><a href="#report"><span class="total-number"><?php echo get_laporan_count(); ?></span><span class="menu-text">Laporan</span></a></li>
					<li><a href="#approved"><span class="total-number"><?php echo get_laporan_count('publish'); ?></span><span class="menu-text">Diterima</span></a></li>
					<li><a href="#pending"><span class="total-number"><?php echo get_laporan_count('pending'); ?></span><span class="menu-text">Menunggu</span></a></li>
					<li><a href="#ongoing"><span class="total-number"><?php echo get_laporan_count('on-going'); ?></span><span class="menu-text">On Going</span></a></li>
					<li><a href="#resolved"><span class="total-number"><?php echo get_laporan_count('resolved'); ?></span><span class="menu-text">Resolved</span></a></li>
				</ul>
			</div>
			<div class="container">
				<div class="row tab-content">
					<div class="btn-box"><a href="<?php echo home_url( 'submit' ); ?>" class="btn btn-primary btn-big fluid btn-compose"><span class="icon-compose"></span>Buat Laporan</a></div>
					<div id="report" class="report-list-box tab-pane active" role="tabpanel">
						
						<div class="report-list">
							<?php  
							$laporan = get_laporan(); 

							if(!empty($laporan)){
								foreach ($laporan as $key => $laporan) {
							?>
							<div class="report-item">
								<div class="edit-box">
									<a href="<?php echo add_query_arg( array('id' => $laporan->ID), home_url( 'edit' ) ); ?>" class="btn-overlay"><span class="icon-compose"></span></a>
								</div>
								<a href="<?php echo get_permalink( $laporan->ID ); ?>">
									<div class="row">
										<div class="col-md-4">
											<div class="img-post">
												<div class="result-box">
													<span class="icon-sad"></span>
												</div>
												<?php echo get_the_post_thumbnail( $laporan->ID ); ?>
											</div>
										</div>
										<div class="col-md-8">
											<div class="report-content">
												<h4><?php echo get_the_title( $laporan->ID ) . get_status_icon($laporan->ID); ?></h4>
												<p class="location-box"><?php get_post_meta( $laporan->ID, '_location', true ); ?></p>
												<p class="description-box">
													<span class="masalah-box"><?php echo $laporan->post_content; ?></span>
												</p>
												<ul class="meta-box">
													<li><span class="meta-text priority <?php echo hackgov_get_priority($laporan->ID); ?>"></span></li>
													<li><span class="icon-<?php echo hackgov_get_category($laporan->ID); ?>"></span></li>
													<?php get_votes($laporan->ID); ?>
													<!-- <li><span class="icon-thumb-up"></span><span class="vote-text">15</span></li>
													<li><span class="icon-thumb-down"></span><span class="vote-text">3</span></li> -->
													<li class="pull-right"><span class="shared-text"><?php echo get_share_count($laporan->ID); ?> Shared</span></li>
												</ul>
											</div>
										</div>
									</div>
								</a>
							</div>
							
							<?php
								}
							} 
							?>
						</div>
					</div>
					<div id="approved" class="report-list-box tab-pane" role="tabpanel">
						
						<div class="report-list">
							<?php  
							$laporan = get_laporan(get_current_user_id(), 'publish'); 
							// echo "<pre>";
							// print_r($laporan);
							// echo "</pre>";

							if(!empty($laporan)){
								foreach ($laporan as $key => $laporan) {
							?>
							<div class="report-item">
								<div class="edit-box">
									<a href="<?php echo add_query_arg( array('id' => $laporan->ID), home_url( 'edit' ) ); ?>" class="btn-overlay"><span class="icon-compose"></span></a>
								</div>
								<a href="<?php echo get_permalink( $laporan->ID ); ?>">
									<div class="row">
										<div class="col-md-4">
											<div class="img-post">
												<div class="result-box">
													<span class="icon-sad"></span>
												</div>
												<?php echo get_the_post_thumbnail( $laporan->ID ); ?>
											</div>
										</div>
										<div class="col-md-8">
											<div class="report-content">
												<h4><?php echo get_the_title( $laporan->ID ) . get_status_icon($laporan->ID); ?></h4>
												<p class="location-box"><?php get_post_meta( $laporan->ID, '_location', true ); ?></p>
												<p class="description-box">
													<span class="masalah-box"><?php echo $laporan->post_content; ?></span>
												</p>
												<ul class="meta-box">
													<li><span class="meta-text priority <?php echo hackgov_get_priority($laporan->ID); ?>"></span></li>
													<li><span class="icon-<?php echo hackgov_get_category($laporan->ID); ?>"></span></li>
													<?php get_votes($laporan->ID); ?>
													<!-- <li><span class="icon-thumb-up"></span><span class="vote-text">15</span></li>
													<li><span class="icon-thumb-down"></span><span class="vote-text">3</span></li> -->
													<li class="pull-right"><span class="shared-text"><?php echo get_share_count($laporan->ID); ?> Shared</span></li>
												</ul>
											</div>
										</div>
									</div>
								</a>
							</div>
							
							<?php
								}
							} 
							?>
						</div>
					</div>
					<div id="pending" class="tab-pane" class="report-list-box tab-pane" role="tabpanel">
						
						<div class="report-list">
							<?php  
							$laporan = get_laporan(get_current_user_id(), 'pending'); 

							if(!empty($laporan)){
								foreach ($laporan as $key => $laporan) {
							?>
							<div class="report-item">
								<div class="edit-box">
									<a href="<?php echo add_query_arg( array('id' => $laporan->ID), home_url( 'edit' ) ); ?>" class="btn-overlay"><span class="icon-compose"></span></a>
								</div>
								<a href="<?php echo get_permalink( $laporan->ID ); ?>">
									<div class="row">
										<div class="col-md-4">
											<div class="img-post">
												<div class="result-box">
													<span class="icon-sad"></span>
												</div>
												<?php echo get_the_post_thumbnail( $laporan->ID ); ?>
											</div>
										</div>
										<div class="col-md-8">
											<div class="report-content">
												<h4><?php echo get_the_title( $laporan->ID ) . get_status_icon($laporan->ID); ?></h4>
												<p class="location-box"><?php get_post_meta( $laporan->ID, '_location', true ); ?></p>
												<p class="description-box">
													<span class="masalah-box"><?php echo $laporan->post_content; ?></span>
												</p>
												<ul class="meta-box">
													<li><span class="meta-text priority <?php echo hackgov_get_priority($laporan->ID); ?>"></span></li>
													<li><span class="icon-<?php echo hackgov_get_category($laporan->ID); ?>"></span></li>
													<?php get_votes($laporan->ID); ?>
													<!-- <li><span class="icon-thumb-up"></span><span class="vote-text">15</span></li>
													<li><span class="icon-thumb-down"></span><span class="vote-text">3</span></li> -->
													<li class="pull-right"><span class="shared-text"><?php echo get_share_count($laporan->ID); ?> Shared</span></li>
												</ul>
											</div>
										</div>
									</div>
								</a>
							</div>
							
							<?php
								}
							} 
							?>
						</div>
					</div>
					<div id="ongoing" class="tab-pane" class="report-list-box tab-pane" role="tabpanel">
						
						<div class="report-list">
							<?php  
							$laporan = get_laporan(get_current_user_id(), 'on-going'); 

							if(!empty($laporan)){
								foreach ($laporan as $key => $laporan) {
							?>
							<div class="report-item">
								<div class="edit-box">
									<a href="<?php echo add_query_arg( array('id' => $laporan->ID), home_url( 'edit' ) ); ?>" class="btn-overlay"><span class="icon-compose"></span></a>
								</div>
								<a href="<?php echo get_permalink( $laporan->ID ); ?>">
									<div class="row">
										<div class="col-md-4">
											<div class="img-post">
												<div class="result-box">
													<span class="icon-sad"></span>
												</div>
												<?php echo get_the_post_thumbnail( $laporan->ID ); ?>
											</div>
										</div>
										<div class="col-md-8">
											<div class="report-content">
												<h4><?php echo get_the_title( $laporan->ID ) . get_status_icon($laporan->ID); ?></h4>
												<p class="location-box"><?php get_post_meta( $laporan->ID, '_location', true ); ?></p>
												<p class="description-box">
													<span class="masalah-box"><?php echo $laporan->post_content; ?></span>
												</p>
												<ul class="meta-box">
													<li><span class="meta-text priority <?php echo hackgov_get_priority($laporan->ID); ?>"></span></li>
													<li><span class="icon-<?php echo hackgov_get_category($laporan->ID); ?>"></span></li>
													<?php get_votes($laporan->ID); ?>
													<!-- <li><span class="icon-thumb-up"></span><span class="vote-text">15</span></li>
													<li><span class="icon-thumb-down"></span><span class="vote-text">3</span></li> -->
													<li class="pull-right"><span class="shared-text"><?php echo get_share_count($laporan->ID); ?> Shared</span></li>
												</ul>
											</div>
										</div>
									</div>
								</a>
							</div>
							
							<?php
								}
							} 
							?>
						</div>
					</div>
					<div id="resolved" class="tab-pane" class="report-list-box tab-pane" role="tabpanel">
						
						<div class="report-list">
							<?php  
							$laporan = get_laporan(get_current_user_id(), 'resolved'); 

							if(!empty($laporan)){
								foreach ($laporan as $key => $laporan) {
							?>
							<div class="report-item">
								<div class="edit-box">
									<a href="<?php echo add_query_arg( array('id' => $laporan->ID), home_url( 'edit' ) ); ?>" class="btn-overlay"><span class="icon-compose"></span></a>
								</div>
								<a href="<?php echo get_permalink( $laporan->ID ); ?>">
									<div class="row">
										<div class="col-md-4">
											<div class="img-post">
												<div class="result-box">
													<span class="icon-sad"></span>
												</div>
												<?php echo get_the_post_thumbnail( $laporan->ID ); ?>
											</div>
										</div>
										<div class="col-md-8">
											<div class="report-content">
												<h4><?php echo get_the_title( $laporan->ID ) . get_status_icon($laporan->ID); ?></h4>
												<p class="location-box"><?php get_post_meta( $laporan->ID, '_location', true ); ?></p>
												<p class="description-box">
													<span class="masalah-box"><?php echo $laporan->post_content; ?></span>
												</p>
												<ul class="meta-box">
													<li><span class="meta-text priority <?php echo hackgov_get_priority($laporan->ID); ?>"></span></li>
													<li><span class="icon-<?php echo hackgov_get_category($laporan->ID); ?>"></span></li>
													<?php get_votes($laporan->ID); ?>
													<!-- <li><span class="icon-thumb-up"></span><span class="vote-text">15</span></li>
													<li><span class="icon-thumb-down"></span><span class="vote-text">3</span></li> -->
													<li class="pull-right"><span class="shared-text"><?php echo get_share_count($laporan->ID); ?> Shared</span></li>
												</ul>
											</div>
										</div>
									</div>
								</a>
							</div>
							
							<?php
								}
							} 
							?>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
