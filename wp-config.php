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


if ($_SERVER['SERVER_NAME'] === "hometown.dev") {

  // ** MySQL settings - You can get this info from your web host ** //
  /** The name of the database for WordPress */
  define('DB_NAME', 'hostsi90_home');

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

} else if ($_SERVER['SERVER_NAME'] === "dev.hostsites.cloud/home") {

  // ** MySQL settings - You can get this info from your web host ** //
  /** The name of the database for WordPress */
  define('DB_NAME', 'hostsi90_home');

  /** MySQL database username */
  define('DB_USER', 'hostsi90_home'); // http://phpmyadmin.hostsites.cloud/

  /** MySQL database password */
  define('DB_PASSWORD', ',ieIVT_F1SO.'); // http://phpmyadmin.hostsites.cloud/

  /** MySQL hostname */
  define('DB_HOST', 'hostsites.cloud');

  /** Database Charset to use in creating database tables. */
  define('DB_CHARSET', 'utf8mb4');

  /** The Database Collate type. Don't change this if in doubt. */
  define('DB_COLLATE', '');

};



/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'm,O:@3BiH$Y_.)w92& ng%#yN]e6>!T)Nldh+S7,sMTZHY9u*40;ZKR)i{.P7#qc');
define('SECURE_AUTH_KEY',  '0%5hz1Du=h7-tzhQHF>Ci+ff)RHY+HsSTcLH&<cqop]?N_m=av:74yLh{m?3ktWY');
define('LOGGED_IN_KEY',    '/MC6,?.Z6pPjG>Z?-!oJC89oY$tjT&LE2t]9j>=e 1<>4Jp!4P}P9.M-;m&..d(R');
define('NONCE_KEY',        '7fGAXy;JY`IaA(x=DvLyRlJHbqs:.74b-GP]{ hQ90V!jD2XF2ycR}d`&Uw)rDaE');
define('AUTH_SALT',        'n]0}/t,#p+_H+)}2UKbS-a4Eksc,?Aa5O W=B@d>tcJ` 78pZWKb/G9Ar(z7qN|G');
define('SECURE_AUTH_SALT', '(BD,}TL40A<36Lt-w}ru1Ct5K&M=_;R9u!T|72]QeTu>7U^#1pkYow_*s!<Q?=c1');
define('LOGGED_IN_SALT',   'C{.<%hh8U0^uHI*_sHkQprm~sPAvR,,dM*JYfXZL^^;[%84Ks:Bu(HXf|3z8XW2-');
define('NONCE_SALT',       '^`cN8;W=4DEBiFWX5hMDC+kv>Mdw~4F:hYVUT*JU=q{w iVKLJ<Oz*!k&[XovvfF');

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

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
