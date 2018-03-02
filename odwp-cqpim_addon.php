<?php
/**
 * Plugin Name: Doplňky pro CQPIM
 * Description: Rozšíření pluginu <b>CQPIM</b>.
 * Version: 1.0.0
 * Author: Ondřej Doněk
 * License: GPLv3
 * Requires at least: 4.7
 * Tested up to: 4.8.4
 *
 * @author Ondřej Doněk <ondrejd@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License 3.0
 * @package odwp-cqpim_addon
 * @since 1.0.0
 */

if( ! defined( 'ABSPATH' ) ) {
    exit;
}


if( !function_exists( 'odwpca_client_metabox_callback' ) ):
    function odwpca_client_metabox_callback( $post ) {
        $ic = get_post_meta( $post->ID, 'odwpca_client_ic', true );
        $dic = get_post_meta( $post->ID, 'odwpca_client_dic', true );
        $psc = get_post_meta( $post->ID, 'odwpca_client_fupsc', true );
        $srv = get_post_meta( $post->ID, 'odwpca_client_srv', true );
?>
<p><?php _e( 'IČ:' )?> </p>
<input type="text" name="contact_odwpca_ic" id="contact_odwpca_ic" value="<?php if( !empty( $ic ) ) { echo $ic; } ?>">
<p><?php _e( 'DIČ:' )?> </p>
<input type="text" name="contact_odwpca_dic" id="contact_odwpca_dic" value="<?php if( !empty( $dic ) ) { echo $dic; } ?>">
<p><?php _e( 'PSČ fin. úřadu:' )?> </p>
<input type="text" name="contact_odwpca_fupsc" id="contact_odwpca_fupsc" value="<?php if( !empty( $psc ) ) { echo $psc; } ?>">
<p><?php _e( 'Služba:' )?> </p>
<select name="contact_odwpca_srv" id="contact_odwpca_srv" value="<?php if( !empty( $srv ) ) { echo $srv; } ?>">
    <option value="mikro" <?php selected( 'mikro', $srv, true )?>><?php _e( 'MIKRO' )?></option>
    <option value="zaklad" <?php selected( 'zaklad', $srv, true )?>><?php _e( 'ZÁKLAD' )?></option>
    <option value="standard" <?php selected( 'standard', $srv, true )?>><?php _e( 'STANDARD' )?></option>
    <option value="vip" <?php selected( 'vip', $srv, true )?>><?php _e( 'VIP' )?></option>
</select>
<?php
    }
endif;


if( !function_exists( 'odwpca_client_metabox_add' ) ):
    function odwpca_client_metabox_add() {
        add_meta_box(
                'odwpca_client_extras',
                __( 'Další informace' ),
                'odwpca_client_metabox_callback',
                'cqpim_client', 
                'side',
                'high'
        );
    }
endif;


if( !function_exists( 'odwpca_client_metabox_save' ) ):
    function odwpca_client_metabox_save( $post_id ) {
	if( isset($_POST['contact_odwpca_ic'] ) ) {
            update_post_meta( $post_id, 'odwpca_client_ic', $_POST['contact_odwpca_ic'] );
	}
	
	if( isset($_POST['contact_odwpca_dic'] ) ) {
            update_post_meta( $post_id, 'odwpca_client_dic', $_POST['contact_odwpca_dic'] );
	}
	
	if( isset($_POST['contact_odwpca_fupsc'] ) ) {
            update_post_meta( $post_id, 'odwpca_client_fupsc', $_POST['contact_odwpca_fupsc'] );
	}
	
	if( isset($_POST['contact_odwpca_srv'] ) ) {
            update_post_meta( $post_id, 'odwpca_client_srv', $_POST['contact_odwpca_srv'] );
	}
    }
endif;


if( !function_exists( 'odwpca_client_custom_columns' ) ):
    function odwpca_client_custom_columns( $columns ) {
        $new_columns = array(
            'odwpca_client_ic' => __( 'IČ' ),
            'odwpca_client_dic' => __( 'DIČ' ),
            'odwpca_client_fupsc' => __( 'FÚ PSČ' ),
            'odwpca_client_srv' => __( 'Služba' ),
        );
        return array_merge( $columns, $new_columns );
    }
endif;


if( !function_exists( 'odwpca_client_custom_columns_content' ) ):
    function odwpca_client_custom_columns_content( $column, $post_id ) {
        switch( $column ) {
            case 'odwpca_client_ic':
            case 'odwpca_client_dic':
            case 'odwpca_client_fupsc':
                $val = get_post_meta( $post_id, $column, true );
                echo '<code>' . ( !empty( $val ) ? $val : '---' ) . '</code>';
                break;
            
            case 'odwpca_client_srv':
                $val = get_post_meta( $post_id, $column, true );
                echo !empty( $val ) ? '<b>' . $val . '</b>' : '';
                break;
        }
    }
endif;


if( !function_exists( 'odwpca_client_sortable_columns' ) ):
    function odwpca_client_sortable_columns( $columns ) {
        $new_columns = array(
            'odwpca_client_ic' => 'odwpca_ic',
            'odwpca_client_dic' => 'odwpca_dic',
            'odwpca_client_fupsc' => 'odwpca_fupsc',
            'odwpca_client_srv' => 'odwpca_srv',
        );
        return array_merge( $columns, $new_columns );
    }
endif;


if( !function_exists( 'odwpca_client_sort_columns_orderby' ) ):
    function odwpca_client_sort_columns_orderby( $query ) {
        if( ! is_admin() ) {
            return;
        }
        
        $orderby = $query->get( 'orderby' );
        
        if( !in_array( $orderby, array( 'odwpca_client_ic', 'odwpca_client_dic', 'odwpca_client_fupsc', 'odwpca_client_fupsc' ) ) ) {
            return;
        }

        if( $orderby == 'odwpca_client_fupsc' ) {
            $query->set( 'meta_key', $orderby );
        } else {
            $query->set( 'meta_key', $orderby );
            $query->set( 'orderby', 'meta_value_num' );
        }
        
        return $query;
    }
endif;


if( !function_exists( 'odwpca_plugins_loaded' ) ):
    function odwpca_plugins_loaded() {
        // Metabox
        add_action( 'add_meta_boxes', 'odwpca_client_metabox_add' );
        add_action( 'save_post', 'odwpca_client_metabox_save' );
        // Table
        add_filter( 'manage_cqpim_client_posts_columns', 'odwpca_client_custom_columns', 10, 1 );
        add_action( 'manage_cqpim_client_posts_custom_column', 'odwpca_client_custom_columns_content', 10, 2 );
    }
endif;

        add_filter( 'manage_cqpim_client_sortable_columns', 'odwpca_client_sortable_columns' );
        add_action( 'pre_get_posts', 'odwpca_client_sort_columns_orderby' );
add_action( 'plugins_loaded', 'odwpca_plugins_loaded' );
