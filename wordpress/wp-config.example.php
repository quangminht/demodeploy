<?php
define( 'WP_HOME', 'http://noithat.local' );
define( 'WP_SITEURL', 'http://noithat.local' );
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
define( 'DB_NAME', 'YOUR_DB_NAME' );

/** Database username */
define( 'DB_USER', 'YOUR_DB_USER' );

/** Database password */
define( 'DB_PASSWORD', 'YOUR_DB_PASSWORD' );

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
define( 'AUTH_KEY',         '3i}z<(?~0g5@9/!]``z^!s7o (:iGYzNt}`:a B?m,A=;p{+n4N]F|B0nU|5, ^F' );
define( 'SECURE_AUTH_KEY',  '@r_N(sruI^l+=:XeQ#ghV&2#CYQy]DaaX:3A}M])PP.L;Q2<Q(pR&}zJ=dp[KH:1' );
define( 'LOGGED_IN_KEY',    '#GrtM_i~:hm@PBcp#vsaN4{[[qZghJ`,)FK_]0%#>xxjgn@9bKB&_I##<zLc7/d|' );
define( 'NONCE_KEY',        'fOSDo:o0IHk=6-}%Z]lDU3p*Mj*Ifq9JJvf-OO ~Q%F8?cN;T5uG*F&7-ktSmCeJ' );
define( 'AUTH_SALT',        'Cy8 SJ7!D#86<zy>?$/4(w%UA^,JS(7MkUA@iOU< ;-ELk<q#&G$WPaX|:eDCF0i' );
define( 'SECURE_AUTH_SALT', '%6!R*I!h0s)uQ4d,vSl/@/qn,,Huaw:b-m]ux.vdo_?n`o/BB`q@m?oU?,xbU/Y2' );
define( 'LOGGED_IN_SALT',   '/CBvy@#>)_EmTLko)Z-R75Y|(-Gyw:v4/Q=3cXW,xmKijrprMfOR5vmq@9*ysPy ' );
define( 'NONCE_SALT',       'zotNl^O(fw+w.,xNy-H1<<k4W h2Qq ~goL`2v,7DlqU;v8*.Yo$zD_L}xTK}y@{' );

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
$table_prefix = 'pw_';

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
