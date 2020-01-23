<?php
	/**
	 * Plugin Name: Custom Meta User
	 * Plugin URI:
	 * Description: Plugin for create custom fields
	 * Text Domain: cstm-mt-sr
	 * Version: 1.0
	 * Author:
	 * Author URI:
	 **/
if( ! function_exists( 'cstm_mt_sr_create_menu' ) ) {
  function cstm_mt_sr_create_menu() {
    add_menu_page( 'Custom User Meta', 'Custom User Meta', 'manage_options', 'custom-meta-user.php', 'cstm_mt_rs_settings' );
  }
}

if ( ! function_exists( 'cstm_mt_sr_init' ) ) {
  function cstm_mt_sr_init() {
    global $cstm_mt_sr_options;
    if ( empty( $cstm_mt_sr_options ) ) {
      cstm_mt_sr_options_default_options();
    }
    if ( isset( $_GET['page'] ) && "custom-meta-user.php" == $_GET['page'] ) {
      cstm_mt_sr_options_default_options();
    }
    //Show fields in register user
    if( in_array( 'registration', $cstm_mt_sr_options['additional_meta'] ) ){
      add_action( 'register_form','cstm_mt_sr_show_fields');
      add_action( 'register_new_user', 'cstm_mt_sr_show_fields' );
    }
  }
}
//Function register defoult option
if ( ! function_exists ( 'cstm_mt_sr_options_default_options' ) ) {
  function cstm_mt_sr_options_default_options() {
    global $cstm_mt_sr_options;

    $cstm_mt_sr_options_default_options = array(
      'pagination'				=> 1,
      'pagination_arrow'  => 1,
      'number_user'				=> 1,
      'additional_meta'   => array( 'edit' ),
    );

    if ( ! get_option( 'cstm_mt_sr_options' ) )
      add_option( 'cstm_mt_sr_options', $cstm_mt_sr_options_default_options );

    $cstm_mt_sr_options = get_option( 'cstm_mt_sr_options' );

  }
}
//Function display settings page plugin
if( ! function_exists( 'cstm_mt_rs_settings' ) ) {
  function cstm_mt_rs_settings() {
    global $cstm_mt_sr_options;

    $plugin_basename = plugin_basename( __FILE__ );
    if ( isset( $_REQUEST['cstm_mt_rs_submit'] ) && check_admin_referer( $plugin_basename, 'cstm_mt_rs_nonce_name' ) ) {
      $cstm_mt_sr_options['pagination'] = isset( $_REQUEST['cstm_mt_sr_pagination'] ) ? 1 : 0;
      $cstm_mt_sr_options['pagination_arrow'] = isset( $_REQUEST['cstm_mt_sr_pagination_arrow'] ) ? 1 : 0;

      $cstm_mt_sr_options['number_user'] = intval( $_REQUEST['cstm_mt_sr_number_user'] );

      $cstm_mt_sr_options['additional_meta'] = isset( $_REQUEST['cstm_mt_sr_additional_meta'] ) ? $_REQUEST['cstm_mt_sr_additional_meta'] : array();
      foreach ( (array)$cstm_mt_sr_options['additional_meta'] as $key => $position ) {
        if ( ! in_array( $position , array( 'edit', 'add' , 'registration' ) ) )
          unset( $cstm_mt_sr_options['additional_meta'][ $key ] );
      }

      update_option( 'cstm_mt_sr_options', $cstm_mt_sr_options );
    }?>
    <div class="wrap">
      <h1>Custom Meta User <?php _e( 'Settings', 'cstm-mt-sr' )?></h1>
        <form id="cstm_mt_rs_settings_form" class="cstm_mt_rs_form" enctype="multipart/form-data" method="post" action="admin.php?page=custom-meta-user.php" autocomplete="on">
          <table class="form-table">
            <tbody>
              <tr valign="top">
                <th scope="row">
                  <label><?php _e( 'Enable pagination', 'cstm-mt-sr' ); ?></label>
                </th>
                <td>
                  <input type="checkbox" name="cstm_mt_sr_pagination" value="1" <?php checked( $cstm_mt_sr_options['pagination'] ); ?> />
                </td>
              </tr>
              <tr valign="top">
                <th scope="row">
                  <label><?php _e( 'Enable arrow pagination', 'cstm-mt-sr' ); ?></label>
                </th>
                <td>
                  <input type="checkbox" name="cstm_mt_sr_pagination_arrow" value="1" <?php checked( $cstm_mt_sr_options['pagination_arrow'] ); ?> />
                </td>
              </tr>
              <tr>
                <th scope="row"><?php _e( 'How many users', 'cstm-mt-sr' ); ?></th>
                <td>
                  <label>
                    <input type="number" required name="cstm_mt_sr_number_user" class="small-text" value="<?php echo $cstm_mt_sr_options['number_user']; ?>" min="1" max="50" />&nbsp;
                  </label>
                </td>
              </tr>
              <tr>
                <th><?php _e( 'Location of additional fields', 'cstm-mt-sr' ); ?></th>
                <td>
                  <fieldset>
                    <label>
                      <input type="checkbox" name="cstm_mt_sr_additional_meta[]" value="edit" <?php if ( checked( in_array( 'edit', $cstm_mt_sr_options['additional_meta']  ) ) ) echo 'checked="checked"'; ?> />
                        <?php _e( 'Edit user page', 'cstm-mt-sr' ); ?>
                    </label>
                    <br/>
                    <label>
                      <input type="checkbox" name="cstm_mt_sr_additional_meta[]" value="add" <?php if ( checked( in_array( 'add', $cstm_mt_sr_options['additional_meta'] ) ) ) echo 'checked="checked"'; ?> />
                        <?php _e( 'Add user page', 'cstm-mt-sr' ); ?>
                    </label>
                    <br/>
                    <label>
                      <input type="checkbox" name="cstm_mt_sr_additional_meta[]" value="registration" <?php if ( checked( in_array( 'registration', $cstm_mt_sr_options['additional_meta'] ) ) ) echo 'checked="checked"'; ?> />
                        <?php _e( 'Registration', 'cstm-mt-sr' ); ?>
                    </label>
                  </fieldset>
                </td>
              </tr>
            </tbody>
          </table>
          <p>
            <input type="hidden" name="cstm_mt_rs_submit" value="submit" />
            <input id="cstm-mt-rs-submit-button" type="submit" class="button-primary" name="cstm_mt_rs_submit" value="<?php _e( 'Save Changes', 'cstm-mt-sr' ); ?>" />
          </p>
          <?php wp_nonce_field( $plugin_basename, 'cstm_mt_rs_nonce_name' ); ?>
      </form>
  </div>
  <?php
  }
}


//Function for display custom fields on edit user and add user page
if ( ! function_exists ( 'cstm_mt_sr_show_profile_fields' ) ) {
  function cstm_mt_sr_show_profile_fields( $user ) {
      $private_secret_key = '1f4276388ad3214c873428dbef42243f';?>
      <h3><?php _e( 'Additional information' , 'cstm-mt-sr' ) ?></h3>
      <table class="form-table">
        <tr>
          <th>
            <label for="address">Address</label>
          </th>
          <td>
            <input type="text" name="address" id="address" value="<?php echo cstm_mt_sr_decrypt( esc_attr( get_the_author_meta( 'address',$user->ID ) ), $private_secret_key ) ;?>" class="regular-text" /><br />
          </td>
        </tr>
        <tr>
          <th>
            <label for="phone">Phone</label>
          </th>
          <td>
            <input type="number" name="phone" id="phone" value="<?php echo cstm_mt_sr_decrypt( esc_attr( get_the_author_meta( 'phone',$user->ID ) ), $private_secret_key ) ;?>" class="regular-text" /><br />
          </td>
        </tr>
        <tr>
          <th>
            <label for="gender">Sex</label>
          </th>
          <td><?php $gender = cstm_mt_sr_decrypt( get_the_author_meta( 'gender', $user->ID ),$private_secret_key ); ?>
          <ul>
            <li><label><input value="men" name="gender"<?php if ( $gender == 'men') { ?> checked="checked"<?php } ?> type="radio" /> Men</label></li>
            <li><label><input value="woman"  name="gender"<?php if ( $gender == 'woman') { ?> checked="checked"<?php } ?> type="radio" /> Woman</label></li>
          </ul>
        </td>
        </tr>
        <th>
          <label for="marital-status">Marital status</label>
        </th>
        <td><?php $marital_status = cstm_mt_sr_decrypt(get_the_author_meta('marital-status',$user->ID),$private_secret_key); ?>
          <ul>
            <li><label><input value="married" name="marital-status"<?php if ( $marital_status == 'married' ) { ?> checked="checked"<?php } ?> type="radio" />Married</label></li>
            <li><label><input value="single"  name="marital-status"<?php if ( $marital_status == 'single' ) { ?> checked="checked"<?php } ?> type="radio" />Single</label></li>
          </ul>
        </td>
      </table>
<?php }
}
//Function for display custom fields in register page
if ( ! function_exists ( 'cstm_mt_sr_show_fields' ) ) {
  function cstm_mt_sr_show_fields() {
    ?>
      <p>
        <label><?php _e( 'Address', 'cstm-mt-sr' )?><br/>
        <input id="address" class="input" type="text" name="address" /></label>
      </p>
      <p>
        <label><?php _e( 'Phone', 'cstm-mt-sr' )?><br/>
        <input id="phone" class="input" type="text" name="phone" /></label>
      </p>
      <p>
        <label><?php _e('Sex','cstm-mt-sr')?></label>
      <ul>
        <li><label><input value="men" name="gender" type="radio" /> Men</label></li>
        <li><label><input value="woman"  name="gender" type="radio" /> Woman</label></li>
      </ul>
      </p>
      <p>
        <label><?php _e('Marital status','cstm-mt-sr')?></label>
      <ul>
        <li><label><input value="married" name="marital-status" type="radio" />Married</label></li>
        <li><label><input value="single"  name="marital-status" type="radio" />Single</label></li>
      </ul>
      </p>
  <?php
  }
}
if ( ! function_exists ( 'cstm_mt_sr_encrypt' ) ) {
  function cstm_mt_sr_encrypt($message, $encryption_key){
      $key = hex2bin($encryption_key);
      $nonceSize = openssl_cipher_iv_length('aes-256-ctr');
      $nonce = openssl_random_pseudo_bytes($nonceSize);
      $ciphertext = openssl_encrypt(
        $message,
        'aes-256-ctr',
        $key,
        OPENSSL_RAW_DATA,
        $nonce
      );
      return base64_encode( $nonce.$ciphertext );
    }
  }

if ( ! function_exists ( 'cstm_mt_sr_decrypt' ) ) {
  function cstm_mt_sr_decrypt( $message, $encryption_key ){
      $key = hex2bin( $encryption_key );
      $message = base64_decode( $message );
      $nonceSize = openssl_cipher_iv_length( 'aes-256-ctr' );
      $nonce = mb_substr( $message, 0, $nonceSize, '8bit' );
      $ciphertext = mb_substr( $message, $nonceSize, null, '8bit' );
      $plaintext= openssl_decrypt(
        $ciphertext,
        'aes-256-ctr',
        $key,
        OPENSSL_RAW_DATA,
        $nonce
      );
      return $plaintext;
    }
  }
  //Function for saving custom meta in db
if ( ! function_exists ( 'cstm_mt_sr_save_profile_fields' ) ) {
    function cstm_mt_sr_save_profile_fields( $user_id ){
      $private_secret_key = '1f4276388ad3214c873428dbef42243f';

      $address_string = $_POST['address'];
      $address_encrypted = cstm_mt_sr_encrypt( $address_string, $private_secret_key );

      $phone_string = $_POST['phone'];
      $phone_encrypted = cstm_mt_sr_encrypt( $phone_string, $private_secret_key );

      $gender_string = $_POST['gender'];
      $gender_encrypted = cstm_mt_sr_encrypt( $gender_string, $private_secret_key );

      $marital_status_string = $_POST['marital-status'];
      $marital_status_encrypted = cstm_mt_sr_encrypt( $marital_status_string, $private_secret_key );

      update_user_meta( $user_id, 'address', $address_encrypted );
      update_user_meta( $user_id, 'phone', $phone_encrypted );
      update_user_meta( $user_id, 'gender', $gender_encrypted );
      update_user_meta( $user_id, 'marital-status', $marital_status_encrypted );
    }
  }
//Function for display shortcode in front-end
if( ! function_exists( 'cstm_mt_sr_show_user' ) ) {
  function cstm_mt_sr_show_user() {
    global $cstm_mt_sr_options;
    if ( empty( $cstm_mt_sr_options ) ) {
      cstm_mt_sr_options_default_options();
    }

    $number   = intval($cstm_mt_sr_options['number_user']);
    $paged    = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $offset   = ($paged - 1) * $number;
    $users    = get_users();
    $query    = get_users('&offset='.$offset.'&number='.$number);
    $total_users = count($users);
    $total_query = count($query);
    $total_pages = intval($total_users / $number);
    echo '<ul id="users">';

    foreach($query as $q) { ?>
        <li class="user clearfix">
          <div class="user-avatar">
            <?php echo get_avatar( $q->ID, 80 ); ?>
          </div>
          <div class="user-data">
            <h4 class="user-name">
              <a href="<?php echo get_author_posts_url($q->ID);?>">
                <?php echo get_the_author_meta('display_name', $q->ID);?>
              </a>
            </h4>
          </div>
        </li>

      <?php }
      echo '</ul>';
      if( 1==$cstm_mt_sr_options['pagination'] ){
        if ($total_users > $total_query) {
          echo '<div id="pagination" class="clearfix">';
            $current_page = max(1, get_query_var('paged'));
            $big = 999999999;
            echo paginate_links(array(
              'base' => str_replace($big,'%#%',esc_url(get_pagenum_link($big))),
              'format' => '?page/%#%/',
              'current' => $current_page,
              'total' => $total_pages,
              'prev_next' => ( 1==$cstm_mt_sr_options['pagination_arrow'] ) ? true : false
              ));
          echo '</div>';
          }
        }
    }
}
//Function display custom fields for select in setting page
if( ! function_exists( 'cstm_mt_sr_admin' ) ) {
  function cstm_mt_sr_admin(){
    global $cstm_mt_sr_options;

    if ( empty( $cstm_mt_sr_options ) ) {
      cstm_mt_sr_options_default_options();
    }
  //Show fields in edit user
    if( in_array( 'edit', $cstm_mt_sr_options['additional_meta'] ) ){
      add_action( 'edit_user_profile', 'cstm_mt_sr_show_profile_fields' );
      add_action( 'show_user_profile', 'cstm_mt_sr_show_profile_fields' );
    }
  //Show fields in add user
    if( in_array( 'add', $cstm_mt_sr_options['additional_meta'] ) ){
      add_action( 'user_new_form', 'cstm_mt_sr_show_profile_fields' );
    }
  }
}
//function delete list style in regitster page
if( ! function_exists( 'cstm_mt_sr_delete_li' ) ) {
  function cstm_mt_sr_delete_li() { ;?>
    <style type="text/css">
        li {
          list-style-type: none;
        }
    </style>
  <?php }
}

if( ! function_exists( 'cstm_mt_sr_add_shortcode_button' ) ) {
  function cstm_mt_sr_add_shortcode_button() {
    if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
      return;
    }
    if ( 'true' == get_user_option( 'rich_editing' ) ) {
      add_filter( 'mce_external_plugins', 'cstm_mt_sr_button_script' );
      add_filter( 'mce_buttons', 'cstm_mt_sr_button' );
    }
  }
}

if( ! function_exists( 'cstm_mt_sr_button_script' ) ) {
  function cstm_mt_sr_button_script( $plugin_array ) {
    $plugin_array['true_mce_button'] = plugins_url() .'/custom-meta-user/js/script.js';
    return $plugin_array;
  }
}

if( ! function_exists( 'cstm_mt_sr_button' ) ) {
  function cstm_mt_sr_button( $buttons ) {
    array_push( $buttons, 'true_mce_button' );
    return $buttons;
  }
}
function cstm_mt_sr_enqueue_scripts() {
  wp_enqueue_style( 'cstm_mt_sr_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
}

register_uninstall_hook( __FILE__, 'my_uninstall_hook' );
function my_uninstall_hook(){
	delete_option('cstm_mt_sr_options');
}
add_shortcode('custom-meta-user', 'cstm_mt_sr_show_user');
add_action( 'wp_enqueue_scripts', 'cstm_mt_sr_enqueue_scripts' );

add_action( 'init', 'cstm_mt_sr_init' );
add_action( 'admin_init', 'cstm_mt_sr_admin' );
add_action( 'admin_menu', 'cstm_mt_sr_create_menu' );
add_action( 'user_register', 'cstm_mt_sr_save_profile_fields');
add_action( 'personal_options_update', 'cstm_mt_sr_save_profile_fields' );
add_action( 'edit_user_profile_update', 'cstm_mt_sr_save_profile_fields' );
add_action( 'login_enqueue_scripts', 'cstm_mt_sr_delete_li' );
add_action('admin_head', 'cstm_mt_sr_add_shortcode_button');
?>