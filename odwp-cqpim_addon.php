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
    /**
     * @param WP_Post $post
     * @return void
     */
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
    /**
     * @return void
     */
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


if( !function_exists( 'odwpca_cqpim_client_save' ) ):
    /**
     * @global WP_Post $post
     * @param int $post_id
     * @return int
     */
    function odwpca_cqpim_client_save( $post_id ) {
        global $post;

        if( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if( 'cqpim_client' != $post->post_type) {
            return $post_id;
        }

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

        return $post_id;
    }
endif;


if( !function_exists( 'odwpca_client_custom_columns' ) ):
    /**
     * @param array $columns
     * @return array
     */
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
    /**
     * @param string $column
     * @param int $post_id
     * @return void
     */
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
    /**
     * @param array $columns
     * @return array
     */
    function odwpca_client_sortable_columns( $columns ) {
        $new_columns = array(
            'odwpca_client_ic' => 'odwpca_client_ic',
            'odwpca_client_dic' => 'odwpca_client_dic',
            'odwpca_client_fupsc' => 'odwpca_client_fupsc',
            'odwpca_client_srv' => 'odwpca_client_srv',
        );
        return array_merge( $columns, $new_columns );
    }
endif;


if( !function_exists( 'odwpca_client_sort_columns_orderby' ) ):
    /**
     * @param WP_Query $query
     * @return void
     */
    function odwpca_client_sort_columns_orderby( $query ) {
        if( ! is_admin() ) {
            return;
        }
        
        $orderby = $query->get( 'orderby' );
        
        if( !in_array( $orderby, array( 'odwpca_client_ic', 'odwpca_client_dic', 'odwpca_client_fupsc', 'odwpca_client_fupsc' ) ) ) {
            return;
        }

        $query->set( 'meta_key', $orderby );
        $query->set( 'orderby', 'meta_value' );

        if( $orderby == 'odwpca_client_type' ) {
            $query->set( 'meta_type', 'NUMERIC' );
        } else {
            $query->set( 'meta_type', 'CHAR' );
        }
    }
endif;


if( !function_exists( 'odwpca_client_manage_posts_1' ) ):
    /**
     * @param string $post_type
     * @return void
     */
    function odwpca_client_manage_posts_1( $post_type ) {
        if( $post_type !== 'cqpim_client' ) {
            return;
        }

        $srv = isset( $_GET['odwpca_client_srv_filter'] ) ? $_GET['odwpca_client_srv_filter'] : '';
?>
<select name="odwpca_client_srv_filter" id="odwpca_client_srv_filter" value="<?php if( !empty( $srv ) ) { echo $srv; } ?>">
    <option value="" <?php selected( '', $srv, true )?>><?php _e( 'Všechny typy' )?></option>
    <option value="mikro" <?php selected( 'mikro', $srv, true )?>><?php _e( 'MIKRO' )?></option>
    <option value="zaklad" <?php selected( 'zaklad', $srv, true )?>><?php _e( 'ZÁKLAD' )?></option>
    <option value="standard" <?php selected( 'standard', $srv, true )?>><?php _e( 'STANDARD' )?></option>
    <option value="vip" <?php selected( 'vip', $srv, true )?>><?php _e( 'VIP' )?></option>
</select>
<?php
    }
endif;


if( !function_exists( 'odwpca_client_prefix_parse_filter_1' ) ):
    /**
     * @global string $pagenow
     * @param WP_Query $query
     * @return void
     */
    function odwpca_client_prefix_parse_filter_1( $query ) {
        global $pagenow;

        $current_page = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';

        if(
            is_admin() &&
            'cqpim_client' == $current_page &&
            'edit.php' == $pagenow &&
            isset( $_GET['odwpca_client_srv_filter'] ) &&
            $_GET['odwpca_client_srv_filter'] != ''
        ) {
            $type = $_GET['odwpca_client_srv_filter'];
            $query->query_vars['meta_key'] = 'odwpca_client_srv';
            $query->query_vars['meta_value'] = $type;
            $query->query_vars['meta_compare'] = '=';
        }
    }
endif;


if( !function_exists( 'odwpca_invoice_manage_posts' ) ):
    /**
     * @param string $post_type
     * @return void
     */
    function odwpca_invoice_manage_posts( $post_type ) {
        if( $post_type !== 'cqpim_invoice' ) {
            return;
        }

        $state = isset( $_GET['odwpca_invoice_state_filter'] ) ? $_GET['odwpca_invoice_state_filter'] : '';
?>
<select name="odwpca_invoice_state_filter" id="odwpca_invoice_state_filter" value="<?php if( !empty( $state ) ) { echo $state; } ?>">
    <option value="" <?php selected( '', $state, true )?>><?php _e( 'Všechny stavy' )?></option>
    <option value="zaplacene" <?php selected( 'zaplacene', $state, true )?>><?php _e( 'Zaplacené' )?></option>
    <option value="nezaplacene" <?php selected( 'nezaplacene', $state, true )?>><?php _e( 'Nezaplacené' )?></option>
</select>
<?php
    }
endif;


if( !function_exists( 'odwpca_invoice_prefix_parse_filter' ) ):
    /**
     * @global string $pagenow
     * @param WP_Query $query
     * @return void
     */
    function odwpca_invoice_prefix_parse_filter( $query ) {
        global $pagenow;

        $current_page = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';

        if(
            is_admin() &&
            'cqpim_invoice' == $current_page &&
            'edit.php' == $pagenow &&
            isset( $_GET['odwpca_invoice_state_filter'] ) &&
            $_GET['odwpca_invoice_state_filter'] != ''
        ) {
            $state = $_GET['odwpca_invoice_state_filter'];
            $query->query_vars['meta_key'] = 'odwpca_invoice_paid';
            $query->query_vars['meta_value'] = 'y';

            if( 'zaplacene' === $state ) {
                $query->query_vars['meta_compare'] = '=';
            } else {
                $query->query_vars['meta_compare'] = '!=';
            }
        }
    }
endif;


if( !function_exists( 'odwpca_cqpim_invoice_save' ) ):
    /**
     * @global WP_Post $post
     * @param int $post_id
     * @return int
     */
    function odwpca_cqpim_invoice_save( $post_id ) {
        global $post;

        if( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if( 'cqpim_invoice' != $post->post_type) {
            return $post_id;
        }

        $inv_details = get_post_meta( $post_id, 'invoice_details', true );
        $inv_paid = isset($inv_details['paid']) ? $inv_details['paid'] : '';
        $paid = ( bool ) $inv_paid === true ? 'y' : 'n';

        update_post_meta( $post_id, 'odwpca_invoice_paid', $paid );

        return $post_id;
    }
endif;


if( !function_exists( 'odwpca_client_manage_posts_2' ) ):
    /**
     * @global wpdb $wpdb
     * @param string $post_type
     * @return void
     */
    function odwpca_client_manage_posts_2( $post_type ) {
        global $wpdb;

        if( $post_type !== 'cqpim_client' ) {
            return;
        }

        $zips = $wpdb->get_col( "
            SELECT DISTINCT meta_value
            FROM " . $wpdb->postmeta . "
            WHERE meta_key = 'odwpca_client_fupsc'
            ORDER BY meta_value
        " );
        $zip = isset( $_GET['odwpca_client_fupsc_filter'] ) ? $_GET['odwpca_client_fupsc_filter'] : '';
?>
<select name="odwpca_client_fupsc_filter" id="odwpca_client_fupsc_filter" value="<?php if( !empty( $zip ) ) { echo $zip; } ?>">
    <option value="" <?php selected( '', $zip, true )?>><?php _e( 'Všechny PSČ' )?></option>
    <?php foreach( $zips as $_zip ):?>
    <option value="<?php echo esc_attr( $_zip )?>" <?php selected( $_zip, $zip, true )?>><?php echo esc_html( $_zip )?></option>
    <?php endforeach ?>
</select>
<?php
    }
endif;


if( !function_exists( 'odwpca_client_prefix_parse_filter_2' ) ):
    /**
     * @global string $pagenow
     * @param WP_Query $query
     * @return void
     */
    function odwpca_client_prefix_parse_filter_2( $query ) {
        global $pagenow;

        $current_page = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';

        if(
            is_admin() &&
            'cqpim_client' == $current_page &&
            'edit.php' == $pagenow &&
            isset( $_GET['odwpca_client_fupsc_filter'] ) &&
            $_GET['odwpca_client_fupsc_filter'] != ''
        ) {
            $zip = $_GET['odwpca_client_fupsc_filter'];
            $query->query_vars['meta_key'] = 'odwpca_client_fupsc';
            $query->query_vars['meta_value'] = $zip;
            $query->query_vars['meta_compare'] = '=';
        }
    }
endif;


if( !function_exists( 'odwpca_plugins_loaded' ) ):
    /**
     * @return void
     */
    function odwpca_plugins_loaded() {
        // Metaboxes for client add/edit page
        add_action( 'add_meta_boxes', 'odwpca_client_metabox_add' );
        add_action( 'save_post', 'odwpca_cqpim_client_save' );
        // Client table
        add_filter( 'manage_cqpim_client_posts_columns', 'odwpca_client_custom_columns', 10, 1 );
        add_action( 'manage_cqpim_client_posts_custom_column', 'odwpca_client_custom_columns_content', 10, 2 );
        add_filter( 'manage_edit-cqpim_client_sortable_columns', 'odwpca_client_sortable_columns', 10, 1 );
        add_action( 'pre_get_posts', 'odwpca_client_sort_columns_orderby' );
        add_action( 'restrict_manage_posts', 'odwpca_client_manage_posts_1' );
        add_filter( 'parse_query', 'odwpca_client_prefix_parse_filter_1' );
        add_action( 'restrict_manage_posts', 'odwpca_client_manage_posts_2' );
        add_filter( 'parse_query', 'odwpca_client_prefix_parse_filter_2' );
        // Invoices table
        add_action( 'restrict_manage_posts', 'odwpca_invoice_manage_posts' );
        add_filter( 'parse_query', 'odwpca_invoice_prefix_parse_filter' );
        add_action( 'save_post', 'odwpca_cqpim_invoice_save' );
    }
endif;


if( ! function_exists( 'odwpca_check_requirements' ) ) :
    /**
     * @global string $wp_version
     * @param array $requirements
     * @return array
     * @since 1.0.0
     */
    function odwpca_check_requirements( array $requirements ) {
        global $wp_version;

        $errors = [];

        // Check PHP version
        if( !empty( $requirements['php']['version'] ) ) {
            if( version_compare( phpversion(), $requirements['php']['version'], '<' ) ) {
                $errors[] = sprintf(
                        __( 'PHP nesplňuje nároky pluginu na minimální verzi (vyžadována nejméně <b>%s</b>)!' ),
                        $requirements['php']['version']
                );
            }
        }

        // Check PHP extensions
        if( count( $requirements['php']['extensions'] ) > 0 ) {
            foreach( $requirements['php']['extensions'] as $req_ext ) {
                if( ! extension_loaded( $req_ext ) ) {
                    $errors[] = sprintf(
                            __( 'Je vyžadováno rozšíření PHP <b>%s</b>, to ale není nainstalováno!' ),
                            $req_ext
                    );
                }
            }
        }

        // Check WP version
        if( ! empty( $requirements['wp']['version'] ) ) {
            if( version_compare( $wp_version, $requirements['wp']['version'], '<' ) ) {
                $errors[] = sprintf(
                        __( 'Plugin vyžaduje vyšší verzi platformy <b>WordPress</b> (minimálně <b>%s</b>)!' ),
                        $requirements['wp']['version']
                );
            }
        }

        // Check WP plugins
        if( count( $requirements['wp']['plugins'] ) > 0 ) {
            $active_plugins = (array) get_option( 'active_plugins', [] );
            foreach( $requirements['wp']['plugins'] as $req_plugin ) {
                if( ! in_array( $req_plugin, $active_plugins ) ) {
                    $errors[] = sprintf(
                            __( 'Je vyžadován plugin <b>%s</b>, ten ale není nainstalován!' ),
                            $req_plugin
                    );
                }
            }
        }

        return $errors;
    }
endif;


if( ! function_exists( 'odwpca_deactivate_raw' ) ) :
    /**
     * @return void
     */
    function odwpca_deactivate_raw() {
        $active_plugins = get_option( 'active_plugins' );
        $out = [];

        foreach( $active_plugins as $key => $val ) {
            if( $val != 'odwp-cqpim_addon/odwp-cqpim_addon.php' ) {
                $out[$key] = $val;
            }
        }

        update_option( 'active_plugins', $out );
    }
endif;


// Check if requirements of the plugin are met or not
$odwpca_errs = odwpca_check_requirements( [
    'php' => [
        'version' => '5.6',
        'extensions' => [],
    ],
    'wp' => [
        'version' => '4.8',
        'plugins' => ['cqpim/cqpim.php'],
    ],
] );

if( count( $odwpca_errs ) > 0 ) {
    odwpca_deactivate_raw();

    if( is_admin() ) {
        add_action( 'admin_notices', function() use ( $odwpca_errs ) {
            $err_head = __( '<b>Doplňky pro CQPIM</b>: ' );

            foreach( $odwpca_errs as $err ) {
                printf( '<div class="error"><p>%s</p></div>', $err_head . $err );
            }
        } );
    }
} else {
    // Requirements are met, initialize the plugin
    add_action( 'plugins_loaded', 'odwpca_plugins_loaded' );
}