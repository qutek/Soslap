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
$posts = get_posts( array('post_status' => 'publish') );
?>

	<div id="body">
		<div id="homepage" class="page-section">
			<div class="container">
				<div class="row">
					<div id="report" class="report-list-box" role="tabpanel">
						<div class="report-list">
							<?php foreach ($posts as $key => $post) { ?>
							<?php  
							$x = get_post_meta( $post->ID, '_category_category', true );
							print_r($x);
							?>
							<?php $author = get_userdata( $post->post_author ); ?>
							<!-- report item -->
							<div class="report-item">
								<div class="row">
									<div class="col-md-4">
										<div class="img-post">
											<div class="result-box <?php echo str_replace('-', '', get_post_meta($post->ID, '_category_status', true ) ); ?>">
												<?php echo get_status_icon($post->ID); ?>
											</div>
											<?php echo get_the_post_thumbnail( $post->ID ); ?>
										</div>
									</div>
									<div class="col-md-8">
										<div class="report-content">
											<div class="author-box">
												<div class="author-box">
													<div class="img-box">
														<?php echo get_avatar( $post->post_author ); ?>
													</div>
													<div class="author-detail">
														<a href=""><div><?php echo $author->display_name; ?></div></a>
														<p class="datetime-box"><span class="icon-clock"></span><?php echo $post->post_date; ?></p>
													</div>
												</div>
											</div>
											<a href="<?php echo get_permalink( $post->ID ); ?>" class="report-title"><?php echo get_the_title( $post->ID ); ?></a>
											<p class="location-box"><?php echo get_post_meta( $Post->ID, '_location', true ); ?></p>
											<p class="description-box">
												<span class="masalah-box"><?php echo $post->post_content; ?></span>
											</p>
											<ul class="meta-box">
												<li><span class="meta-text priority <?php echo hackgov_get_priority($post->ID); ?>"></span></li>
												<li><span class="icon-<?php echo hackgov_get_category($post->ID); ?>"></span></li>
												<?php get_votes($post->ID); ?>
												<!-- <li><span class="icon-thumb-up"></span><span class="vote-text">15</span></li>
												<li><span class="icon-thumb-down"></span><span class="vote-text">3</span></li> -->
												<li class="pull-right"><span class="shared-text"><?php echo get_share_count($post->ID); ?> Shared</span></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<!-- report item -->
							<?php } ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>
