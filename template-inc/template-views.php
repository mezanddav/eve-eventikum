<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package eve
 */

if ( ! function_exists( 'eve_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function eve_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'eve' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}
endif;



if ( ! function_exists( 'eve_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function eve_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'eve' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;



if ( ! function_exists( 'eve_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function eve_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'eve' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'eve' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'eve' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'eve' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'eve' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'eve' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;



if ( ! function_exists( 'eve_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function eve_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div>

		<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>">
			<?php
			the_post_thumbnail( 'post-thumbnail', array(
				'alt' => the_title_attribute( array(
					'echo' => false,
				) ),
			) );
			?>
		</a>

		<?php
		endif; // End is_singular().
	}
endif;



if ( ! function_exists( 'the_breadcrumb' ) ) :
	/**
	 * The Breadcrumb
	 */
	function the_breadcrumb( $sep = '/' )
	{
		global $post;
		$itemprop_position = 1;

		echo '<ul class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">';
		echo '<li itemprop="itemListElement" itemscope
		itemtype="http://schema.org/ListItem"><a itemprop="item" href="'. get_home_url() .'">';
		echo '<span itemprop="name">'. get_bloginfo( 'name' ) .'</span>';
		echo '</a><meta itemprop="position" content="'. $itemprop_position++ .'" /></li>';
		if( !is_home() ):
			echo '<li class="separator"> '. $sep .' </li>';

			if( is_single() ):
				echo '<li itemprop="itemListElement" itemscope
				itemtype="http://schema.org/ListItem"><a itemprop="item" href="'. get_permalink() .'"><span itemprop="name">';
				the_title();
				echo '</span></a><meta itemprop="position" content="'. $itemprop_position++ .'" /></li>';

			elseif( is_archive() && !is_category() && !is_tag() && !is_author() ):
				$archive = get_queried_object();
				echo '<li itemprop="itemListElement" itemscope
				itemtype="http://schema.org/ListItem"><a itemprop="item" href="'. get_post_type_archive_link( $archive->name ) .'"><span itemprop="name">';
				echo $archive->label;
				echo '</span></a><meta itemprop="position" content="'. $itemprop_position++ .'" /></li>';

			elseif( is_category() ):
				$category = get_queried_object();
				echo '<li itemprop="itemListElement" itemscope
				itemtype="http://schema.org/ListItem"><a itemprop="item" href="'. get_category_link( $category->term_id ) .'"><span itemprop="name">';
				echo get_the_category_by_ID( $category->term_id );
				echo '</span></a><meta itemprop="position" content="'. $itemprop_position++ .'" /></li>';

			elseif( is_page() ):
				if( !empty($post->post_parent) ):
					$anc = get_post_ancestors( $post->ID );
					$anc = array_reverse($anc);
					$title = get_the_title();
					foreach ( $anc as $ancestor ):
						echo '<li itemprop="itemListElement" itemscope
						itemtype="http://schema.org/ListItem"><a itemprop="item" href="'. get_permalink($ancestor) .'" title="'. get_the_title($ancestor) .'"><span itemprop="name">'. get_the_title($ancestor) .'</span></a><meta itemprop="position" content="'. $itemprop_position++ .'" /></li><li class="separator"> '. $sep .' </li>';
					endforeach;
					echo '<li itemprop="itemListElement" itemscope
					itemtype="http://schema.org/ListItem"><a itemprop="item" href="'. get_permalink( $post->ID ) .'" title="'. $title .'"><span itemprop="name">'. $title .'</span></a><meta itemprop="position" content="'. $itemprop_position++ .'" /></li>';
				else:
					echo '<li itemprop="itemListElement" itemscope
					itemtype="http://schema.org/ListItem"><a itemprop="item" href="'. get_permalink() .'"> <span itemprop="name">'. get_the_title() .'</span></a><meta itemprop="position" content="'. $itemprop_position++ .'" /></li>';
				endif;

			elseif( is_tag() ):
				$tag = get_queried_object();
				echo '<li itemprop="itemListElement" itemscope
				itemtype="http://schema.org/ListItem"><a itemprop="item" href="'. get_tag_link( $tag->term_id ) .'"><span itemprop="name">';
				echo single_tag_title();
				echo '</span></a><meta itemprop="position" content="'. $itemprop_position++ .'" /></li>';

			elseif( is_search() ):
				echo '<li>'. __( 'Search Results', 'eve' ) .'</li>';

			elseif ( is_day() ):
				echo '<li>'. __( 'Archive for ', 'eve' ); the_time('F jS, Y'); echo '</li>';

			elseif ( is_month() ): 
				echo '<li>'. __( 'Archive for ', 'eve' ); the_time('F, Y'); echo'</li>';

			elseif ( is_year() ): 
				echo '<li>'. __( 'Archive for ', 'eve' ); the_time('Y'); echo'</li>';

			elseif ( is_author() ):
				echo '<li>'. __( 'Author Archive', 'eve' ) .'</li>';

			elseif ( isset($_GET['paged']) && !empty($_GET['paged']) ):
				echo '<li>'. __( 'Blog Archives', 'eve' ) .'</li>';

			endif;

		elseif( is_home() ):
			echo '<li class="separator"> '. $sep .' </li>';
			echo '<li itemprop="itemListElement" itemscope
			itemtype="http://schema.org/ListItem"><a itemprop="item" href="'. get_permalink( get_option( 'page_for_posts' ) ) .'"><span itemprop="name">';
			single_post_title();
			echo '</span></a><meta itemprop="position" content="'. $itemprop_position++ .'" /></li>';

		endif;
		echo '</ul>';
	}
endif;




if ( !function_exists( 'progresseve_gtm_core' ) ) :
	/**
	 * GTM Code
	 */
	function progresseve_gtm_core() { 

    $gtm_ID = get_option('eve_gtm_code');
    if( empty($gtm_ID) ){ return; }

?><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo $gtm_ID; ?>');</script>
<?php
  }
endif;



if ( !function_exists( 'progresseve_gtm_noscript' ) ) :
	/**
	 * GTM Code
	 */
	function progresseve_gtm_noscript() { 

    $gtm_ID = get_option('eve_gtm_code');
    if( empty($gtm_ID) ){ return; }

?><noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtm_ID; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php
  }
endif;



if ( ! function_exists( 'eve_foo' ) ) :
	/**
	 * Comment
	 */
	function eve_foo() {

	}
endif;