<?php

class WP_SQLite_DB__Main
{
    public static function bootstrap() {
        self::define_constants();
        self::define_wp_install();
        $GLOBALS['wpdb'] = new WP_SQLite_DB\wpsqlitedb();
    }

    private static function define_constants() {
        /**
         * FQDBDIR is a directory where the sqlite database file is placed.
         * If DB_DIR is defined, it is used as FQDBDIR.
         */
        if ( defined( 'DB_DIR' ) ) {
            if ( substr( DB_DIR, - 1, 1 ) != '/' ) {
                define( 'FQDBDIR', DB_DIR . '/' );
            } else {
                define( 'FQDBDIR', DB_DIR );
            }
        } else {
            if ( defined( 'WP_CONTENT_DIR' ) ) {
                define( 'FQDBDIR', WP_CONTENT_DIR . '/database/' );
            } else {
                define( 'FQDBDIR', ABSPATH . 'wp-content/database/' );
            }
        }

        /**
         * FQDB is a database file name. If DB_FILE is defined, it is used
         * as FQDB.
         */
        if ( defined( 'DB_FILE' ) ) {
            define( 'FQDB', FQDBDIR . DB_FILE );
        } else {
            define( 'FQDB', FQDBDIR . '.ht.sqlite' );
        }
    }

    private static function define_wp_install() {
        if ( function_exists( 'wp_install' ) ) {
            return;
        }
        /**
         * Installs the site.
         *
         * Runs the required functions to set up and populate the database,
         * including primary admin user and initial options.
         *
         * @param string $blog_title Site title.
         * @param string $user_name User's username.
         * @param string $user_email User's email.
         * @param bool $public Whether site is public.
         * @param string $deprecated Optional. Not used.
         * @param string $user_password Optional. User's chosen password. Default empty (random password).
         * @param string $language Optional. Language chosen. Default empty.
         *
         * @return array Array keys 'url', 'user_id', 'password', and 'password_message'.
         * @since 2.1.0
         *
         */
        function wp_install(
            $blog_title,
            $user_name,
            $user_email,
            $public,
            $deprecated = '',
            $user_password = '',
            $language = ''
        ) {
            if ( ! empty( $deprecated ) ) {
                _deprecated_argument( __FUNCTION__, '2.6.0' );
            }

            wp_check_mysql_version();
            wp_cache_flush();
            /* begin wp-sqlite-db changes */
            // make_db_current_silent();
            WP_SQLite_DB\Install::run();
            /* end wp-sqlite-db changes */
            populate_options();
            populate_roles();

            update_option( 'blogname', $blog_title );
            update_option( 'admin_email', $user_email );
            update_option( 'blog_public', $public );

            // Freshness of site - in the future, this could get more specific about actions taken, perhaps.
            update_option( 'fresh_site', 1 );

            if ( $language ) {
                update_option( 'WPLANG', $language );
            }

            $guessurl = wp_guess_url();

            update_option( 'siteurl', $guessurl );

            // If not a public blog, don't ping.
            if ( ! $public ) {
                update_option( 'default_pingback_flag', 0 );
            }

            /*
             * Create default user. If the user already exists, the user tables are
             * being shared among sites. Just set the role in that case.
             */
            $user_id        = username_exists( $user_name );
            $user_password  = trim( $user_password );
            $email_password = false;
            if ( ! $user_id && empty( $user_password ) ) {
                $user_password = wp_generate_password( 12, false );
                $message       = __( '<strong><em>Note that password</em></strong> carefully! It is a <em>random</em> password that was generated just for you.' );
                $user_id       = wp_create_user( $user_name, $user_password, $user_email );
                update_user_option( $user_id, 'default_password_nag', true, true );
                $email_password = true;
            } elseif ( ! $user_id ) {
                // Password has been provided
                $message = '<em>' . __( 'Your chosen password.' ) . '</em>';
                $user_id = wp_create_user( $user_name, $user_password, $user_email );
            } else {
                $message = __( 'User already exists. Password inherited.' );
            }

            $user = new WP_User( $user_id );
            $user->set_role( 'administrator' );

            wp_install_defaults( $user_id );

            wp_install_maybe_enable_pretty_permalinks();

            flush_rewrite_rules();

            wp_new_blog_notification( $blog_title, $guessurl, $user_id,
                ( $email_password ? $user_password : __( 'The password you chose during installation.' ) ) );

            wp_cache_flush();

            /**
             * Fires after a site is fully installed.
             *
             * @param WP_User $user The site owner.
             *
             * @since 3.9.0
             *
             */
            do_action( 'wp_install', $user );

            return [ 'url' => $guessurl, 'user_id' => $user_id, 'password' => $user_password, 'password_message' => $message ];
        }
    }
}