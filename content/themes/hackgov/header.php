<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package hackgov
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body id="<?php echo hackgov_body_id(); ?>" <?php body_class(); ?>>
	<header>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="main-menu nav navbar-nav">
						<li class="hidden-xs"><a href="<?php echo home_url(); ?>" class="brand"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo_brand.png" height="40px"></a></li>
						<li><a href="<?php echo home_url(); ?>"><span class="icon-home"></span><span class="menu-text">Homepage</span></a></li>
						<li><a href="<?php echo home_url('infrastruktur'); ?>"><span class="icon-traffic"></span><span class="menu-text">Infrastruktur</span></a></li>
						<li><a href="<?php echo home_url('lingkungan' );; ?>"><span class="icon-leaf"></span><span class="menu-text">Lingkungan</span></a></li>
						<li class="pull-right"><a href="<?php echo home_url('submit'); ?>" class="btn btn-icon"><span class="icon-compose"></span><span class="btn-text">Buat laporan</span></a></li>
					</ul>
				</div>
			</div>
		</div>
	</header>