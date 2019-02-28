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

$table_prefix = 'wp_';


// ** MySQL settings ** //

$server_url = $_SERVER['HTTP_HOST'];
 
switch($server_url) {	
	
	
	// LOCAL
	case 'rdr2.local' : 
	
		define('WP_HOME', 'http://rdr2.local');
		define('WP_SITEURL', 'http://rdr2.local');
		
		/** MySQL database username */
		define( 'DB_USER', 'root' );
		
		/** MySQL database password */
		define( 'DB_PASSWORD', '' );
		
		/** MySQL hostname */
		define( 'DB_HOST', 'localhost' );
		
		/** The name of the database for WordPress */
		define( 'DB_NAME', 'rdr2' );
		
		//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		
		define('DISABLE_WP_CRON', FALSE); 
		define('WP_DEBUG', TRUE); 
		define('SAVEQUERIES', TRUE); 
		define('FS_METHOD', 'direct'); 
		
	
	break;
	
	// STAGING 
	case 'rdr2.netwideimage.com' : 
	
		define('WP_HOME', 'https://rdr2.netwideimage.com');
		define('WP_SITEURL', 'https://rdr2.netwideimage.com');
		
		/** MySQL database username */
		define( 'DB_USER', 'netwidei_web' );
		
		/** MySQL database password */
		define( 'DB_PASSWORD', '(6$f{e8%bU=C' );
		
		/** MySQL hostname */
		define( 'DB_HOST', 'localhost' );
		
		/** The name of the database for WordPress */
		define( 'DB_NAME', 'netwidei_rdr2' );
		
		//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		
		define('DISABLE_WP_CRON', FALSE); 
		define('WP_DEBUG', TRUE); 
		define('SAVEQUERIES', TRUE); 
		
	break;
	
	// PRODUCTION
	default :  

		define('WP_HOME', 'https://rdr2.photography');
		define('WP_SITEURL', 'https://rdr2.photography');
				
		/** MySQL database username */
		define( 'DB_USER', '' );
		
		/** MySQL database password */
		define( 'DB_PASSWORD', '' );
		
		/** MySQL hostname */
		define( 'DB_HOST', '' );
		
		/** The name of the database for WordPress */
		define( 'DB_NAME', '' );
		
	break;
	
}



/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'h2la-K@)bDy+/`>0mg>nfya/7pVdgo]YTsg7%FZS2@??J~%Yf}mIBw Qei#fYzt;');
define('SECURE_AUTH_KEY',  ']<IB[mL=~~vma VN<S)P!x|+4-54YJU*9dX`)Ntju/r|^ G|;$U<FL9zQzoV_oq]');
define('LOGGED_IN_KEY',    'osVm*_#2/7Ukuc[bR`9#r&ghWHJB*b;j wh>|ax@*LYgoU-n$&53y1{@sDTcUsCt');
define('NONCE_KEY',        '0n:JxDG5}QA)Id~)1~t@u+vRO:fxK&`yu_9YM5fchhWlC|nB$O?G[7V-Mo{~w0Bn');
define('AUTH_SALT',        'E~=H&E[+Y<^+]O2l&pk!/3*4?O6> *v<Rj%Agutp[>1@KZm3-6F^dhMjTB-ZNWPn');
define('SECURE_AUTH_SALT', '0)}=dKwr|{#_b}dFb0p*dGJQG/+5V<+%h+QI`[y)]NQuX|:+-u8m.K;m< ]gzp,J');
define('LOGGED_IN_SALT',   'eAB-9amV-o_{PaK6-lPP+X+*xv@`yaph-?o_na9bo0@;|HUk)d19Zpd38;;Fs%Jl');
define('NONCE_SALT',       't-Z2ulO?uRA>ok+EmC4Y/:+ddHl]s}>~|AsxN0?FZcc}<XO~B@9m=vB$H^U<dy*L');



define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_general_ci');
define('WP_MEMORY_LIMIT', '96M');
define('WP_MAX_MEMORY_LIMIT', '96M');


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
