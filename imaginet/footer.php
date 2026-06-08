		<footer class="footer" role="contentinfo" id="colophon">
			<div class="container">
				<div class="rights-credit">
					
					<div class="rights">
						<?php 
						echo wp_kses_post( get_field('rights_text', 'option') ); 
						?>
					</div>
		
					<div class="credit-accessibility">
						<div class="credit">
							<?php esc_html_e('Site by', 'imaginet'); ?> 
							<a href="https://imaginet.co.il" target="_blank" rel="noopener">Imaginet</a>
						</div>
						
						<?php if ( get_field('accessibility_link', 'option') ) : ?>
							<a class="accessibility" href="<?php echo esc_url( get_field('accessibility_link', 'option') ); ?>" target="_blank" rel="noopener">
								<?php echo esc_html( get_field('accessibility_text', 'option') ); ?>
							</a>
						<?php endif; ?>
					</div>
		
				</div>
			</div>
		</footer>
		
		<?php wp_footer(); ?> 
	</body>
</html>
