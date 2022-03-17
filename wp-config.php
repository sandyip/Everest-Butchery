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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'neweverest' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'EFw .6BGEc7hs<^&#cpJ4]9,cYQiAF,j|~%#FF%H5F6O!@C#K|~vKfI)heA*q_CO' );
define( 'SECURE_AUTH_KEY',  'xNf:<5w;U7ew4.KMmiTrX{H#$P6KtUbrt1??~9SinR#WTB}C}cCtTC]Rh%G[Z<Wp' );
define( 'LOGGED_IN_KEY',    '%dcKcB*Ip,)&dkdJeel9S@kaS$P4q**( PZbumNhY}7@-.G4&RrAZ%|8x5FN6`AU' );
define( 'NONCE_KEY',        '+R),WwK6l8m<%DNlZUi14tg*,H+yxPd1/+ZGGfszJiQy3{Y_hgdfurB1$cybMF{(' );
define( 'AUTH_SALT',        'WL4duh.FliG~=9RA_DfR=u$i7|^I4RBo@>Zt,a<Nyta$yh)jRMFcm[Sm+*u?fJ@0' );
define( 'SECURE_AUTH_SALT', 'Xn[Sd`a0+O?|B|26WNh-C07(Bv+Z2E%ye4LzVK~.Xgc)2}%{/jLe]W6UxM<9%Sv!' );
define( 'LOGGED_IN_SALT',   '/Ya-!f4pa6[MmMRbn!nk;Qm&|uRPDtl7oBM]R0~!g~2_9jW:K{})(tN)yQJ3z6yp' );
define( 'NONCE_SALT',       'k<12ps|%$~ao5*pV4V9D:YWKO{8veUR8xW;]9L}54_*)e^x_rQ *|yn WUu`S5`[' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
