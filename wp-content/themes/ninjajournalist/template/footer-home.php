<?php

?>
<!---->
<!--<footer id="main_footer">-->
<!--	<div class="container">-->
<!--		<div class="row">-->
<!--			<div class="col-xs-12 wrap_footer">-->
<!--				--><?php //get_template_part( 'template/footer_about' ) ?>
<!--				--><?php //get_template_part( 'template/footer_widget' ) ?>
<!--				--><?php //get_template_part( 'template/footer_menu' ) ?>
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
<!--</footer>-->

<footer id="main_footer">
	<div class="container">
		<div class="row">
			<?php get_template_part( 'template/footer_about' ) ?>
			<?php get_template_part( 'template/footer_follow' ) ?>
			<?php get_template_part( 'template/footer_widget' ) ?>
<!--			--><?php //get_template_part( 'template/footer_menu' ) ?>
<!--			//= template/footer_about.html-->
<!--			//= template/footer_follow.html-->
<!--			//= template/footer_widget.html-->
		</div>
	</div>
	<?php get_template_part( 'template/footer_menu' ) ?>
<!--	//= template/footer_menu.html-->
</footer>


