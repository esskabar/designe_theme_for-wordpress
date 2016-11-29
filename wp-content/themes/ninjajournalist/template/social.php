<div id="social">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="wrap_social clearfix">
						<h2 class="head_social">get social!</h2>
						<div class="item_social_wrap">
							<a href="//www.youtube.com" class="you_tube item_social">
							</a>
							<a href="//twitter.com" class="twitter item_social">
							</a>
							<a href="//www.facebook.com" class="facebook item_social">
							</a>
						</div>

					</div>
					<?php
					if ( is_active_sidebar( 'ad-728x90' ) ) : ?>
						<?php dynamic_sidebar( 'ad-728x90' ); ?>
					<?php else: ?>
					<div class="wrap_ads hidden-xs hidden-sm">
						<div class="ads">
							<img src="<?php echo get_stylesheet_directory_uri() . '/images/img/ads.png';?>" class="img-responsive" alt="">
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>