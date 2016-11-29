<?php ?>

<div class="visible-md-block visible-lg-block col-md-4 aside">
	<div class="aside-popular">
	<?php
	if ( is_active_sidebar( 'aside-popular' ) ) : ?>
		<?php dynamic_sidebar( 'aside-popular' ); ?>
	<?php else: ?>

			<h2>Trending Posts</h2>
			<div class="widget">
		<?php
		// WP_Query arguments
		$args = array (
			'post_type'              => array( 'post' ),
			'post_status'            => array( 'publish' ),
			'category__not_in'        => array('1'),
			'posts_per_page'         => '7',
			'order'                  => 'DESC',
			'orderby'                => 'date',
		);

		// The Query
		$query_aside_home = new WP_Query( $args );

		// The Loop
		if ( $query_aside_home->have_posts() ) {
			while ( $query_aside_home->have_posts() ) {
				$query_aside_home->the_post();
				$category_detail=get_the_category($post->ID);
				?>

					<div class="three_blocks_item">
						<a href="<?php the_permalink(); ?>">
							<p class="tb_top_meta"><span>BEST</span> <?php echo $category_detail[0]->cat_name; ?></p>

							<?php
							if ( has_post_thumbnail()):
								$thumb_ = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'theme-aside-thumbnail' )?>
								<div class="tb_img_wrap">
									<div class="tb_img" style="background-image: url('<?php echo $thumb_[0];?>')"></div>
								</div>
							<?php else: ?>
								<div class="tb_img_wrap">
									<div class="tb_img" style="background-image: url('<?php echo get_stylesheet_directory_uri() . '/images/no-image.png';?>')"></div>
								</div>
							<?php endif; ?>
							<h2 class="tb_head"><?php the_title(); ?></h2>
							<p class="tb_text"><?php kama_excerpt( 'maxchar=150' ); ?></p>
							<p class="tb_slogan">I can’t get involved! I’ve got work to do! </p>
							<p class="tb_category"><?php echo $category_detail[0]->cat_name; ?></p>
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
			</div>

	<?php endif; ?>
	</div>
</div>
