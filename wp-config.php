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
define('DB_NAME', 'hackgov');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '|eO&[EL.f$@y2hE$Ct@uj|}Q|#3_&TfG{#MQ(Mvjum{6kDnuL|HD-i{DA_M;v$+i');
define('SECURE_AUTH_KEY',  '|JxbbX[(qc+@O|g>rB1+nJ-AVjOIpF[w8LRLt-S``wJ:c^<fs)SML#U+WK[#J4;b');
define('LOGGED_IN_KEY',    'l+&pX>s;t.Yr,1C[gFv8|SxT%Y+WI}T-)dr=~o5D00hsUyh)Hi{Eh|(6uZ?-X+BM');
define('NONCE_KEY',        '/=+0.m`iL1R ./4k-0!_]&X>>Ww:E+bO=-SK8JZRL$d?G5;T%M%d=@ak}++R3uEq');
define('AUTH_SALT',        '5;w{0]$&|~1C7QsnE-cl$E!jV!%K*T{qUz:g0VnMbn. qoqI #S=eA8VQu.l<xVc');
define('SECURE_AUTH_SALT', '!#u82;KXWVm..1h;{u31jibxo^/hpT~Z+YX8hH^7k!p),iP:3iLK@=g!s~<BaT8^');
define('LOGGED_IN_SALT',   '<i%Or4`s{N<?wK#s[O[<U|q>Yp[tAK=D(Rt+Iti9Gf@^ L<@J+O*[rT-rsnFDxLt');
define('NONCE_SALT',       'gl>L`}`<-V!?R]Fx0QQNme|AOA=1+Rse7D> d5L&=kH)n{!#P/<J}T^z6n*3Nb-B');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'hgv_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_AUTO_UPDATE_CORE', false );

define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] .'/' . 'content' );

// plugin dir
define( 'WP_PLUGIN_DIR', dirname(__FILE__) . '/modules' );
define( 'WP_PLUGIN_URL', 'http://' . $_SERVER['HTTP_HOST'] .'/' . 'modules' );

//upload folder
// define( 'UPLOADS', 'uploads' );

// echo dirname(__FILE__); exit();
// define('TEMPLATEPATH', dirname(__FILE__) . '/content/themes/twentyfourteen');
// define('STYLESHEETPATH', dirname(__FILE__) . '/content/themes/twentyfourteen');


/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
