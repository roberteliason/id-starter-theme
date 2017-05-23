<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */

get_header(); ?>

    <?php
    if ( ! is_user_logged_in() ) { // Display WordPress login form:

        $args = array(
            'redirect'       => admin_url(),
            'form_id'        => 'loginform-ehalsa',
            'label_username' => __( 'Username' ),
            'label_password' => __( 'Password' ),
            'label_remember' => __( 'Remember Me' ),
            'label_log_in'   => __( 'Log In' ),
            'remember'       => false
        );
        wp_login_form( $args );
        exit;
    }
    ?>

	<div id="primary" class="content-area dashboard">
		<main id="main" class="site-main" role="main">

            <div class="progress">
                <?php echo do_shortcode('[namastepro-progress]') ?>
            </div>

            <div class="dash-board-feeds">
                <div class="unit-feed">
                    <?php echo do_shortcode('[namaste-leaderboard 10]') ?>
                </div>

                <div class="courses-feed">
                    <?php echo do_shortcode('[namaste-mycourses]') ?>
                </div>

                <div class="badges-feed">
                    <?php echo do_shortcode('[namaste-mycertificates]') ?>
                </div>
            </div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
