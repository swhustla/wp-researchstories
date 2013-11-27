<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Heroku Postgres settings - from Heroku Environment ** //
$db = parse_url($_ENV["DATABASE_URL"]);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', trim($db["path"],"/"));

/** MySQL database username */
define('DB_USER', $db["user"]);

/** MySQL database password */
define('DB_PASSWORD', $db["pass"]);

/** MySQL hostname */
define('DB_HOST', $db["host"]);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */

define('AUTH_KEY',         ']{8L#JR~xiv-lqrM]q3-= HW-6(FS]PUC;[N65=>Q!kO1<$389Ou4dI+v}NI{C`h');
define('SECURE_AUTH_KEY',  'H:kI2IqM1:>gH@o0h>3-55@_f-j2~UCj>xtfvAkWoayqNtm lL&cZUAV%US|Oix:');
define('LOGGED_IN_KEY',    '0Y,H,8SQ~dAyfD&~td]=%bjoED9/L|3z3ebxCuJvxv czN+V:Gm8<v9~+G:)Yka]');
define('NONCE_KEY',        'ifTP(5`q@`.=TZvn]jQKvuSYZ~_z&/m{*+F*86 9pWQms6VbDg2xl) ,P=_$E0dP');
define('AUTH_SALT',        'mD1ItBiMYe/t<5CnEU9?SA}tSwASu$G:=o7+$/%nC_tBU}VNxVd^B}!V8({xKwvL');
define('SECURE_AUTH_SALT', '<jGQL #D|@tleT$@TNSDIwI}7+o%Pe.AC1%u6p|`HowfiYV7Hh.e-M^%sKz-;_.+');
define('LOGGED_IN_SALT',   'uu#Ve%971d)0yOcfwH:x.)|[B8Bo8`Gj09B)H3L=Ftga6rxeH5O)jfy=|`-Q;E9/');
define('NONCE_SALT',       '6F-X=T3rouOC+o?2a2mc}|{+qf+j|d{kWiGERtH:a%z_ku$/nh[SQ1qs11`:2e@&');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
