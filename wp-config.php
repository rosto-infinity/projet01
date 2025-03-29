<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'projet01' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'v2b>y`jE<c#+D^1jRuY/}lL^S|!8T1Ck$?iY?rz0%S1D=[F}ut4sw]FOy($?6!FG' );
define( 'SECURE_AUTH_KEY',  '(?N$E%/e;v^8Lc_~+U,*|#kw zj0+h21DL0fh^r>g_hbG>UPvy~I[ClY}&#04[|W' );
define( 'LOGGED_IN_KEY',    'K-FdZ }35}<]&d1[Ba/5AFRf4u8&Mnm45l7Br8$?,9-<?0tQWG0cqc?aVz1%N1hM' );
define( 'NONCE_KEY',        'lIyBrSZ`qojuDC-TcYcRa|axV%RO;lPZmVNUjpTF;Ckw+WU#!SNv5&S,DsJH93di' );
define( 'AUTH_SALT',        'AVbeQ5A*2e,ti(hTFqo!.>q.i8Tv/^q<8?^E3d1WDMa+:]]l[WF7pgVW(*6/&,|Q' );
define( 'SECURE_AUTH_SALT', 'mF^7vhP9O$?=#^*h$e7K]$!_?)i/k+j(uE_&6iZB[1`A[56i^IbxAf}Q:eK;9Kxp' );
define( 'LOGGED_IN_SALT',   'N-@aJ%h%:=cn2&$L+b/-{f^-kHz&!]RA+6*ze] ]MZ4v)1Rw>{1eN0=1U) h;b;j' );
define( 'NONCE_SALT',       '@l9rJYA6]0(8!uc8H@ZS=Y7(_(VnJ2E,D$PAyCJFs+@{cbyk#$BsGIo-YEaIUGaP' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
