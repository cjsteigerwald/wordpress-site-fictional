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
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

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
define('AUTH_KEY',         'eQ5KApjCpobpW1NHJWr6OENG1+lWXEw9T6XHXvvfybDHvY04eCWbIvqxn+VQhfCJlst+CrWdk4KAOogMqJyYLg==');
define('SECURE_AUTH_KEY',  'Uh26QSv6MaSgzoMG3coeKnstCuUgkIr2WEex9gB4HXV0PTemg+DK99vlamAtUG6sMuSLa164IPqLInAOEtdU5g==');
define('LOGGED_IN_KEY',    '1ZK8SBa9b2/hRAhMrl3PQxOgK+a2tWBZVOnANgRbxwtGn8Y4KtjfjU9mX3jm00WXBFGgrusbZ44gKLz4P8ec8A==');
define('NONCE_KEY',        'Dnp74BhIsiCQuSSAVWEUd8dyAoCWm+kr/Bpl20zMRXmF5HUMrPXlBcUnwvSZ2SX16l0sddD7/Rjg52uDW1L0KQ==');
define('AUTH_SALT',        'kw7ZJmDGm4BJzTBJ/UTQ/DymbVW+qe8ed2quYz0B7r1dO4puJll5DgmCykD0Nn8oG6UFpBAO4tU0KKCx0c0IdQ==');
define('SECURE_AUTH_SALT', 'QrOZMUDNs6INWMB92CbdQ2GppR50Ru/3xBespTXb163JW0Woevm83KJNK7C4aocOEsilQPRv6/Lj5ILI/pBh4A==');
define('LOGGED_IN_SALT',   'zWxcDfSWfveLrL6d1WXSZd+1rov4Q9OhrlTkZ7GJfnTu/Mcq0EIYRKrATUJkjUCdT98Vy9tJMljgONhR7mxBFQ==');
define('NONCE_SALT',       'f8fvTAUzy1M1bOKz4eoKPczbdltMX8s76AwZgVIdSAAvfnG+TBl3sa3E3r9FTWYENNbvk1TK8zpbxFfPNT/JIg==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
