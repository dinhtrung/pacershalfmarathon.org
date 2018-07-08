<?php
/**
 * The template for displaying the footer.
 */

				runcrew_close_wrapper();	// <!-- </.content> -->

				runcrew_profiler_add_point(esc_html__('After Page content', 'runcrew'));
	
				// Show main sidebar
				get_sidebar();

				if (runcrew_get_custom_option('body_style')!='fullscreen') runcrew_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			// Footer Testimonials stream
			if (runcrew_get_custom_option('show_testimonials_in_footer')=='yes') { 
				$count = max(1, runcrew_get_custom_option('testimonials_count'));
				$data = runcrew_sc_testimonials(array('count'=>$count));
				if ($data) {
					?>
					<footer class="testimonials_wrap sc_section scheme_<?php echo esc_attr(runcrew_get_custom_option('testimonials_scheme')); ?>">
						<div class="testimonials_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php echo trim($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}

            // Socials in footer
            if (runcrew_get_custom_option('show_socials_in_footer')=='yes') {
                ?>
                <footer class="socials_wrap scheme_<?php echo esc_attr(runcrew_get_custom_option('socials_scheme')); ?>">
                    <div class="socials_wrap_inner">
                        <div class="content_wrap">
                            <?php echo trim(runcrew_sc_socials(array('size'=>"small", 'shape'=>"round", 'show_name'=>true))); ?>
                        </div>	<!-- /.content_wrap -->
                    </div>	<!-- /.contacts_wrap_inner -->
                </footer>	<!-- /.contacts_wrap -->
                <?php
            }

			// Footer sidebar
			$footer_show  = runcrew_get_custom_option('show_sidebar_footer');
			$sidebar_name = runcrew_get_custom_option('sidebar_footer');
			if (!runcrew_param_is_off($footer_show) && is_active_sidebar($sidebar_name)) { 
				runcrew_storage_set('current_sidebar', 'footer');
				?>
				<footer class="footer_wrap widget_area scheme_<?php echo esc_attr(runcrew_get_custom_option('sidebar_footer_scheme')); ?>">
					<div class="footer_wrap_inner widget_area_inner">
						<div class="content_wrap">
							<div class="columns_wrap"><?php
							ob_start();
							do_action( 'before_sidebar' );
							if ( !dynamic_sidebar($sidebar_name) ) {
								// Put here html if user no set widgets in sidebar
							}
							do_action( 'after_sidebar' );
							$out = ob_get_contents();
							ob_end_clean();
							echo trim(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
							?></div>	<!-- /.columns_wrap -->
						</div>	<!-- /.content_wrap -->
					</div>	<!-- /.footer_wrap_inner -->
				</footer>	<!-- /.footer_wrap -->
				<?php
			}


			// Footer Twitter stream
			if (runcrew_get_custom_option('show_twitter_in_footer')=='yes') { 
				$count = max(1, runcrew_get_custom_option('twitter_count'));
				$data = runcrew_sc_twitter(array('count'=>$count));
				if ($data) {
					?>
					<footer class="twitter_wrap sc_section scheme_<?php echo esc_attr(runcrew_get_custom_option('twitter_scheme')); ?>">
						<div class="twitter_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php echo trim($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}


			// Google map
			if ( runcrew_get_custom_option('show_googlemap')=='yes' ) { 
				$map_address = runcrew_get_custom_option('googlemap_address');
				$map_latlng  = runcrew_get_custom_option('googlemap_latlng');
				$map_zoom    = runcrew_get_custom_option('googlemap_zoom');
				$map_style   = runcrew_get_custom_option('googlemap_style');
				$map_height  = runcrew_get_custom_option('googlemap_height');
				if (!empty($map_address) || !empty($map_latlng)) {
					$args = array();
					if (!empty($map_style))		$args['style'] = esc_attr($map_style);
					if (!empty($map_zoom))		$args['zoom'] = esc_attr($map_zoom);
					if (!empty($map_height))	$args['height'] = esc_attr($map_height);
					echo trim(runcrew_sc_googlemap($args));
				}
			}

			// Footer contacts
			if (runcrew_get_custom_option('show_contacts_in_footer')=='yes') {
				$address_1 = runcrew_get_theme_option('contact_address_1');
				$address_2 = runcrew_get_theme_option('contact_address_2');
				$phone = runcrew_get_theme_option('contact_phone');
				$fax = runcrew_get_theme_option('contact_fax');
				if (!empty($address_1) || !empty($address_2) || !empty($phone) || !empty($fax)) {
					?>
					<footer class="contacts_wrap scheme_<?php echo esc_attr(runcrew_get_custom_option('contacts_scheme')); ?>">
						<div class="contacts_wrap_inner">
							<div class="content_wrap">
								<?php runcrew_show_logo(false, false, true); ?>
								<div class="contacts_address">
									<address class="address_right">
										<?php if (!empty($phone)) echo esc_html__('Phone:', 'runcrew') . ' ' . esc_html($phone) . '<br>'; ?>
										<?php if (!empty($fax)) echo esc_html__('Fax:', 'runcrew') . ' ' . esc_html($fax); ?>
									</address>
									<address class="address_left">
										<?php if (!empty($address_2)) echo esc_html($address_2) . '<br>'; ?>
										<?php if (!empty($address_1)) echo esc_html($address_1); ?>
									</address>
								</div>
							</div>	<!-- /.content_wrap -->
						</div>	<!-- /.contacts_wrap_inner -->
					</footer>	<!-- /.contacts_wrap -->
					<?php
				}
            }

			// Copyright area
			$copyright_style = runcrew_get_custom_option('show_copyright_in_footer');
			if (!runcrew_param_is_off($copyright_style)) {
				?> 
				<div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>  scheme_<?php echo esc_attr(runcrew_get_custom_option('copyright_scheme')); ?>">
					<div class="copyright_wrap_inner">
						<div class="content_wrap">
							<?php
							if ($copyright_style == 'menu') {
								if (($menu = runcrew_get_nav_menu('menu_footer'))!='') {
									echo trim($menu);
								}
							} else if ($copyright_style == 'socials') {
								echo trim(runcrew_sc_socials(array('size'=>"tiny")));
							}
							?>
							<div class="copyright_text"><?php echo force_balance_tags(runcrew_get_custom_option('footer_copyright')); ?></div>
						</div>
					</div>
				</div>
				<?php
			}

			runcrew_profiler_add_point(esc_html__('After Footer', 'runcrew'));
			?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->
	
	<?php if ( !runcrew_param_is_off(runcrew_get_custom_option('show_sidebar_outer')) ) { ?>
	</div>	<!-- /.outer_wrap -->
	<?php } ?>

<?php
// Post/Page views counter
get_template_part(runcrew_get_file_slug('templates/_parts/views-counter.php'));

// Login/Register
if (runcrew_get_theme_option('show_login')=='yes') {
	runcrew_enqueue_popup();
	// Anyone can register ?
	if ( (int) get_option('users_can_register') > 0) {
		get_template_part(runcrew_get_file_slug('templates/_parts/popup-register.php'));
	}
	get_template_part(runcrew_get_file_slug('templates/_parts/popup-login.php'));
}

// Front customizer
if (runcrew_get_custom_option('show_theme_customizer')=='yes') {
	get_template_part(runcrew_get_file_slug('core/core.customizer/front.customizer.php'));
}
?>

<a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'runcrew'); ?>"></a>

<div class="custom_html_section">
<?php echo force_balance_tags(runcrew_get_custom_option('custom_code')); ?>
</div>

<?php
echo force_balance_tags(runcrew_get_custom_option('gtm_code2'));

runcrew_profiler_add_point(esc_html__('After Theme HTML output', 'runcrew'));

wp_footer(); 
?>

</body>
</html>