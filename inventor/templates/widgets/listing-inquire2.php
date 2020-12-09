<?php
global $wp;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php echo wp_kses( $args['before_widget'], wp_kses_allowed_html( 'post' ) ); ?>

	<div class="widget-inner
 <?php if ( ! empty( $instance['classes'] ) ) : ?><?php echo esc_attr( $instance['classes'] ); ?><?php endif; ?>
 <?php echo ( empty( $instance['padding_top'] ) ) ? '' : 'widget-pt' ; ?>
 <?php echo ( empty( $instance['padding_bottom'] ) ) ? '' : 'widget-pb' ; ?>"
		<?php if ( ! empty( $instance['background_color'] ) || ! empty( $instance['background_image'] ) ) : ?>
			style="
			<?php if ( ! empty( $instance['background_color'] ) ) : ?>
				background-color: <?php echo esc_attr( $instance['background_color'] ); ?>;
    <?php endif; ?>
			<?php if ( ! empty( $instance['background_image'] ) ) : ?>
				background-image: url('<?php echo esc_attr( $instance['background_image'] ); ?>');
			<?php endif; ?>"
		<?php endif; ?>>

		<?php if ( ! empty( $instance['title'] ) ) : ?>
			<?php echo wp_kses( $args['before_title'], wp_kses_allowed_html( 'post' ) ); ?>
			<?php echo wp_kses( $instance['title'], wp_kses_allowed_html( 'post' ) ); ?>
			<?php echo wp_kses( $args['after_title'], wp_kses_allowed_html( 'post' ) ); ?>
		<?php endif; ?>
        
	</div><!-- /.widget-inner -->
    <?php echo do_shortcode('[contact-form-7 id="12346" title="TEST"]')?>


	<script>
	
	$(document).ready(function(){
		
		
		$(".wpcf7-submit").click(function(){
			
			
			
			//alert("aaaaa");
			
			location.href = "https://catalog.adwrks.co.il/index.php?download=&kccpid=&kcccount=<?php echo home_url( $wp->request ); ?>__QUESTION__mess_type=2";
			//location.href = "https://catalog.adwrks.co.il/?download&amp%3Bkccpid&amp%3Bkcccount=https%3A%2F%2Fcatalog.adwrks.co.il%2Fbusinesses%2Fshimon-instelator%2F__QUESTION__mess_type%3D2";
			
		});
		
		
	});
	
	
	</script>
	
	
<?php echo wp_kses( $args['after_widget'], wp_kses_allowed_html( 'post' ) ); ?>

