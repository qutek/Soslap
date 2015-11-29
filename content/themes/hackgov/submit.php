<?php
/**
 * The template for displaying all dashboard user
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package hackgov
 */

get_header(); ?>

	<div class="form-box page-section">
		<div class="container">
			<h1 class="center">Buat Laporan</h1>
			<form method="POST" action="" id="submit_new">
				<div class="form-group">
					<div class="drop-attachment">
						<div class="tj_drop_image">
							<div class="caption">Click atau Drag Gambar<br><span>Disini</span></div>
		                    <input type="file" class="tj_select_file">
		                    <input type="hidden" class="uploaded_img images" name="featured_image" value="">
		                </div>
			     	</div>
				</div>
				<div class="form-group">
					<label>Judul</label>
					<input class="form-control" type="text" name="title" placeholder="Judul">
				</div>
				<div class="form-group">
					<label>Kategori</label>
					<select name="category" class="form-control">
						<?php  
						foreach ( hackgov_category() as $key => $value) {
							echo '<option value="'.$key.'">'.$value.'</option>';
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<label>Lokasi</label>
					<div class="container-input">
						<input type="hidden" id="latitude" value="-7.72711716283" name="latitude" placeholder="latitude">
						<input type="hidden" id="longitude" value="110.40847454603272" name="longitude" placeholder="longitude">
						<input class="postcode form-control" id="location_address" name="location_address" type="text" placeholder="Tulis alamat" autocomplete="off" style="width:100%;" value="<?php echo $location_name; ?>">
					</div>
					<div id="map" style="height:300px;"></div>
				</div>
				<div class="form-group">
					<label>Masalah</label>
					<textarea name="problem" class="form-control" id="content_problem" cols="30" rows="10" placeholder="Masalah"></textarea>
				</div>
				<div class="form-group">
					<label>Solusi</label>
					<textarea name="recommendation" id="content_recommendation" cols="30" rows="10" placeholder="Saran" class="form-control"></textarea>
				</div>
				<div class="form-group">
					<label>Prioritas</label>
					<select class="form-control" name="priority">
						<?php  
						foreach ( hackgov_priority() as $key => $value) {
							echo '<option value="'.$key.'">'.$value.'</option>';
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-big form-control">Kirim</button>
					<?php wp_nonce_field( 'hackgov_submit_new_nonce', 'hackgov_submit_new' ); ?>
				</div>
			</form>
		</div>
	</div>

<?php get_footer(); ?>
