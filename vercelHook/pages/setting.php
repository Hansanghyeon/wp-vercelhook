<?php defined('ABSPATH') or die('Nothing to see here.'); // Security (disable direct access) 
?>

<div class="wrap vercel-hook-markup">
  <h2>Vercel Hook Settings</h2>
  <?php settings_errors(); ?>
  <form method="POST" action="options.php">
    <?php
    settings_fields(VERCEL_HOOK_Field);
    do_settings_sections(VERCEL_HOOK_Field);
    ?>
    <?php submit_button(); ?>
  </form>
</div>