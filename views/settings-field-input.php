<?php
/**
 * Template variables.
 *
 * @var string $class       Input class name.
 * @var string $description Field description.
 * @var string $label       Input label.
 * @var string $name        Input name.
 * @var string $type        Input type.
 * @var string $value       Input value.
 */
if ( ! isset( $type ) ) {
	$type = 'text';
}
?>
<div class="frase-settings-field">
	<input
		aria-label="<?php echo esc_attr( $label ); ?>"
		class="<?php echo esc_attr( $class ); ?>"
		name="<?php echo esc_attr( $name ); ?>"
		type="<?php echo esc_attr( $type ); ?>"
		value="<?php echo esc_attr( $value ); ?>"
	/>
	<?php if ( isset( $description ) ) : ?>
		<p class="description">
			<?php esc_html( $description ); ?>
		</p>
	<?php endif; ?>
</div>
