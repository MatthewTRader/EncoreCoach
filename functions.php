<?php

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');
    add_image_size('homeArticle', 700, 467, array( 'center', 'top' ) ); 
    
    // Add Support for Custom Backgrounds - Uncomment below if you're going to use
    /*add_theme_support('custom-background', array(
	'default-color' => 'FFF',
	'default-image' => get_template_directory_uri() . '/img/bg.jpg'
    ));*/

    // Add Support for Custom Header - Uncomment below if you're going to use
    /*add_theme_support('custom-header', array(
	'default-image'			=> get_template_directory_uri() . '/img/headers/default.jpg',
	'header-text'			=> false,
	'default-text-color'		=> '000',
	'width'				=> 1000,
	'height'			=> 198,
	'random-default'		=> false,
	'wp-head-callback'		=> $wphead_cb,
	'admin-head-callback'		=> $adminhead_cb,
	'admin-preview-callback'	=> $adminpreview_cb
    ));*/

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/


function admin_bar(){

  if(is_user_logged_in()){
         add_filter( 'show_admin_bar', '__return_true' , 1000 );
    }
}
add_action('init', 'admin_bar' );


// EncoreCoach navigation
function _nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

// Load EncoreCoach styles
function _styles()
{

    wp_register_style('', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style(''); // Enqueue it!
}


// Load EncoreCoach scripts (header.php)
function _header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
        
        wp_register_script('jquery', get_template_directory_uri() . 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), '3.1.1');
        wp_enqueue_script('jquery'); // Enqueue it!

        wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-2.7.1.min.js', array(), '2.7.1'); // Modernizr
        wp_enqueue_script('modernizr'); // Enqueue it!
        
        wp_register_script('foundation', get_template_directory_uri() . '/js/foundation.min.js', array('jquery'), '1.0.0'); // Custom scripts
        wp_enqueue_script('foundation'); // Enqueue it!

        wp_register_script('scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0.0'); // Custom scripts
        wp_enqueue_script('scripts'); // Enqueue it!
        
        wp_register_script('font-awesome', 'https://use.fontawesome.com/f7da517c51.js', array(), '1.0.0'); // Custom scripts
        wp_enqueue_script('font-awesome'); // Enqueue it!
        
        wp_register_script('bxslider', get_template_directory_uri() . '/js/jquery.bxslider.min.js', array('jquery'), '4.1.2'); // Custom scripts
        wp_enqueue_script('bxslider'); // Enqueue it!
    }
}


// Load Google fonts
function load_google_fonts() {
    wp_register_style('googleWebFonts', 'https://fonts.googleapis.com/css?family=Roboto');
    wp_enqueue_style('googleWebFonts');
}
add_action('wp_print_styles', 'load_google_fonts');



// Register EncoreCoach Navigation
function register_EncoreCoach_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', ''), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', ''), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', '') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Sidebar Widget Area 1', ''),
        'description' => __('Description for this widget-area...', ''),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Sidebar Widget Area 2', ''),
        'description' => __('Description for this widget-area...', ''),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
    
 // Define Sidebar Widget Area 3
    register_sidebar(array(
        'name' => __('Sidebar Widget Area 3', ''),
        'description' => __('Description for this widget-area...', ''),
        'id' => 'widget-area-3',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 4
    register_sidebar(array(
        'name' => __('Sidebar Widget Area 4', ''),
        'description' => __('Description for this widget-area...', ''),
        'id' => 'widget-area-4',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
    

    // Define Footer Widget Area 1
    register_sidebar(array(
        'name' => __('Footer-left widget', ''),
        'description' => __('Left footer widget', ''),
        'id' => 'footer-widget-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
    

    // Define Footer Widget Area 2
    register_sidebar(array(
        'name' => __('Footer-middle widget', ''),
        'description' => __('Middle footer widget', ''),
        'id' => 'footer-widget-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
    

    // Define Footer Widget Area 3
    register_sidebar(array(
        'name' => __('Footer-right widget', ''),
        'description' => __('Right footer widget', ''),
        'id' => 'footer-widget-3',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function EncoreCoach_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}


function ec_customize_register( $wp_customize ) {
    $wp_customize->add_setting( 'ec_logo' ); // Add setting for logo uploader
         
    // Add control for logo uploader (actual uploader)
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ec_logo', array(
        'label'    => __( 'Upload Logo (replaces text)', 'ec' ),
        'section'  => 'title_tagline',
        'settings' => 'ec_logo',
    ) ) );
}
add_action( 'customize_register', 'ec_customize_register' );




/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
function example_customizer( $wp_customize ) {
    
    $wp_customize->add_section(
        'font_settings',
        array(
            'title' => 'Font Colors',
            'description' => 'You can set header and paragraph font colors here.',
            'priority' => 35,
        )
    );
    
    //Header Font Fields
    
    $wp_customize->add_setting(
        'header-2_font_color',
        array(
            'default' => '#003056',
        )
    );

    $wp_customize->add_control(
        'header-2_font_color',
        array(
            'label' => 'Main Headers Font Color (h1 & h2)',
            'section' => 'font_settings',
            'type' => 'text',
        )
    );
    

 //Sub Header Font Fields
    
    $wp_customize->add_setting(
        'header_font_color',
        array(
            'default' => '#555',
        )
    );

    $wp_customize->add_control(
        'header_font_color',
        array(
            'label' => 'Sub Headers Font Color (h3, h4, h5, h6)',
            'section' => 'font_settings',
            'type' => 'text',
        )
    );
   
    //Paragraph Font Color
    
    $wp_customize->add_setting(
        'paragraph_font_color',
        array(
            'default' => '#555555',
        )
    );

    $wp_customize->add_control(
        'paragraph_font_color',
        array(
            'label' => 'Paragraph Font Color',
            'section' => 'font_settings',
            'type' => 'text',
        )
    );
    
    
    //Social Media Fields
    
    $wp_customize->add_section(
        'social_settings',
        array(
            'title' => 'Social Settings',
            'description' => 'For adding social media icons in the header.',
            'priority' => 30,
        )
    );
    
    //Facebook
    
    $wp_customize->add_setting(
        'show_facebook',
        array(
            'default'    => false,
        )
    );

    $wp_customize->add_control(
        'show_facebook',
        array(
            'label' => 'Facebook',
            'section' => 'social_settings',
            'settings'  => 'show_facebook',
            'type' => 'checkbox',
        )
    );
    
    $wp_customize->add_setting(
        'facebook_link',
        array(
            'default' => 'https://facebook.com',
        )
    );

    $wp_customize->add_control(
        'facebook_link',
        array(
            'label' => 'Facebook URL',
            'section' => 'social_settings',
            'type' => 'text',
        )
    );
    
    //Twitter
    
    $wp_customize->add_setting(
        'show_twitter',
        array(
            'default'    => false,
        )
    );

    $wp_customize->add_control(
        'show_twitter',
        array(
            'label' => 'Twitter',
            'section' => 'social_settings',
            'settings'  => 'show_twitter',
            'type' => 'checkbox',
        )
    );
    
    $wp_customize->add_setting(
        'twitter_link',
        array(
            'default' => 'https://twtter.com',
        )
    );

    $wp_customize->add_control(
        'twitter_link',
        array(
            'label' => 'Twitter URL',
            'section' => 'social_settings',
            'type' => 'text',
        )
    );
    
    //LinkedIn
    
    $wp_customize->add_setting(
        'show_linkedin',
        array(
            'default'    => false,
        )
    );

    $wp_customize->add_control(
        'show_linkedin',
        array(
            'label' => 'LinkedIn',
            'section' => 'social_settings',
            'settings'  => 'show_linkedin',
            'type' => 'checkbox',
        )
    );
    
    $wp_customize->add_setting(
        'linkedin_link',
        array(
            'default' => 'https://linkedin.com',
        )
    );

    $wp_customize->add_control(
        'linkedin_link',
        array(
            'label' => 'LinkedIn URL',
            'section' => 'social_settings',
            'type' => 'text',
        )
    );
}
        
add_action( 'customize_register', 'example_customizer' );


// Custom Excerpts

// Create 20 Word Callback for Index page Excerpts, call using EncoreCoach_excerpt('EncoreCoach_index');
function EncoreCoach_index($length) 
{
    return 20;
}


// Create 60 Word Callback for Custom Post Excerpts, call using EncoreCoach_excerpt('EncoreCoach_custom_post');
function EncoreCoach_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function EncoreCoach_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
        add_filter('excerpt_length', $length_callback);
        add_filter('excerpt_more', $more_callback);

    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// Custom View Article link to Post
function EncoreCoach_view_article($more)
{
    global $post;
    return ' ';
}


// Remove 'text/css' from our enqueued stylesheet
function EncoreCoach_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function gravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function comments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', '_header_scripts'); // Add Custom Scripts to wp_head
//add_action('wp_print_scripts', '_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', '_styles'); // Add Theme Stylesheet
add_action('init', 'register_EncoreCoach_menu'); // Add EncoreCoach Menu
add_action('init', 'create_post_type_EncoreCoach'); // Add our EncoreCoach Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'EncoreCoach_pagination'); // Add our EncoreCoach Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'gravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'EncoreCoach_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('style_loader_tag', 'EncoreCoach_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('EncoreCoach_shortcode_demo', 'EncoreCoach_shortcode_demo'); // You can place [EncoreCoach_shortcode_demo] in Pages, Posts now.
add_shortcode('EncoreCoach_shortcode_demo_2', 'EncoreCoach_shortcode_demo_2'); // Place [EncoreCoach_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [EncoreCoach_shortcode_demo] [EncoreCoach_shortcode_demo_2] Here's the page title! [/EncoreCoach_shortcode_demo_2] [/EncoreCoach_shortcode_demo]

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/

// Create 1 Custom Post type for a Demo, called EncoreCoach
function create_post_type_EncoreCoach()
{
    register_taxonomy_for_object_type('category', 'EncoreCoach'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'EncoreCoach');
    register_post_type('EncoreCoach', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('EncoreCoach Custom Post', ''), // Rename these to suit
            'singular_name' => __('EncoreCoach Custom Post', ''),
            'add_new' => __('Add New', ''),
            'add_new_item' => __('Add New EncoreCoach Custom Post', ''),
            'edit' => __('Edit', ''),
            'edit_item' => __('Edit EncoreCoach Custom Post', ''),
            'new_item' => __('New EncoreCoach Custom Post', ''),
            'view' => __('View EncoreCoach Custom Post', ''),
            'view_item' => __('View EncoreCoach Custom Post', ''),
            'search_items' => __('Search EncoreCoach Custom Post', ''),
            'not_found' => __('No EncoreCoach Custom Posts found', ''),
            'not_found_in_trash' => __('No EncoreCoach Custom Posts found in Trash', '')
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail'
        ), // Go to Dashboard Custom EncoreCoach post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}

/*------------------------------------*\
	ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function EncoreCoach_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function EncoreCoach_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}

?>
