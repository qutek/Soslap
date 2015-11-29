<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package hackgov
 */

get_header(); ?>
	
	<div id="body">
		<div class="single-post">
			<div class="container">
				<?php while ( have_posts() ) : the_post(); ?>
				<div class="row">
					<div class="col-md-8">
						<div class="content-box">
							<?php if ( has_post_thumbnail() ) { ?>
							<div class="img-post">
								<?php the_post_thumbnail(); ?>
							</div>
							<?php } ?>
							<?php the_title( '<h1 class="">', '</h1>' ); ?>
							<div class="description">
								<div class="masalah-box">
									<h4>Masalah</h4>
									<?php the_content(); ?>
								</div>
								<div class="solusi-box">
									<h4>Solusi</h4>
									<?php echo get_post_meta( $post->ID, '_post_recommendation', true ); ?>
								</div>
							</div>
							<ul class="meta-box">
								<li><span class="meta-text priority <?php echo hackgov_get_priority($post->ID); ?>"></span></li>
								<li><span class="icon-<?php echo hackgov_get_category($post->ID); ?>"></span></li>
								<?php $comment_count = get_comment_count( $post->ID ); ?>
								<li><span class="icon-comment"></span><span class="comment-text"><?php echo $comment_count['approved']; ?></span></li>
								<?php get_votes($post->ID); ?>
								<!-- <li><span class="icon-thumb-up"></span><span class="vote-text">15</span></li>
								<li><span class="icon-thumb-down"></span><span class="vote-text">3</span></li> -->
								<li class="pull-right"><span class="shared-text"><?php echo get_share_count($post->ID); ?> Shared</span></li>
							</ul>
						</div>
					</div>
					<div class="col-md-4">
						<div class="sidebar">
							<div class="widget-box widget-share">
								<h4>Bagikan isu ini</h4>
								<div class="sss_kk_main" style="max-width: 560px;">
									<div class="sss_shares_block">
										<span class="sss_shares_number"><?php echo get_share_count($post->ID); ?></span><strong>SHARES</strong>
									</div>
									
									<a rel="nofollow" href="<?php echo get_twitter_share_url($post->ID); ?>" target="_blank" title="Tweet this" class="btn-twitter"><span class="sss_twitter_button icon-twitter"></span>Tweet</a>
									<?php $fb_url = build_query( array('u' => get_short_url($post->ID) ) ); ?>
									<a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?<?php echo $fb_url; ?>" title="Share this on Facebook" target="_blank" class="btn-facebook"><span class="sss_fb_button icon-facebook"></span>Share</a>
								</div>
								<p class="description">Saat share Facebook gunakanlah <span>#l<?php echo $post->ID; ?></span> untuk memperkuat pendapat Anda</p>
							</div>
							<div class="widget-box widget-popular">
								<h4>Isu Terpopuler</h4>
								<?php  
								$popular = get_popular_posts(5);
								?>
								<ul class="widget-list">
									<?php if(!empty($popular)){ 
										foreach ($popular as $key => $popular) {
									?>
									<li>
										<div class="row">
											<div class="img-box col-md-4">
												<?php echo get_the_post_thumbnail( $popular->ID, 'medium' ); ?>
											</div>
											<div class="list-content col-md-8">
												<a href=""><?php echo get_the_title( $popular->ID ); ?></a>
												<ul class="meta-box">
													<li><span class="icon-traffic"></span></li>
													<?php get_votes($popular->ID); ?>
													<li class="pull-right"><span class="shared-text"><?php echo get_share_count($popular->ID); ?> Shared</span></li>
												</ul>
											</div>
										</div>
									</li>
									<?php
										}
									}
									?>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<?php endwhile; // End of the loop. ?>
			</div>
		</div>
	</div>
	
<?php get_footer(); ?>
