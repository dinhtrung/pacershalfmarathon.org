<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_istt');

/** MySQL database username */
define('DB_USER', 'istt_wp');

/** MySQL database password */
define('DB_PASSWORD', 'istt@wp');

/** MySQL hostname */
define('DB_HOST', 'db');

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
define('AUTH_KEY',         ')%0rUu#<@,_z_s|2@ET)Z}G!b>>y5~ZP(|g{>.f#kX]~Vnh9J*S2ZwAZA8Q?m q6');
define('SECURE_AUTH_KEY',  '[]iPzx5/EvxwP+Bv!(ZJ-Ep3mH?<l>W/U~%<7 @P.oKp-K<ZbQ=]z)qUT4CP)KG3');
define('LOGGED_IN_KEY',    ']UofNUo)8m^zZ:=[C(/`ok4(1YHe5IRd$}TNhq=7oHI0T<JpYlxIwmB<8&iGQT*G');
define('NONCE_KEY',        '6WV&%$O i[>ZT}+QnsVNl`t]n[vd:=jX$fDF>yGK4fiA@e=e)Wuw=LOAXdK}fCNk');
define('AUTH_SALT',        'pAq@?|&nT6$DG@AJ2jPPZ{Qwm%{kDs#=%bg=]~>v$DPA3|Lj`UF2E/~ci1~`Hw{S');
define('SECURE_AUTH_SALT', 'gcTu#x@x5{~BWPEtu#kD]u>0c@I1qO/.SbbgZA5s&H$0fPHU^[Rk$.X|*!1JKU`y');
define('LOGGED_IN_SALT',   'Pyl<?P$J89J6fAr62coIi,)4]LCHz9ZNV{Of4t8bzvkaFXuYW%YVF6FkgSRejj?(');
define('NONCE_SALT',       'axfx>ykYvMM6oKgz)r&tp(13Jl0#8!di!{%mZ0[w}$W4:^xly?~y}$5-=Z92=W.2');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */
define('FS_METHOD', 'direct');
define('WP_MAX_MEMORY_LIMIT', '256M' );

/* FIXME: Change after done development locally */
define('WP_HOME',"http://localhost:28000" );
define('WP_SITEURL',"http://localhost:28000");


/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
