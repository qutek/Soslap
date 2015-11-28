<?php
/**
 * The template for displaying all dashboard user
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package hackgov
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<form method="POST" action="" id="submit_new">
			
			<div class="drop-attachment">
				<div class="tj_drop_image">
					<div class="caption">Click atau Drag Gambar<br><span>Disini</span></div>
                    <input type="file" class="tj_select_file">
                    <input type="hidden" class="uploaded_img images" name="bukti_transfer" value="">
                </div>
	     	</div>

			<input type="text" name="title" placeholder="Judul">
			<textarea name="problem" id="content_problem" cols="30" rows="10" placeholder="Masalah"></textarea>
			<textarea name="saran" id="content_saran" cols="30" rows="10" placeholder="Saran"></textarea>
			<span class="radio-group">
				<input type="radio" name="priority" value="1"> Prioritas 1
				<input type="radio" name="priority" value="2"> Prioritas 2
				<input type="radio" name="priority" value="3"> Prioritas 3
			</span>
			<select name="category" id="category">
				<option value="1">Kat</option>
			</select>
			<div class="container-input-location">
				<input type="hidden" id="latitude" value="-7.72711716283" name="latitude" placeholder="latitude">
				<input type="hidden" id="longitude" value="110.40847454603272" name="longitude" placeholder="longitude">
				<input class="postcode form-control" id="location-map" name="location-map" type="text" placeholder="Lokasi" autocomplete="off" style="width:100%;">
				<div id="map"></div>
			</div>
			


			<input type="submit" value="Simpan">
			<?php wp_nonce_field( 'hackgov_submit_new_nonce', 'hackgov_submit_new' ); ?>
		</form>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
