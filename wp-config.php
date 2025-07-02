<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 's1203600i3' );

/** Database username */
define( 'DB_USER', 's1203600i3' );

/** Database password */
define( 'DB_PASSWORD', 'OKa6Jfi0' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '}4wW:Tm8IQ&k5y Uoj,7}+[cY:.tU5y,luC@#&-8>>|tp3hJ4z<,Ztc~,6N?cvh~' );
define( 'SECURE_AUTH_KEY',   'kO;Sp-t<v}:2YD/=Q;u7<:W4tZ|#KRoww?EF]4O+KsZ7LbsQXV2QRQ.&Dstwmt3~' );
define( 'LOGGED_IN_KEY',     'V_t_ftm9:lWa^^|RmNd(M>FPG(2XPC@-Nc,y!v@uES,f}U(?K,#E_`4T7k+P8B/h' );
define( 'NONCE_KEY',         'Ja}-j~L52DABw6r,A;hg`YuOACj[$3J37H$AA8z=$m0#p*Q)Ekf{Mll~d`*XTr,;' );
define( 'AUTH_SALT',         'ap t/Ycj~<L,(Um(*,^5Nec5gWeD[|kyOTf&*Sap9cS1Aef|</~KWdkg@MP2ov]~' );
define( 'SECURE_AUTH_SALT',  'pPG*^VTGt!9DsyPu4^#$ogpu#JRt+o4L!_!N)GUft<( MhIr03t<=%)ql_<K1hc~' );
define( 'LOGGED_IN_SALT',    '/3LJ19zUvly:`xzw6inhV#-[AJc 9XSmQ#*<u]lMMGBl5c`%uMx?VbMCLJ(n=^4}' );
define( 'NONCE_SALT',        'Cr{{6vW(V0b,5_qPS&n` 8zLBXa/3hjd$shQ0hdPB _x6-qHc.GKrg/^7t>rl+S,' );
define( 'WP_CACHE_KEY_SALT', 'l ~krP}dt@&lH59d8soa?~gz.FgsFZYTLY4S;po!a;{]y~zShhS]bH.xE)v7!c}@' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */

/**
 * Don't send the default new blog notification
 */
function wp_new_blog_notification() {}


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'DISALLOW_FILE_EDIT', true );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
