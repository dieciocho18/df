<?php
/**
 * Widget para mostrar las entradas recientes con thumbnails
 *
 * @package BMag
 *
 * @since BMag 1.0
 */

add_action( 'widgets_init', 'bmag_columns_cats_widget' );

function bmag_columns_cats_widget() {
	register_widget( 'Bmag_Widget_Columns_Cats' );
}

class Bmag_Widget_Columns_Cats extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'bmag_widget_columns_cats',
			'description' => __( 'Displays the latest posts of two specific categories (each category in a column). This widget must use it ONLY on the MAGAZINE FRONT PAGE.', 'bmag' ),
		);

		parent::__construct( 'Bmag_Widget_Columns_Cats', '(BMag) ' . __( 'Two Columns', 'bmag' ), $widget_ops );
	}

	public function form( $instance ) {
		?>

		<style type="text/css">
			.titulo-seccion{text-align:center; padding:4px 0; border-top:1px solid #cccccc; border-bottom:1px solid #cccccc;}
		</style>

		<?php

		$defaults = array(
			'title_left'      => '',
			'cat_sel_left'    => '',
			'title_right'     => '',
			'cat_sel_right'   => '',
			'n_posts'         => '3',
			'mostrar_excerpt' => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$title_left      = $instance ['title_left'];
		$cat_sel_left    = $instance['cat_sel_left'];
		$title_right     = $instance ['title_right'];
		$cat_sel_right   = $instance['cat_sel_right'];
		$n_posts         = $instance ['n_posts'];
		$mostrar_excerpt = $instance ['mostrar_excerpt'];

		?>

		<!-- Columna izquierda -->
		<p class="titulo-seccion"><strong><?php esc_html_e( 'Left Column', 'bmag' ); ?></strong></p>

		<p><?php esc_html_e( 'Title', 'bmag' ); ?><br />
			<input class="widefat"
			id="<?php echo $this->get_field_id( 'title_left' ); ?>"
			name="<?php echo $this->get_field_name( 'title_left' ); ?>"
			type="text" value="<?php echo esc_attr( $title_left ); ?>" size="30" />
		</p>

		<p><strong><?php esc_html_e( 'Category', 'bmag' ); ?>:</strong><br />

		<p>
			<select id="<?php echo $this->get_field_id( 'cat_sel_left' ); ?>" name="<?php echo $this->get_field_name( 'cat_sel_left' ); ?>">
			<option value=""><?php esc_html_e( 'All', 'bmag' ); ?></option>

			<?php

			$categorias_left = get_categories();

			foreach ( $categorias_left as $categoria_left ) {
				$cat_id_left   = $categoria_left->cat_ID;
				$cat_slug_left = $categoria_left->slug;
				$cat_name_left = $categoria_left->name;

				?>
				<option value="<?php echo $cat_id_left; ?>"  <?php echo selected( $cat_sel_left, $cat_id_left, false ); ?>><?php echo $cat_name_left; ?></option>
				<?php

			}
			?>
			</select>
		</p>

		<!-- Final columna izquierda -->

		<!-- Columna derecha -->

		<p class="titulo-seccion"><strong><?php esc_html_e( 'Right Column', 'bmag' ); ?></strong></p>

		<p><?php esc_html_e( 'Title', 'bmag' ); ?><br />
			<input class="widefat"
			id="<?php echo $this->get_field_id( 'title_right' ); ?>"
			name="<?php echo $this->get_field_name( 'title_right' ); ?>"
			type="text" value="<?php echo esc_attr( $title_right ); ?>" size="30" />
		</p>

		<p><strong><?php esc_html_e( 'Category', 'bmag' ); ?>:</strong><br />

		<p>
			<select id="<?php echo $this->get_field_id( 'cat_sel_right' ); ?>" name="<?php echo $this->get_field_name( 'cat_sel_right' ); ?>">
			<option value=""><?php esc_html_e( 'All', 'bmag' ); ?></option>
			<?php
			$categorias_right = get_categories();
			foreach ( $categorias_right as $categoria_right ) {
				$cat_id_right   = $categoria_right->cat_ID;
				$cat_slug_right = $categoria_right->slug;
				$cat_name_right = $categoria_right->name;

				?>
				<option value="<?php echo $cat_id_right; ?>"  <?php echo selected( $cat_sel_right, $cat_id_right, false ); ?>><?php echo $cat_name_right; ?></option>
				<?php

			}
			?>
			</select>
		</p>

		<!-- Final columna derecha -->

		<!-- Opciones comunes -->

		<p class="titulo-seccion"><strong><?php esc_html_e( 'Common', 'bmag' ); ?></strong></p>

		<p>
		<?php esc_html_e( 'Number of posts to display', 'bmag' ); ?>: &nbsp;
		<input name="<?php echo $this->get_field_name( 'n_posts' ); ?>"
		type="number" value="<?php echo esc_attr( $n_posts ); ?>" mix="1" max="15"  />
		</p>

		<p>
		<input name="<?php echo $this->get_field_name( 'mostrar_excerpt' ); ?>" type="checkbox"
		<?php echo checked( $mostrar_excerpt, 'on', false ); ?> /> <?php esc_html_e( 'Show excerpt', 'bmag' ); ?>
		</p>

		<?php

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title_left']      = sanitize_text_field( $new_instance['title_left'] );
		$instance['cat_sel_left']    = $new_instance['cat_sel_left'];
		$instance['title_right']     = sanitize_text_field( $new_instance['title_right'] );
		$instance['cat_sel_right']   = $new_instance['cat_sel_right'];
		$instance['n_posts']         = sanitize_text_field( $new_instance['n_posts'] );
		$instance['mostrar_excerpt'] = ( ! empty( $new_instance['mostrar_excerpt'] ) ) ? strip_tags( $new_instance['mostrar_excerpt'] ) : '';

		return $instance;
	}

	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		$title_left      = ! empty( $instance['title_left'] ) ? apply_filters( 'widget_title', $instance['title_left'], $instance, $this->id_base ) : '';
		$cat_sel_left    = ! empty( $instance['cat_sel_left'] ) ? $instance['cat_sel_left'] : '';
		$title_right     = ! empty( $instance['title_right'] ) ? apply_filters( 'widget_title', $instance['title_right'], $instance, $this->id_base ) : '';
		$cat_sel_right   = ! empty( $instance['cat_sel_right'] ) ? $instance['cat_sel_right'] : '';
		$n_posts         = ! empty( $instance['n_posts'] ) ? $instance['n_posts'] : 3;
		$mostrar_excerpt = ! empty( $instance['mostrar_excerpt'] ) ? $instance['mostrar_excerpt'] : '';

		/* COLUMNA IZQUIERDA */

		$args_left = array(
			'post__not_in'   => get_option( 'sticky_posts' ),
			'posts_per_page' => $n_posts,
			'cat'            => $cat_sel_left,
		);

		$query_left = new WP_Query( $args_left );

		if ( $query_left->have_posts() ) :
			$num = $this->number;
			?>
			<div class="wrapper-mag-widget-cols-cats-<?php echo $num; ?>">
			<div class="wrapper-column-cats-left">

			<?php
			if (! empty( $title_left ) ) {
				echo $args['before_title'] . esc_html( $title_left ) . $args['after_title'];
			}
			?>

			<div class="columns-cats-left-content">

			<?php
			while ( $query_left->have_posts() ) :

				$query_left->the_post();
				$current_post = $query_left->current_post;

				if ( $current_post == 0 ) {
					?>

					<div class="col-cats-main-item">
						<?php
						if ( has_post_thumbnail() ) :
						?>
							<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_post_thumbnail( 'bmag-thumbnail-3x2' ); ?></a>
						<?php else : ?>
							<a href="<?php echo esc_url( get_the_permalink() ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/default-3x2.png" /></a>
						<?php endif; ?>

						<div class="wrapper-title-main-item fondo-degradado">
							<?php
							the_title( '<h3 class="entry-title overlay-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
							?>

							<div class="post-meta-fp"><?php bmag_entry_info_fp(); ?></div>

						</div>

					</div>

					<?php if ( $mostrar_excerpt == 'on' ) { ?>
					<div class="excerpt-col-cats"><?php the_excerpt(); ?></div>
					<?php } ?>

				<?php

				} else {

					$ultimo_item = ( $current_post == $n_posts - 1 ) ? ' ultimo-item' : '';
					?>

					<div class="wrapper-col-cats-items<?php echo $ultimo_item; ?>">

						<div class="col-cats-item-img">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href='<?php the_permalink(); ?>'><?php the_post_thumbnail( 'bmag-thumbnail-4x3' ); ?></a>
						<?php else : ?>
							<a href='<?php the_permalink(); ?>'><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/default-4x3.png" /></a>
						<?php endif; ?>
						</div>

						<div class="col-cats-item-title">
							<?php
							the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
							?>
						</div>

						<div class="post-meta-fp"><?php bmag_entry_info_fp(); ?></div>

					</div><!-- .wrapper-col-cats-items -->

					<?php if ( $mostrar_excerpt == 'on' ) { ?>
					<div class="excerpt-col-cats"><?php the_excerpt(); ?></div>
					<?php } ?>

					<?php
				}

				endwhile;
				?>

			</div><!-- .columns-cats-left-content -->
			</div><!-- .wrapper-column-cats-left -->

		<?php

		endif;
		wp_reset_postdata();

		/* FINAL COLUMNA IZQUIERDA */

		/* COLUMNA DERECHA */

		$args_right = array(
			'post__not_in'   => get_option( 'sticky_posts' ),
			'posts_per_page' => $n_posts,
			'cat'            => $cat_sel_right,
		);

		$query_right = new WP_Query( $args_right );

		if ( $query_right->have_posts() ) :

			?>
			<div class="wrapper-column-cats-right">

			<?php
			if (! empty( $title_right ) ) {
				echo $args['before_title'] . esc_html( $title_right ) . $args['after_title'];
			}
			?>

			<div class="columns-cats-right-content">

			<?php
			while ( $query_right->have_posts() ) :

				$query_right->the_post();

				$current_post = $query_right->current_post;

				if ( $current_post == 0 ) {
					?>

					<div class="col-cats-main-item">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_post_thumbnail( 'bmag-thumbnail-3x2' ); ?></a>
						<?php else : ?>
							<a href="<?php echo esc_url( get_the_permalink() ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/default-3x2.png" /></a>
						<?php endif; ?>

						<div class="wrapper-title-main-item fondo-degradado">
							<?php
							the_title( '<h3 class="entry-title overlay-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
							?>

							<div class="post-meta-fp"><?php bmag_entry_info_fp(); ?></div>

						</div>
					</div>

					<?php if ( $mostrar_excerpt == 'on' ) { ?>
					<div class="excerpt-col-cats"><?php the_excerpt(); ?></div>
					<?php } ?>

					<?php

				} else {

					$ultimo_item = ( $current_post == $n_posts - 1 ) ? $ultimo_item = ' ultimo-item' : '';

					?>

					<div class="wrapper-col-cats-items<?php echo $ultimo_item; ?>">

						<div class="col-cats-item-img">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href='<?php the_permalink(); ?>'><?php the_post_thumbnail( 'bmag-thumbnail-4x3' ); ?></a>
						<?php else : ?>
							<a href='<?php the_permalink(); ?>'><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/default-4x3.png" /></a>
						<?php endif; ?>
						</div>

						<div class="col-cats-item-title">
							<?php
							the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
							?>
						</div>

						<div class="post-meta-fp"><?php bmag_entry_info_fp(); ?></div>

					</div><!-- .wrapper-col-cats-items -->

					<?php if ( $mostrar_excerpt == 'on' ) { ?>
					<div class="excerpt-col-cats"><?php the_excerpt(); ?></div>
					<?php } ?>

				<?php
				}

			endwhile;
			?>
			</div><!-- .columns-cats-right-content -->
			</div><!-- .wrapper-column-cats-right -->
		</div><!-- .wrapper-mag-widget-cols-cats -->
		<?php

		endif;
		wp_reset_postdata();

		/* FINAL COLUMNA DERECHA */

		echo $args['after_widget'];

	}
}
