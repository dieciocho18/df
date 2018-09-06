<?php
/**
 * Widget para mostrar las entradas recientes con thumbnails
 *
 * @package BMag
 *
 * @since BMag 1.0
 */

add_action( 'widgets_init', 'bmag_banner_3_posts_widget' );

function bmag_banner_3_posts_widget() {
	register_widget( 'Bmag_Widget_Banner_3_Posts' );
}

class Bmag_Widget_Banner_3_Posts extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'bmag_widget_banner_3_posts',
			'description' => __( 'Displays the latest 3 post of specific category. This widget must use it ONLY on the MAGAZINE FRONT PAGE.', 'bmag' ),
		);

		parent::__construct( 'Bmag_Widget_Banner_3_Posts', '(BMag) ' . __( '3 Posts', 'bmag' ), $widget_ops );
	}

	public function form( $instance ) {

		$defaults = array(
			'title'   => '',
			'cat_sel' => '',
			'offset'  => 0,
			'frame'   => 'frameless',
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
		<?php esc_html_e( 'Frame', 'bmag' ); ?><br>
		<select id="<?php echo $this->get_field_id( 'frame' ); ?>" name="<?php echo $this->get_field_name( 'frame' ); ?>">
			<option value="frameless"  <?php echo selected( $frame, 'frameless', false ); ?>><?php esc_html_e( 'Frameless', 'bmag' ); ?></option>
			<option value="light-frame"  <?php echo selected( $frame, 'light-frame', false ); ?>><?php esc_html_e( 'Light frame', 'bmag' ); ?></option>
		</select>
		</p>

		<?php

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']   = sanitize_text_field( $new_instance['title'] );
		$instance['cat_sel'] = $new_instance['cat_sel'];
		$instance['offset']  = sanitize_text_field( $new_instance['offset'] );
		$instance['frame']   = strip_tags( $new_instance['frame'] );

		return $instance;

	}

	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		$title   = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) : '';
		$cat_sel = ! empty( $instance['cat_sel'] ) ? $instance['cat_sel'] : '';
		$offset  = ! empty( $instance['offset'] ) ? (int) $instance['offset'] : 0;
		$frame   = ! empty( $instance['frame'] ) ? $instance['frame'] : 'frameless';

		if (! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		$arg = array(
			'post__not_in'   => get_option( 'sticky_posts' ),
			'posts_per_page' => 3,
			'cat'            => $cat_sel,
			'offset'         => $offset,
		);

		$query = new WP_Query( $arg );

		if ( $query->have_posts() ) :

			$num = $this->number;

			?>

			<div class="wrapper-mag-widget-3-posts-<?php echo $num . ' ' . $frame; ?>">

				<?php
				while ( $query->have_posts() ) :

					$query->the_post();
					?>

					<div class="banner-3-posts-item">
						<a href='<?php the_permalink(); ?>'>
							<div class="banner-3-posts-item-img">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'bmag-thumbnail-3x2' ); ?>
							<?php else : ?>
								<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/default-3x2.png" />
							<?php endif; ?>
							</div>
						</a>

						<div class="banner-3-posts-item-title">
							<?php
							the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
							?>
						</div>
						<div class="post-meta-fp"><?php bmag_entry_info_fp(); ?></div>

					</div><!-- .banner-3-posts-item -->

				<?php
				endwhile;
				?>
			</div><!-- .wrapper-banner-3-posts -->

			<?php
			wp_reset_postdata();

		endif;

		echo $args['after_widget'];

	} // Widget.

} // Class..
