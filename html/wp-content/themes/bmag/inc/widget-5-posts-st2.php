<?php
/**
 * Widget para mostrar las entradas recientes con thumbnails
 *
 * @package BMag
 *
 * @since BMag 1.0
 */

add_action( 'widgets_init', 'bmag_5_posts_style_2_widget' );

function bmag_5_posts_style_2_widget() {
	register_widget( 'Bmag_Widget_5_Posts_Style_2' );
}

class Bmag_Widget_5_Posts_Style_2 extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'bmag_widget_5_posts_style_2',
			'description' => __( 'Displays the latest 5 posts of specific category. This widget must use it ONLY on the MAGAZINE FRONT PAGE.', 'bmag' ),
		);

		parent::__construct( 'Bmag_Widget_5_Posts_Style_2', '(BMag) ' . __( '5 Posts - Style 2', 'bmag' ), $widget_ops );
	}

	public function form( $instance ) {

		$defaults = array(
			'title'   => '',
			'cat_sel' => '',
			'offset'  => 0,
			'frame'   => 'light-frame',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$title   = $instance ['title'];
		$cat_sel = $instance['cat_sel'];
		$offset  = $instance['offset'];
		$frame   = $instance ['frame'];

		?>

		<p><?php esc_html_e( 'Title', 'bmag' ); ?><br />
			<input class="widefat"
			id="<?php echo $this->get_field_id( 'title' ); ?>"
			name="<?php echo $this->get_field_name( 'title' ); ?>"
			type="text" value="<?php echo esc_attr( $title ); ?>" size="30" />
		</p>

		<p><strong><?php esc_html_e( 'Select category', 'bmag' ); ?>:</strong><br />

		<p>
		<select id="<?php echo $this->get_field_id( 'cat_sel' ); ?>" name="<?php echo $this->get_field_name( 'cat_sel' ); ?>">
		<option value=""><?php esc_html_e( 'All', 'bmag' ); ?></option>
		<?php
		$categorias = get_categories();
		foreach ( $categorias as $categoria ) {
			$cat_id   = $categoria->cat_ID;
			$cat_slug = $categoria->slug;
			$cat_name = $categoria->name;

			?>
			<option value="<?php echo $cat_id; ?>"  <?php echo selected( $cat_sel, $cat_id, false ); ?>><?php echo $cat_name; ?></option>
			<?php
		}
		?>
		</select>
		</p>

		<p>
		<?php esc_html_e( 'Number of post to pass over', 'bmag' ); ?>: &nbsp;
		<input
		name="<?php echo $this->get_field_name( 'offset' ); ?>"
		type="number" value="<?php echo esc_attr( $offset ); ?>" min="0" max="10"  />
		</p>

		<p>
		<?php esc_html_e( 'Main item frame', 'bmag' ); ?><br>
		<select id="<?php echo $this->get_field_id( 'frame' ); ?>" name="<?php echo $this->get_field_name( 'frame' ); ?>">
			<option value="frameless"  <?php echo selected( $frame, 'frameless', false ); ?>><?php esc_html_e( 'Frameless', 'bmag' ); ?></option>
			<option value="light-frame"  <?php echo selected( $frame, 'light-frame', false ); ?>><?php esc_html_e( 'Light frame', 'bmag' ); ?></option>
			<option value="dark-frame"  <?php echo selected( $frame, 'dark-frame', false ); ?>><?php esc_html_e( 'Dark frame', 'bmag' ); ?></option>
		</select>
		</p>
		<?php

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']   = sanitize_text_field( $new_instance['title'] );
		$instance['cat_sel'] = $new_instance['cat_sel'];
		$instance['offset']  = sanitize_text_field( $new_instance['offset'] );
		$instance['frame']   = ( ! empty( $new_instance['frame'] ) ) ? strip_tags( $new_instance['frame'] ) : 'light-frame';

		return $instance;
	}

	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		$title   = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) : '';
		$cat_sel = ! empty( $instance['cat_sel'] ) ? $instance['cat_sel'] : '';
		$offset  = ! empty( $instance['offset'] ) ? (int) $instance['offset'] : 0;
		$frame   = ! empty( $instance['frame'] ) ? $instance['frame'] : 'light-frame';

		if (! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		$arg = array(
			'post__not_in'   => get_option( 'sticky_posts' ),
			'posts_per_page' => 5,
			'cat'            => $cat_sel,
			'offset'         => $offset,
		);

		$query = new WP_Query( $arg );

		if ( $query->have_posts() ) :

			$num = $this->number;

			?>

			<div class="wrapper-mag-widget-5-posts-st2-<?php echo $num; ?>">

			<?php
			while ( $query->have_posts() ) :

				$query->the_post();

				$current_post = $query->current_post;

				if ( 0 == $current_post ) { // Elemento principal.
					?>

					<div class="wrapper-main-item-5-posts-st2 <?php echo $frame; ?>">

						<div class="main-item-5-posts-st2">

							<?php if ( has_post_thumbnail() ) : ?>
								<a href='<?php the_permalink(); ?>'><?php the_post_thumbnail( 'bmag-thumbnail-4x3' ); ?></a>
							<?php else : ?>
								<a href='<?php the_permalink(); ?>'><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/default-4x3.png" /></a>
							<?php endif; ?>


							<div class="main-item-5-posts-st2-title fondo-degradado">

								<?php
								the_title( '<h3 class="entry-title overlay-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
								?>

								<div class="post-meta-fp"><?php bmag_entry_info_fp(); ?></div>

							</div><!-- .main-item-title -->

						</div><!-- .main-item-5-posts-st2 -->
						<div class="main-item-5-posts-st2-excerpt"><?php the_excerpt(); ?></div>
					</div><!-- .wrapper-main-item-5-posts-st2 -->

					<?php

				} else { // current_post > 0. Elementos secundarios.

				?>

					<?php if ( 1 == $current_post ) { // Primer item secundario, se abre el contenedor. ?>

					<div class="wrapper-secondary-items-5-posts-st2">

					<?php } ?>

						<div class="secondary-item-5-posts-st2">

							<div class="secondary-item-5-posts-st2-img">

								<?php if ( has_post_thumbnail() ) : ?>
									<a href='<?php the_permalink(); ?>'><?php the_post_thumbnail( 'bmag-thumbnail-4x3' ); ?></a>
								<?php else : ?>
									<a href='<?php the_permalink(); ?>'><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/default-4x3.png" /></a>
								<?php endif; ?>

							</div><!-- .secondary-item-5-posts-st2-img -->

							<div class="secondary-item-5-posts-st2-title">
								<?php
								the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
								?>
								<div class="post-meta-fp"><?php bmag_entry_info_fp(); ?></div>
							</div>

						</div><!-- .secondary-item-5-posts-st2 -->

					<?php
				}
			endwhile;
			?>

				</div><!-- .wrapper-secondary-items-5-posts-st2 -->
			</div><!-- .wrapper-mag-widget-5-posts-st2- -->

		<?php
		wp_reset_postdata();

		endif;

		echo $args['after_widget'];
	}
}
