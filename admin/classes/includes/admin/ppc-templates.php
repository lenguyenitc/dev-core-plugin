<?php
/**
 * PPC JS Templates.
 *
 * @package primary-post-category
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<style>
    .ppc-primary-term select { width: 90% !important; }
</style>

<script type="text/html" id="tmpl-ppc-select-primary-term">
	<div class="ppc-primary-term">
		<h4 class="ppc-primary-term-heading">
			<?php
			/* translators: %s expands to taxonomy title. */
			echo sprintf( esc_html__( 'Primary %s', 'src' ), '{{data.taxonomy.title}}' );
			?>
		</h4>
		<select id="ppc-primary-term-{{data.taxonomy.name}}" name="ppc_primary_term_{{data.taxonomy.name}}" style="width: 96%;">
			<option value="-1">
				<?php
				/* translators: %s expands to taxonomy title. */
				echo sprintf( esc_html__( 'Select Main %s—', 'arc' ), '{{data.taxonomy.title}}' );
				?>
			</option>
			<# _( data.taxonomy.terms ).each( function( term ) { #>
			<option value="{{term.id}}"
			<# if ( data.taxonomy.primary === term.id ) { #>
			selected
			<# } #>
			>{{term.name}}</option>
			<# }); #>
		</select>
		<?php wp_nonce_field( 'ppc-save-primary-term', 'ppc_save_primary_{{data.taxonomy.name}}_nonce' ); ?>
	</div>
</script>
