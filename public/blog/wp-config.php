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
define( 'DB_NAME', 'epictrainer-wp-FOvej18i' );

/** MySQL database username */
define( 'DB_USER', 'eNVoDOHES9uQ' );

/** MySQL database password */
define( 'DB_PASSWORD', '5B1QK3FT4n2hIboN' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          ')OZ8n:dvA,S<wU1tTRDk6%;`4b}2L|puuve]yQv#Iq5$H*RMHD=r0Z,Nu |xKTM_' );
define( 'SECURE_AUTH_KEY',   '}36|_*?saKHEBvdzP$./HlX]x59bwiRUc|??vR $:Is:yQbGwNnH]j-?mH:WYE)L' );
define( 'LOGGED_IN_KEY',     '<Pa=Aeb,]&VH-xXX*dz}ej7Vu.~Y:(4lKoa`a]nuzExk&H]W-}L@HbZ=LbTI*gXN' );
define( 'NONCE_KEY',         '1hi@2Gm)p9mAQuh>APRwRh:f,23+ z,_)P] +u(Gx9jHk5i!n-M)W/7v}*JG*E++' );
define( 'AUTH_SALT',         'y?Q6.,> off2N{?QR*[8v1J2NqE<Q7V?zQL}ap9ZqIV^qIS,P|maaa1 %Q#ZW$=^' );
define( 'SECURE_AUTH_SALT',  '4;zKDk:ta%t.eFs~aY?:_t8eNC9G87VzoJhSQDTr8,{gmO/14!gPyrt~pCqvk26#' );
define( 'LOGGED_IN_SALT',    '!J@0M,wO|XA[5uMqq|5Lk:_t!&?5mbQJJdK?7,5V%&+|w%=1wSQLOU.p>R&[oalk' );
define( 'NONCE_SALT',        ',jSs@Y=LqDFMz=a;YN^W[pDQ6a8@#4cOV{2|#) n,@}tW_WcKgEOkIA6xy*#A6o@' );
define( 'WP_CACHE_KEY_SALT', 'hx-{B[*z{-NzIBRa@F(KL<`1yig#^J>Y>a7[&B,XMe $jOE};&VZtSX_[;-j9e|p' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_f9380a22a0_';


/* Change WP_MEMORY_LIMIT to increase the memory limit for public pages. */
define('WP_MEMORY_LIMIT', '256M');

/* Uncomment and change WP_MAX_MEMORY_LIMIT to increase the memory limit for admin pages. */
//define('WP_MAX_MEMORY_LIMIT', '256M');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
