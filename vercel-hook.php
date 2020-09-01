<?php defined('ABSPATH') or die('Nothing to see here.');
/**
 * Plugin Name: Vercel Hook
 * Plugin URI: 
 * Description: Vercel의 웹훅 요청하는 플러그인입니다.
 * Version: 1.1.3
 * Author: 한상현
 * Author URI: https://github.com/Hansanghyeon
 * License: GLPv2 or later
 */

define('VERCEL_HOOK_PATH', plugin_dir_path(__FILE__) . '/src');
define('VERCEL_HOOK_URL', plugins_url('', __FILE__) . '/src');
define('VERCEL_HOOK_Name', 'vercel_webhook_url');
define('VERCEL_HOOK_Field', 'vercel_hook_general_settings');

require_once VERCEL_HOOK_PATH . '/classes/Admin.class.php';

register_activation_hook(__FILE__, array('VERCEL_HOOK_Admin', 'active'));
