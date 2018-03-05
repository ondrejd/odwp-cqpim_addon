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
    <option value="maxim" <?php selected( 'vip', $srv, true )?>><?php _e( 'MAXIM' )?></option>
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
    <option value="maxim" <?php selected( 'maxim', $srv, true )?>><?php _e( 'MAXIM' )?></option>
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
    <option value="posplatnosti" <?php selected( 'posplatnosti', $state, true )?>><?php _e( 'Po splatnosti' )?></option>
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

            if( 'posplatnosti' === $state ) {
                $query->query_vars['meta_key'] = 'odwpca_invoice_afterpayterm';
                $query->query_vars['meta_value'] = 'y';
                $query->query_vars['meta_compare'] = '=';
            } else {
                $query->query_vars['meta_key'] = 'odwpca_invoice_paid';
                $query->query_vars['meta_value'] = 'y';
                $query->query_vars['meta_compare'] = ( 'zaplacene' === $state ) ? '=' : '!=';
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
        $inv_paid = isset( $inv_details['paid'] ) ? $inv_details['paid'] : '';
        $paid = ( bool ) $inv_paid === true ? 'y' : 'n';
        update_post_meta( $post_id, 'odwpca_invoice_paid', $paid );

        if( $paid != 'y' ) {
            $due = isset( $inv_details['terms_over'] ) ? $inv_details['terms_over'] : '';

            if( $due ) {
                $now = time();
                update_post_meta( $post_id, 'odwpca_invoice_afterpayterm', ( $now > $due ) ? 'y' : 'n' );
            } else {
                update_post_meta( $post_id, 'odwpca_invoice_afterpayterm', 'n' );
            }
        } else {
            update_post_meta( $post_id, 'odwpca_invoice_afterpayterm', 'n' );
        }

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


if( !function_exists( 'odwpca_feform_shortcode' ) ):
    /**
     * This function contains code which is slightly customized original CQPIM code.
     * @see cqpim/shortcodes/frontend_quote.php
     * @return void
     */
    function odwpca_feform_shortcode() {
?>
<div class="entry-content">
    <!--<h3>Nezávazná objednávka služeb</h3>-->
    <div id="cqpim_frontend_form_cont">
        <form id="odwpca_feform">
            <!--<input type="hidden" id="_wpnonce" name="_wpnonce" value="88e738beaa">
            <input type="hidden" name="_wp_http_referer" value="/">-->
            <?php echo wp_nonce_field('image-submission') ?>
            <div style="padding-bottom:12px" class="cqpim_form_item">
                <label style="display:block; padding-bottom:5px" for="full_name"><?php _e( 'Jméno a příjmení' ) ?> <span style="color:#F00">*</span></label>
                <input style="width:100%" type="text" id="full_name" required>
            </div>
            <div style="padding-bottom:12px" class="cqpim_form_item">
                <label style="display:block; padding-bottom:5px" for="company_name"><?php _e( 'Název společnosti' ) ?> <span style="color:#F00">*</span></label>
                <input style="width:100%" type="text" id="company_name" required>
            </div>
            <div style="padding-bottom:12px" class="cqpim_form_item">
                <label style="display:block; padding-bottom:5px" for="address"><?php _e( 'Adresa' ) ?> <span style="color:#F00">*</span></label>
                <textarea style="width:100%; height:140px" id="address" required></textarea>
            </div>
            <div style="padding-bottom:12px" class="cqpim_form_item">
                <label style="display:block; padding-bottom:5px" for="postcode"><?php _e( 'PSČ' ) ?> <span style="color:#F00">*</span></label>
                <input style="width:100%" type="text" id="postcode" required>
            </div>
            <div style="padding-bottom:12px" class="cqpim_form_item">
                <label style="display:block; padding-bottom:5px" for="telephone"><?php _e( 'Telefon' ) ?> <span style="color:#F00">*</span></label>
                <input style="width:100%" type="text" id="telephone" required>
            </div>
            <div style="padding-bottom:12px" class="cqpim_form_item">
                <label style="display:block; padding-bottom:5px" for="email"><?php _e( 'E-mail' ) ?> <span style="color:#F00">*</span></label>
                <input style="width:100%" type="email" id="email" required>
            </div>
            <div style="padding-bottom:12px" class="cqpim_form_item">
                <label style="display:block; padding-bottom:5px" for="vbr_sluby"><?php _e( 'Výběr služby' ) ?> </label>
                <select class="form-control" id="odwpca_client_srv" >
                    <option value="mikro"><?php _e( 'MIKRO' ) ?></option>
                    <option value="zaklad"><?php _e( 'ZÁKLAD' ) ?></option>
                    <option value="standard"><?php _e( 'STANDARD' ) ?></option>
                    <option value="maxim"><?php _e( 'MAXIM' ) ?></option>
                    <option value="vip"><?php _e( 'VIP' ) ?></option>
                </select>
            </div>
            <div style="padding-bottom:12px" class="cqpim_form_item">
                <label style="display:block; padding-bottom:5px" for="odwpca_client_ic"><?php _e( 'IČ' ) ?> </label>
                <input style="width:100%" class="form-control" type="text" id="odwpca_client_ic">
            </div>
            <div style="padding-bottom:12px" class="cqpim_form_item">
                <label style="display:block; padding-bottom:5px" for="odwpca_client_dic"><?php _e( 'DIČ' ) ?> </label>
                <input style="width:100%" class="form-control" type="text" id="odwpca_client_dic">
            </div>
            <div style="padding-bottom:12px" class="cqpim_form_item">
                <label style="display:block; padding-bottom:5px" for="odwpca_client_fupsc"><?php _e( 'Finanční úřad PSČ' ) ?> </label>
                <input style="width:100%" class="form-control" type="text" id="odwpca_client_fupsc">
            </div>
            <p><input type="submit" id="odwpca_submit_feform" value="<?php _e( 'Odeslat' ) ?>"></p>
            <div id="form_spinner" style="clear:both; display:none; background:url(http://crm.complextaxsolution.cz/wp-content/plugins/cqpim/css/img/ajax-loader.gif) center center no-repeat; width:16px; height:16px; padding:10px 0 0 5px; margin-top:15px"></div>
            <div style="margin-top:20px" id="odwpca_submit_feform_messages"></div>
        </form>
    </div>
</div><!-- .entry-content -->
<?php
        wp_enqueue_script(
                'upload-form-js',
                plugin_dir_url( 'cqpim/cqpim.php' ) . 'shortcodes/js/upload.js',
                array( 'jquery' ),
                '0.1.0',
                true
        );

        $data = array(
            'upload_url' => admin_url( 'async-upload.php' ),
            'ajax_url'   => admin_url( 'admin-ajax.php' ),
            'nonce'      => wp_create_nonce( 'media-form' ),
            'strings' => array(
                'uploading' => __( 'Uploading...', 'cqpim' ),
                'success' => __( 'Successfully uploaded', 'cqpim' ),
                'change' => __( 'Change File', 'cqpim' ),
                'error' => __( 'Failed to upload file. It may not be on our list of allowed extensions. Please try again.', 'cqpim' )
            ),
	);

        wp_localize_script( 'upload-form-js', 'upload_config', $data );
    }
endif;


if( !function_exists( 'odwpca_feform_scripts' ) ):
    /**
     * This function contains code which is slightly customized original CQPIM code.
     * @see cqpim/shortcodes/frontend_quote.php
     * @return void
     */
    function odwpca_feform_scripts() {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#odwpca_submit_feform').before(
        '<div style="padding-bottom:12px" class="cqpim_form_item"> ' +
            '<?php _e( 'Jsem člověk (SPAM kontrola)', 'cqpim' ) ?><span style="color:#F00">*</span> ' +
            '<input type="checkbox" id="human_conf" required>' +
        '</div>'
    );

    jQuery('#odwpca_feform').on('submit', function(e) {
        e.preventDefault();
        var spinner = jQuery('#form_spinner');
        var name = jQuery('#full_name').val();
        var company = jQuery('#company_name').val();
        var address = jQuery('#address').val();
        var postcode = jQuery('#postcode').val();
        var telephone = jQuery('#telephone').val();
        var email = jQuery('#email').val();
        var odwpca_client_ic = jQuery('#odwpca_client_ic').val();
        var odwpca_client_dic = jQuery('#odwpca_client_dic').val();
        var odwpca_client_fupsc = jQuery('#odwpca_client_fupsc').val();
        var odwpca_client_srv = jQuery('#odwpca_client_srv').val();
        var data = {
            'action' : 'odwpca_feform_submission',
            'name' : name,
            'company' : company,
            'address' : address,
            'postcode' : postcode,
            'telephone' : telephone,
            'email' : email,
            'odwpca_client_ic': odwpca_client_ic,
            'odwpca_client_dic': odwpca_client_dic,
            'odwpca_client_fupsc': odwpca_client_fupsc,
            'odwpca_client_srv': odwpca_client_srv
        };

        jQuery.ajax({
            url: '<?php echo admin_url() . 'admin-ajax.php'; ?>',
            data: data,
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                // show spinner
                spinner.show();
                // disable form elements while awaiting data
                jQuery('#odwpca_submit_feform').prop('disabled', true);
            },
        }).always(function(response) {
            //console.log(response);
        }).done(function(response){
            if(response.error == true) {
                spinner.hide();
                // re-enable form elements so that new enquiry can be posted
                jQuery('#odwpca_submit_feform').prop('disabled', false);
                jQuery('#odwpca_submit_feform_messages').html(response.message);
            } else {
                spinner.hide();
                // re-enable form elements so that new enquiry can be posted
                jQuery('#odwpca_submit_feform').prop('disabled', false);
                jQuery('#odwpca_submit_feform_messages').html(response.message);
            }
        });
    });
});
</script>
<?php
    }
endif;


if( !function_exists( 'odwpca_feform_submission' ) ):
    /**
     * This function contains code which is slightly customized original CQPIM code.
     * @see cqpim/cqpim_functions.php
     * @return void
     */
    function odwpca_feform_submission() {
        $data = isset( $_POST ) ? $_POST : '';

        if( empty( $data ) ) {
            $return =  array(
                'error' => true,
                'message' => '<span style="display:block; width:96%; padding:2%; color:#fff; background:#d9534f">' . __( 'There is missing data, please try again filling in every field.', 'cqpim' ) . '</span>',
            );

            header( 'Content-type: application/json' );
            echo json_encode( $return );
            exit();
        }

        unset( $data['action'] );
        $name = isset( $data['name'] ) ? $data['name'] : '';
        unset( $data['name'] );
        $company = isset( $data['company'] ) ? $data['company'] : '';
        unset( $data['company'] );
        $address = isset( $data['address'] ) ? $data['address'] : '';
        unset( $data['address'] );
        $postcode = isset( $data['postcode'] ) ? $data['postcode'] : '';
        unset( $data['postcode'] );
        $telephone = isset( $data['telephone'] ) ? $data['telephone'] : '';
        unset( $data['telephone'] );
        $email = isset( $data['email'] ) ? $data['email'] : '';
        unset( $data['email'] );
        $odwpca_client_ic = isset( $data['odwpca_client_ic'] ) ? $data['odwpca_client_ic'] : '';
        unset( $data['odwpca_client_ic'] );
        $odwpca_client_dic = isset( $data['odwpca_client_dic'] ) ? $data['odwpca_client_dic'] : '';
        unset( $data['odwpca_client_dic'] );
        $odwpca_client_fupsc = isset( $data['odwpca_client_fupsc'] ) ? $data['odwpca_client_fupsc'] : '';
        unset( $data['odwpca_client_fupsc'] );
        $odwpca_client_srv = isset( $data['odwpca_client_srv'] ) ? $data['odwpca_client_srv'] : '';
        unset( $data['odwpca_client_srv'] );

        if ( username_exists( $email ) || email_exists( $email ) ) {
            $return =  array(
                'error' => true,
                'message' => '<span style="display:block; width:96%; padding:2%; color:#fff; background:#d9534f">' . __('The email address entered is already in our system, please try again with a different email address or contact us.', 'cqpim') . '</span>',
            );

            header( 'Content-type: application/json' );
            echo json_encode( $return );
            exit();
        }

        $new_client = array(
            'post_type' => 'cqpim_client',
            'post_status' => 'private',
            'post_content' => '',
            'post_title' => $company,
        );

        $client_pid = wp_insert_post( $new_client, true );

        if( is_wp_error( $client_pid ) ) {
            $return =  array(
                'error' => true,
                'message' => '<span style="display:block; width:96%; padding:2%; color:#fff; background:#d9534f">' . __( 'Unable to create client entry, please try again or contact us.', 'cqpim' ) . '</span>',
            );

            header( 'Content-type: application/json' );
            echo json_encode( $return );
            exit();
        }

        $client_updated = array(
            'ID' => $client_pid,
            'post_name' => $client_pid,
        );

        wp_update_post( $client_updated );

        $client_details = array(
                'client_ref' => $client_pid,
                'client_company' => $company,
                'client_contact' => $name,
                'client_address' => $address,
                'client_postcode' => $postcode,
                'client_telephone' => $telephone,
                'client_email' => $email,
        );

        $passw = cqpim_random_string( 10 );
        $login = $email;
        $user_id = wp_create_user( $login, $passw, $email );
        $user = new WP_User( $user_id );
        $user->set_role( 'cqpim_client' );
        $client_details['user_id'] = $user_id;
        $client_ids = array();
        $client_ids[] = $user_id;

        update_post_meta( $client_pid, 'client_details', $client_details );
        update_post_meta( $client_pid, 'client_ids', $client_ids );
        update_post_meta( $client_pid, 'odwpca_client_ic', $odwpca_client_ic );
        update_post_meta( $client_pid, 'odwpca_client_dic', $odwpca_client_dic );
        update_post_meta( $client_pid, 'odwpca_client_fupsc', $odwpca_client_fupsc );
        update_post_meta( $client_pid, 'odwpca_client_srv', $odwpca_client_srv );

        $user_data = array(
            'ID' => $user_id,
            'display_name' => $name,
            'first_name' => $name,
        );

        wp_update_user( $user_data );

        $form_auto_welcome = get_option('form_auto_welcome');

        if( $form_auto_welcome == 1 ) {
            send_cqpim_welcome_email( $client_pid, $passw );
        }

        $new_quote = array(
            'post_type' => 'cqpim_quote',
            'post_status' => 'private',
            'post_content' => '',
            'post_title' => '',
        );

        $quote_pid = wp_insert_post( $new_quote, true );

        if( is_wp_error( $quote_pid ) ) {
            $return =  array(
                'error' => true,
                'message' => '<span style="display:block; width:96%; padding:2%; color:#fff; background:#d9534f">' . __( 'Unable to create quote, please try again or contact us.', 'cqpim' ) . '</span>',
            );

            header( 'Content-type: application/json' );
            echo json_encode( $return );
            exit();
        }

        $title = $company . ' - ' . __('Quote', 'cqpim') . ': ' . $quote_pid;
        $quote_updated = array(
            'ID' => $quote_pid,
            'post_title' => $title,
            'post_name' => $quote_pid,
        );

        wp_update_post( $quote_updated );

        $uploaded_files = array();
        $summary = '';

        foreach( $data as $key => $field ) {
            if( is_array( $field ) ) {
                $field = implode( ', ', $field );
            }

            $title = str_replace( '_', ' ', $key );
            $title = ucwords( $title );

            if( strpos( $title, 'Cqpimuploader' ) !== false ) {
                $file_object = get_post( $field );
                $title = str_replace( 'Cqpimuploader ', '', $title );
                $summary .= '<p><strong>' . $title . ': </strong> ' . $file_object->post_title . '</p>';
                $attachment_updated = array(
                    'ID' => $field,
                    'post_parent' => $quote_pid,
                );

                wp_update_post( $attachment_updated );
                update_post_meta( $field, 'cqpim', true );
            } else {
                $summary .= '<p><strong>' . $title . ': </strong> ' . $field . '</p>';
            }
        }

        $header = get_option( 'quote_header' );
        $header = str_replace( '%%CLIENT_NAME%%', $name, $header );
        $footer = get_option( 'quote_footer' );
        $footer = str_replace( '%%CURRENT_USER%%', '', $footer );
        $currency = get_option( 'currency_symbol' );
        $currency_code = get_option( 'currency_code' );
        $currency_position = get_option( 'currency_symbol_position' );
        $currency_space = get_option( 'currency_symbol_space' );

        update_post_meta( $quote_pid, 'currency_symbol', $currency );
        update_post_meta( $quote_pid, 'currency_code', $currency_code );
        update_post_meta( $quote_pid, 'currency_position', $currency_position );
        update_post_meta( $quote_pid, 'currency_space', $currency_space );

        $quote_details = array(
            'quote_type' => 'quote',
            'quote_ref' => $quote_pid,
            'client_id' => $client_pid,
            'quote_summary' => $summary,
            'quote_header' => $header,
            'quote_footer' => $footer,
            'client_contact' => $user_id
        );

        update_post_meta( $quote_pid, 'quote_details', $quote_details );

        $to = get_option( 'company_sales_email' );
        $attachments = array();
        $subject = get_option( 'new_quote_subject' );
        $content = get_option( 'new_quote_email' );
        $name_tag = '%%NAME%%';
        $link_tag = '%%QUOTE_URL%%';
        $company_tag = '%%COMPANY_NAME%%';
        $quote_link = admin_url() . 'post.php?post=' . $quote_pid . '&action=edit';
        $subject = str_replace( $name_tag, $name, $subject );
        $content = str_replace( $name_tag, $name, $content );
        $content = str_replace( $link_tag, $quote_link, $content );
        $content = str_replace( $company_tag, $sender_name, $content );

        cqpim_send_emails( $to, $subject, $content, '', $attachments, 'sales' );

        $return =  array(
            'error' => false,
            'message' => '<span style="display:block; width:96%; padding:2%; color:#fff; background:#8ec165">' . __('Quote request submitted, we\'ll get back to you soon!', 'cqpim') . '</span>',
        );

        header( 'Content-type: application/json' );
        echo json_encode( $return );
        exit();
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
        // Front-end form shortcode
        add_shortcode( 'odwpca_frontend_form' , 'odwpca_feform_shortcode' );
        add_action( 'wp_footer', 'odwpca_feform_scripts' );
        add_action( 'wp_ajax_nopriv_odwpca_feform_submission', 'odwpca_feform_submission' );
	add_action( 'wp_ajax_odwpca_feform_submission', 'odwpca_feform_submission' );
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