<?php
/**
 * Plugin Name: d-mind WP Theme Toggler
 * Description: Bietet einen hübschen Schalter für den Dunkelmodus und Basis-CSS
 * Version: 1.0
 * Author: Jens Fuchs
 * Author URI: https://www.d-mind.de
 */

if (!defined('ABSPATH')) {
	exit;
}

if (!defined('DMIND_TOGGLER_PLUGIN_DIR')) {
	define('DMIND_TOGGLER_PLUGIN_DIR', 'dmind-wp-theme-toggler');
}


function dmind_theme_toggler_page(): void {
	add_submenu_page(
		'options-general.php',      // Parent menu slug
		'Theme Toggler',            // Page title
		'Theme Toggler',            // Menu title (Fehlte zuvor)
		'manage_options',           // Capability
		'dmind-theme-toggler',      // Menu slug
		'dmind_theme_toggler'       // Callback function
	);
}

add_action('admin_menu', 'dmind_theme_toggler_page');

// Funktion für die Admin-Seite
function dmind_theme_toggler(): void {
	echo '<div class="wrap">';
	echo '<h1>' . __('Theme-Toggler-Einstellungen (aktuell nichts einzustellen)', DMIND_TOGGLER_PLUGIN_DIR) . '</h1>';
	echo '<form method="post" action="">';
	echo '<input type="submit" name="save_alt_texts" class="button-primary" value="'.__('Speichern', DMIND_TOGGLER_PLUGIN_DIR).'">';
	echo '</form>';
	echo '</div>';
}


function dmind_theme_toggler_item($items, $args): string {
	#if ($args->theme_location === 'primary') {
	$icon = '<li class="menu-item custom-theme-toggle">
            <button aria-label="'.__('Dunkelmodus ein- bzw. ausschalten', DMIND_TOGGLER_PLUGIN_DIR).'" aria-live="polite" class="theme-toggle" id="theme-toggle" title="'.__('Zwischen hell und dunkel wechseln', DMIND_TOGGLER_PLUGIN_DIR).'">
                <svg aria-hidden="true" class="sun-and-moon" height="24" viewBox="0 0 24 24" width="24">
                    <mask class="moon" id="moon-mask">
                        <rect fill="white" height="100%" width="100%" x="0" y="0"></rect>
                        <circle cx="24" cy="10" fill="black" r="6"></circle>
                    </mask>
                    <circle class="sun" cx="12" cy="12" fill="currentColor" mask="url(#moon-mask)" r="6"></circle>
                    <g class="sun-beams" stroke="currentColor">
                        <line x1="12" x2="12" y1="1" y2="3"></line>
                        <line x1="12" x2="12" y1="21" y2="23"></line>
                        <line x1="4.22" x2="5.64" y1="4.22" y2="5.64"></line>
                        <line x1="18.36" x2="19.78" y1="18.36" y2="19.78"></line>
                        <line x1="1" x2="3" y1="12" y2="12"></line>
                        <line x1="21" x2="23" y1="12" y2="12"></line>
                        <line x1="4.22" x2="5.64" y1="19.78" y2="18.36"></line>
                        <line x1="18.36" x2="19.78" y1="5.64" y2="4.22"></line>
                    </g>
                </svg>
            </button>
        </li>';

	// Menüpunkt am Ende des Menüs hinzufügen
	$items .= $icon;
	#}
	return $items;
}
add_filter('wp_nav_menu_items', 'dmind_theme_toggler_item', 10, 2);

/**
 * Enqueue scripts and styles
 */
add_action('wp_enqueue_scripts', function () {
	$csspath = WP_PLUGIN_DIR . '/'.DMIND_TOGGLER_PLUGIN_DIR.'/assets/css/';
	$filescss = preg_grep('/^([^.])/', scandir($csspath));
	foreach ($filescss as $filename) {
		$path = $csspath . $filename;
		$pathrel = plugins_url(DMIND_TOGGLER_PLUGIN_DIR) . '/assets/css/' . $filename;
		if (is_file($path)) {
			$extension = pathinfo($path, PATHINFO_EXTENSION);
			if ($extension === 'css') {
				wp_enqueue_style($filename, $pathrel, [], filemtime($path));
			}
		}
	}
}, 30);