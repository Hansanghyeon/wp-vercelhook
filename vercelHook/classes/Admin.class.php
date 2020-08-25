<?php defined('ABSPATH') or die('Nothing to see here');

if (!class_exists('VERCEL_HOOK_Admin')) {

  class VERCEL_HOOK_Admin
  {

    public static function init()
    {
      return new self();
    }

    public static function active()
    {
    }

    public function __construct()
    {
      add_action('admin_enqueue_scripts', array($this, 'admin_menu_style'));
      add_action('admin_menu', array($this, 'admin_menu'));
      add_action('admin_init', array($this, 'registerAndBuildFields'));
      if (get_option(VERCEL_HOOK_Name) != '') {
        add_action('admin_enqueue_scripts', array($this, 'admin_menu_bar_style'));
        add_action('admin_bar_menu', array($this, 'add_deploy_button'), 100);
      }
    }

    public function add_deploy_button($wp_admin_bar)
    {
      $args = array(
        'id' => 'vercelHookReqBtn', // ID of the button added. 
        'title' => '<img class="vercel_icon" src="' . VERCEL_HOOK_URL . '/img/icon.jpg"/><span class="cloth">Deploy Now</span>',
        'href' => get_option(VERCEL_HOOK_Name)
      );

      $wp_admin_bar->add_node($args);
    }

    public function admin_menu()
    {
      add_menu_page(
        'VercelHook',
        'VercelHook',
        'manage_options',
        'vercel-hook',
        array($this, 'admin_list'),
        VERCEL_HOOK_URL . '/img/icon.jpg'
      );
    }

    public function admin_list()
    {
      include VERCEL_HOOK_PATH . 'pages/setting.php';
    }

    public function admin_menu_style($hook)
    {
      wp_enqueue_style('VERCEL-admin-menu', VERCEL_HOOK_URL . '/css/admin_menu.css', array(), '1.0.0');

      if ($hook == 'toplevel_page_vercel-hook') {
        wp_enqueue_style('VERCEL-page', VERCEL_HOOK_URL . '/css/style.min.css', array(), '1.0.0');
      }
    }
    public function admin_menu_bar_style()
    {
      wp_enqueue_style('VERCEL-admin-menu-bar', VERCEL_HOOK_URL . '/css/admin_menu_bar.css', array(), '1.0.0');
      wp_enqueue_script('VERCEL-admin-menu-bar', VERCEL_HOOK_URL . '/js/index.js', array(), '1.0.0');
    }


    public function registerAndBuildFields()
    {
      /**
       * First, we add_settings_section. This is necessary since all future settings must belong to one.
       * Second, add_settings_field
       * Third, register_setting
       */
      add_settings_section(
        // ID used to identify this section and with which to register options
        'plugin_name_general_section',
        // Title to be displayed on the administration page
        '',
        // Callback used to render the description of the section
        array($this, 'plugin_name_display_general_account'),
        // Page on which to add this section of options
        VERCEL_HOOK_Field
      );
      unset($args);
      $args = array(
        'type'      => 'input',
        'subtype'   => 'text',
        'id'    => VERCEL_HOOK_Name,
        'name'      => VERCEL_HOOK_Name,
        'required' => 'true',
        'get_options_list' => '',
        'value_type' => 'normal',
        'wp_data' => 'option'
      );
      add_settings_field(
        VERCEL_HOOK_Name,
        'Vercel WebHook Url',
        array($this, 'plugin_name_render_settings_field'),
        VERCEL_HOOK_Field,
        'plugin_name_general_section',
        $args
      );


      register_setting(
        VERCEL_HOOK_Field,
        VERCEL_HOOK_Name
      );
    }
    public function plugin_name_display_general_account()
    {
      $html = '<p>Vercel > 프로젝트 > Settings > Git Integration > Deploy Hooks</p>';
      $html .= '<p>해당 위치에서 훅을 생성후 해당 내용의 URL을 복사해서 아래 입력하세요</p>';
      echo $html;
    }

    public function plugin_name_render_settings_field($args)
    {
      if ($args['wp_data'] == 'option') {
        $wp_data_value = get_option($args['name']);
      } elseif ($args['wp_data'] == 'post_meta') {
        $wp_data_value = get_post_meta($args['post_id'], $args['name'], true);
      }

      switch ($args['type']) {

        case 'input':
          $value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
          if ($args['subtype'] != 'checkbox') {
            $prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">' . $args['prepend_value'] . '</span>' : '';
            $prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
            $step = (isset($args['step'])) ? 'step="' . $args['step'] . '"' : '';
            $min = (isset($args['min'])) ? 'min="' . $args['min'] . '"' : '';
            $max = (isset($args['max'])) ? 'max="' . $args['max'] . '"' : '';
            if (isset($args['disabled'])) {
              // hide the actual input bc if it was just a disabled input the informaiton saved in the database would be wrong - bc it would pass empty values and wipe the actual information
              echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '_disabled" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="' . $args['id'] . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;
            } else {
              echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" "' . $args['required'] . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr($value) . '" />' . $prependEnd;
            }
            /*<input required="required" '.$disabled.' type="number" step="any" id="'.$this->plugin_name.'_cost2" name="'.$this->plugin_name.'_cost2" value="' . esc_attr( $cost ) . '" size="25" /><input type="hidden" id="'.$this->plugin_name.'_cost" step="any" name="'.$this->plugin_name.'_cost" value="' . esc_attr( $cost ) . '" />*/
          } else {
            $checked = ($value) ? 'checked' : '';
            echo '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" "' . $args['required'] . '" name="' . $args['name'] . '" size="40" value="1" ' . $checked . ' />';
          }
          break;
        default:
          # code...
          break;
      }
    }
  }
}

if (method_exists('VERCEL_HOOK_Admin', 'init')) {
  VERCEL_HOOK_Admin::init();
}
