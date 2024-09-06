<?php
/*
 * Plugin Name:       Delete Post Meta
 * Plugin URI:        https://github.com/guzmandrade-wds/delete-post-meta
 * Description:       Delete Post Meta based on meta key.
 * Version:           1.2.0
 * Requires at least: 6.3
 * Requires PHP:      7.4
 * Author:            Mauricio Andrade
 * Author URI:        https://profiles.wordpress.org/h4l9k/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       delete-post-meta
 */

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

// Wait for plugins to be loaded before registering actions to validate admin access.
add_action( 'plugins_loaded', 'delete_post_meta_init' );

/**
 * Validates admin access and adds action hook.
 *
 * @return void
 */
function delete_post_meta_init() {
	if ( current_user_can( 'activate_plugins' ) ) {
		add_action( 'admin_menu', 'delete_post_meta_submenu' );
	}
}

/**
 * Registers a submenu page for the 'tools.php' admin page.
 *
 * @return void
 */
function delete_post_meta_submenu() {
	add_submenu_page(
		'tools.php',
		__( 'Delete Post Meta', 'delete-post-meta' ),
		__( 'Delete Post Meta', 'delete-post-meta' ),
		'activate_plugins',
		'delete-post-meta',
		'delete_post_meta_callback'
	);
}

/**
 * Returns an array of all object types that have an associated meta table.
 *
 * @return array
 */
function delete_post_meta_get_object_types() {
	// Get all registered post types.
	$post_types = get_post_types( array(), 'names' );

	// Default object types.
	$object_types = array_merge( $post_types, array( 'comment', 'term', 'user' ) );

	foreach ( $object_types as $type ) {
		$table = _get_meta_table( $type );
		if ( ! $table ) {
			unset( $object_types[ $type ] );
		}
	}

	return $object_types;
}

/**
 * Main callback function. If the delete_post_meta_key_search value is not false, it calls `delete_metadata` to delete all post meta based on the meta key.
 *
 * @return void
 */
function delete_post_meta_callback() {
	if ( isset( $_POST['delete_post_meta_nonce'] )
		&& check_admin_referer( 'delete_post_meta_action', 'delete_post_meta_nonce' )
	) {
		$meta_key_search = isset( $_POST['delete_post_meta_key_search'] ) ? sanitize_text_field( wp_unslash( $_POST['delete_post_meta_key_search'] ) ) : false;
		$object_type     = isset( $_POST['delete_post_meta_object_type'] ) ? sanitize_text_field( wp_unslash( $_POST['delete_post_meta_object_type'] ) ) : 'post';
		if ( $meta_key_search ) {
			/**
			 * More info at:
			 *
			 * @link https://developer.wordpress.org/reference/functions/delete_metadata/
			 * Accepts 'post', 'comment', 'term', 'user', or any other object type with an associated meta table.
			 */
			delete_metadata( $object_type, 0, $meta_key_search, null, true );
		}
	}

	$object_types = delete_post_meta_get_object_types();
	?>
	<div class="wrap"><div id="icon-tools" class="icon32"></div>
		<h2><?php esc_html_e( 'Delete Post Meta', 'delete-post-meta' ); ?></h2>
		<div class="notice notice-warning inline">
			<p><strong><?php esc_html_e( 'Warning:', 'delete-post-meta' ); ?></strong> <?php esc_html_e( 'Use this plugin with caution. It will delete all post meta based on a meta key.', 'delete-post-meta' ); ?></p>
		</div>
		<form method="post">
			<?php wp_nonce_field( 'delete_post_meta_action', 'delete_post_meta_nonce' ); ?>
			<label for="delete_post_meta_object_type">Select Object Type: </label>
			<select name="delete_post_meta_object_type" id="delete_post_meta_object_type">
				<?php foreach ( $object_types as $object_type ) : ?>
					<option value="<?php echo esc_attr( $object_type ); ?>" <?php selected( $object_type, 'post' ); ?>>
						<?php echo esc_html( ucfirst( $object_type ) ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<label for="delete_post_meta_key_search">Meta Key:</label>
			<input name="delete_post_meta_key_search" id="delete_post_meta_key_search" type="text" />

			<input type="submit" class="button button-primary" onclick="return confirm('We are about to delete all post meta based on this meta key. Are you sure?')" value="Delete Post Meta" />
		</form>
	</div>
	<?php
}
