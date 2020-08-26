<?php
if ( ! current_user_can( FrasePlugin::CAPABILITY ) ) {
	return;
}
settings_errors( 'frase_messages' );
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="options.php" method="post">
		<?php
		settings_fields( FrasePlugin::PAGE );
		do_settings_sections( FrasePlugin::PAGE );
		submit_button( 'Save' );
		?>
	</form>
</div>
