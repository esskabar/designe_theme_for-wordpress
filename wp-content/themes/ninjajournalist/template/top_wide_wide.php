<?php
global $post_not_in, $block_wide_post;
$post_not_in = !empty( $post_not_in ) ? $post_not_in : array();
$block_wide_post = get_option('block_wide_post');
$post_in = $block_wide_post ? $block_wide_post['wide'] : '';
?>

<?php

// WP_Query arguments
$args = array (
	'p' => $post_in,
	'posts_per_page' => 1,
	'category__not_in' => 1,
	'post_type'              => array( 'post' ),
	'post_status'            => array( 'publish' ),
	'order'                  => 'DESC',
	'post__not_in' => $post_not_in,
	'orderby'                => 'date'
);

// The Query
$query_top_wide_wide = new WP_Query( $args );

// The Loop
if ( $query_top_wide_wide->have_posts() ) {
	while ( $query_top_wide_wide->have_posts() ) {
		$query_top_wide_wide->the_post();
		?>
		<?php
		if ( has_post_thumbnail()){
			$thumb_ = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'ifl-theme-main_wide-thumbnail' );
		} else {
			$thumb_ = array();
		}?>
		<?php
		$post_not_in[] = $post->ID;
		$category_detail=get_the_category($post->ID);
		?>
		<div class="wb_wide">
			<a href="<?php the_permalink(); ?>">
				<div class="wb_wide_img_wrap">
					<div class="wb_wide_img" style="background-image: url('<?php echo $thumb_[0]; ?>')"></div>
				</div>
				<h2 class="wb_wide_head"><?php the_title(); ?></h2>
				<p class="wb_wide_text"><?php kama_excerpt( 'maxchar=150' ); ?></p>
				<p class="wb_wide_meta"><?php echo $category_detail[0]->cat_name; ?></p>
			</a>
		</div>
		<?php
	}
} else {
	// no posts found
}

// Restore original Post Data
wp_reset_postdata();
?>
