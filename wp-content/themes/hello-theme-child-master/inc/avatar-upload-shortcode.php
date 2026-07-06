<?php
/**
 * Shortcode Avatar Material Design - Lógica Reforzada
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function material_avatar_upload_shortcode( $atts ) {
	global $current_user;

	// 1. Verificación de sesión
	if ( ! is_user_logged_in() ) {
		return '<div class="mau-alert mau-error">Debes iniciar sesión para editar tu avatar.</div>';
	}

	$valid_user = $current_user;
	$user_id    = $valid_user->ID;
	$plugin_messages = '';

	// 2. Procesamiento (Backend) - Idéntico al original para máxima compatibilidad
	if (
		isset( $_POST['wpua_action'] ) && 
		( 'update' == $_POST['wpua_action'] || 'upload' == $_POST['wpua_action'] ) &&
		isset( $_POST['_wpnonce'] ) && 
		wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id )
	) {
		ob_start();
		do_action( 'wpua_update', $user_id );
		$output = ob_get_clean();
		// Si hay salida, asumimos que hubo acción (éxito o error del plugin)
		if ( ! empty( $output ) ) {
			// Opcional: filtrar mensajes del plugin o mostrar uno genérico limpio
			$plugin_messages = '<div class="mau-alert mau-info">Proceso completado. Si no ves los cambios, recarga la página.</div>';
		}
	}

	ob_start();
	?>
	<style>
		:root {
			--mau-primary: #1A73E8; /* Google Blue */
			--mau-primary-dark: #1557B0;
			--mau-size: 160px;
		}

		.mau-container {
			display: flex;
			flex-direction: column;
			align-items: center;
			font-family: Roboto, -apple-system, sans-serif;
			margin: 2rem 0;
			position: relative;
		}

		/* Alertas */
		.mau-alert {
			padding: 12px 20px;
			border-radius: 4px;
			font-size: 14px;
			margin-bottom: 20px;
			text-align: center;
			box-shadow: 0 2px 5px rgba(0,0,0,0.1);
		}
		.mau-error { background-color: #fdeded; color: #5f2120; border-left: 4px solid #d32f2f; }
		.mau-info { background-color: #e8f5e9; color: #1b5e20; border-left: 4px solid #2e7d32; }

		/* Avatar Wrapper */
		.mau-avatar-wrapper {
			position: relative;
			width: var(--mau-size);
			height: var(--mau-size);
		}

		.mau-avatar-wrapper img {
			width: 100%;
			height: 100%;
			border-radius: 50%;
			object-fit: cover;
			box-shadow: 0 4px 10px rgba(0,0,0,0.2);
			display: block;
			background: #fff;
		}

		/* Spinner de carga */
		.mau-loading-overlay {
			position: absolute;
			top: 0; left: 0; width: 100%; height: 100%;
			border-radius: 50%;
			background: rgba(255,255,255,0.6);
			display: none;
			justify-content: center;
			align-items: center;
			z-index: 20;
		}
		.mau-spinner {
			width: 40px; height: 40px;
			border: 4px solid #f3f3f3;
			border-top: 4px solid var(--mau-primary);
			border-radius: 50%;
			animation: mau-spin 1s linear infinite;
		}

		/* Botón FAB (Material Design) */
		.mau-fab {
			position: absolute;
			bottom: 5px;
			right: 5px;
			width: 48px;
			height: 48px;
			background-color: var(--mau-primary);
			border-radius: 50%;
			box-shadow: 0 3px 5px -1px rgba(0,0,0,0.2), 0 6px 10px 0 rgba(0,0,0,0.14), 0 1px 18px 0 rgba(0,0,0,0.12);
			display: flex;
			align-items: center;
			justify-content: center;
			cursor: pointer;
			transition: transform 0.2s, background-color 0.2s;
			z-index: 30;
		}
		.mau-fab:hover {
			background-color: var(--mau-primary-dark);
			transform: scale(1.05);
		}
		.mau-fab svg {
			width: 24px; height: 24px;
			fill: #ffffff;
		}

		/* EL TRUCO: Visually Hidden (Accesible y funcional, no display:none) */
		.mau-visually-hidden {
			position: absolute;
			width: 1px; height: 1px;
			padding: 0; margin: -1px;
			overflow: hidden;
			clip: rect(0, 0, 0, 0);
			border: 0;
		}

		@keyframes mau-spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
	</style>

	<div class="mau-container">
		<?php if ( ! empty( $plugin_messages ) ) echo $plugin_messages; ?>

		<div class="mau-avatar-wrapper">
			<div class="mau-loading-overlay">
				<div class="mau-spinner"></div>
			</div>
			
			<?php echo get_avatar( $user_id, 200 ); ?>

			<div class="mau-fab" id="mau-trigger-btn">
				<svg viewBox="0 0 24 24">
					<circle cx="12" cy="12" r="3.2"/>
					<path d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
				</svg>
			</div>
		</div>

		<input type="file" id="mau-bridge-input" accept="image/*" style="display:none;">

		<div class="mau-visually-hidden">
			<form id="wpua-edit-<?php echo esc_attr( $user_id ); ?>" class="wpua-edit" action="" method="post" enctype="multipart/form-data">
				
				<?php do_action( 'wpua_show_profile', $valid_user ); ?>

				<input type="hidden" name="wpua_action" value="update" />
				<input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>" />
				<?php wp_nonce_field( 'update-user_' . $user_id ); ?>
				
				<input type="submit" name="submit" value="Guardar" />
			</form>
		</div>
	</div>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Elementos del DOM
		var userId = '<?php echo esc_js( $user_id ); ?>';
		var triggerBtn = document.getElementById('mau-trigger-btn');
		var bridgeInput = document.getElementById('mau-bridge-input');
		var loadingOverlay = document.querySelector('.mau-loading-overlay');
		var previewImg = document.querySelector('.mau-avatar-wrapper img');
		
		// El formulario "Real" (Legacy)
		var legacyForm = document.getElementById('wpua-edit-' + userId);

		if (!triggerBtn || !bridgeInput || !legacyForm) {
			console.error("MAU Error: No se encontraron los elementos necesarios.");
			return;
		}

		// 1. Click en el botón bonito -> abre el input puente
		triggerBtn.addEventListener('click', function() {
			bridgeInput.click();
		});

		// 2. Cuando el usuario elige archivo
		bridgeInput.addEventListener('change', function() {
			if (this.files.length > 0) {
				var file = this.files[0];

				// A. Feedback visual inmediato
				loadingOverlay.style.display = 'flex';
				var reader = new FileReader();
				reader.onload = function(e) {
					if (previewImg) previewImg.src = e.target.result;
				};
				reader.readAsDataURL(file);

				// B. LÓGICA ROBUSTA (Tu código original mejorado)
				// Buscamos el input del plugin EXACTAMENTE como lo hacía tu código
				var legacyInput = legacyForm.querySelector('input[type="file"][name="wp-user-avatar"]');
				
				// Fallback si el nombre cambió
				if (!legacyInput) {
					legacyInput = legacyForm.querySelector('input[type="file"]');
				}

				if (legacyInput) {
					try {
						// Transferencia de archivo moderna
						var dataTransfer = new DataTransfer();
						dataTransfer.items.add(file);
						legacyInput.files = dataTransfer.files;

						// Disparamos evento change por si el plugin escucha
						var event = new Event('change', { bubbles: true });
						legacyInput.dispatchEvent(event);

						// C. ENVIO (Submit)
						// Damos un respiro al navegador para procesar el archivo (100ms es suficiente, 500ms es seguro)
						setTimeout(function() {
							var submitBtn = legacyForm.querySelector('input[type="submit"]');
							if (submitBtn) {
								submitBtn.click();
							} else {
								legacyForm.submit();
							}
						}, 300);

					} catch (e) {
						console.error("Error transfiriendo archivo:", e);
						alert("Hubo un error al procesar la imagen. Intenta de nuevo.");
						loadingOverlay.style.display = 'none';
					}
				} else {
					console.error("No se encontró el input del plugin.");
					alert("Error de compatibilidad con el plugin de avatar.");
					loadingOverlay.style.display = 'none';
				}
			}
		});
	});
	</script>
	<?php

	return ob_get_clean();
}
add_shortcode( 'mejor_avatar_upload', 'material_avatar_upload_shortcode' );
?>