<?php
/**
 * Plugin Name: LearnDash Auto Enroll Pro
 * Description: Auto-inscribe usuarios a grupos de LearnDash basado en email o dominio con una interfaz elegante y moderna
 * Version: 2.0
 * Author: Jairo Hurtado Rosales
 */

// Prevenir acceso directo
if (!defined('ABSPATH')) {
    exit;
}

class LearnDashAutoEnroll {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('user_register', [$this, 'auto_enroll_user']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    public function add_admin_menu() {
        add_options_page(
            'LearnDash Auto Enroll',
            'Auto Enroll',
            'manage_options',
            'learndash-auto-enroll',
            [$this, 'render_admin_page']
        );
    }

    public function register_settings() {
        register_setting(
            'learndash_auto_enroll_group',
            'learndash_auto_enroll_rules',
            [$this, 'sanitize_rules']
        );
    }

    public function sanitize_rules($input) {
        if (!is_array($input)) return [];
        
        $sanitized = [];
        foreach ($input as $rule) {
            if (empty($rule['email_or_domain']) || empty($rule['group_id'])) continue;
            
            $email_or_domain = sanitize_text_field($rule['email_or_domain']);
            $group_id = intval($rule['group_id']);
            
            // Validar formato de email o dominio
            if ($this->validate_email_or_domain($email_or_domain) && $group_id > 0) {
                $sanitized[] = [
                    'email_or_domain' => strtolower($email_or_domain),
                    'group_id' => $group_id,
                    'created' => isset($rule['created']) ? $rule['created'] : current_time('mysql'),
                    'status' => 'active'
                ];
            }
        }
        
        return $sanitized;
    }

    private function validate_email_or_domain($value) {
        // Validar email completo
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        
        // Validar dominio (debe contener al menos un punto y no espacios)
        if (preg_match('/^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?)*$/', $value)) {
            return true;
        }
        
        return false;
    }

    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'settings_page_learndash-auto-enroll') return;
        
        wp_enqueue_script('jquery');
        wp_enqueue_style('wp-jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-dialog');
    }

    public function auto_enroll_user($user_id) {
        $user_info = get_userdata($user_id);
        if (!$user_info) return;
        
        $email = strtolower($user_info->user_email);
        // Usar la misma lógica que el código original
        $domain = strtolower(array_pop(explode('@', $email)));
        
        $rules = get_option('learndash_auto_enroll_rules', []);
        $enrolled_groups = [];
        
        foreach ($rules as $rule) {
            if (empty($rule['email_or_domain']) || empty($rule['group_id'])) continue;
            if (isset($rule['status']) && $rule['status'] !== 'active') continue;
            
            $target = strtolower(trim($rule['email_or_domain']));
            $group_id = intval($rule['group_id']);
            
            // Verificar coincidencia exacta de email o dominio
            if ($email === $target || $domain === $target) {
                // Usar la función correcta de LearnDash
                if (function_exists('ld_update_group_access')) {
                    $result = ld_update_group_access($user_id, $group_id);
                    if ($result) {
                        $enrolled_groups[] = $group_id;
                        // Log de la inscripción exitosa
                        $this->log_enrollment($user_id, $group_id, $target, 'success');
                    } else {
                        // Log de error en la inscripción
                        $this->log_enrollment($user_id, $group_id, $target, 'error');
                    }
                }
            }
        }
        
        // Guardar grupos inscritos en meta del usuario para referencia
        if (!empty($enrolled_groups)) {
            update_user_meta($user_id, 'auto_enrolled_groups', $enrolled_groups);
            update_user_meta($user_id, 'auto_enrolled_date', current_time('mysql'));
        }
    }

    private function log_enrollment($user_id, $group_id, $rule, $status = 'success') {
        $logs = get_option('learndash_auto_enroll_logs', []);
        $user_data = get_userdata($user_id);
        $group_data = get_post($group_id);
        
        $logs[] = [
            'user_id' => $user_id,
            'group_id' => $group_id,
            'rule' => $rule,
            'status' => $status,
            'date' => current_time('mysql'),
            'user_email' => $user_data ? $user_data->user_email : 'Unknown',
            'group_name' => $group_data ? $group_data->post_title : 'Unknown Group'
        ];
        
        // Mantener solo los últimos 100 logs para no sobrecargar la base de datos
        if (count($logs) > 100) {
            $logs = array_slice($logs, -100);
        }
        
        update_option('learndash_auto_enroll_logs', $logs);
    }

    public function render_admin_page() {
        $rules = get_option('learndash_auto_enroll_rules', []);
        $logs = get_option('learndash_auto_enroll_logs', []);
        $logs = array_slice(array_reverse($logs), 0, 10); // Últimos 10 logs
        
        ?>
        <div class="wrap ld-auto-enroll">
            <div class="ld-header">
                <h1><span class="dashicons dashicons-groups"></span> LearnDash Auto Enroll Pro</h1>
                <p class="ld-description">Inscribe automáticamente usuarios en grupos basándose en su email o dominio</p>
            </div>
            
            <?php if (!function_exists('ld_update_group_access')): ?>
                <div class="notice notice-error">
                    <p><strong>Error:</strong> LearnDash no está activo. Este plugin requiere LearnDash para funcionar.</p>
                </div>
            <?php endif; ?>

            <div class="ld-container">
                <div class="ld-main">
                    <div class="ld-card">
                        <div class="ld-card-header">
                            <h2>Reglas de Auto-Inscripción</h2>
                            <button type="button" class="ld-btn ld-btn-primary" id="add-rule-btn">
                                <span class="dashicons dashicons-plus-alt"></span> Añadir Regla
                            </button>
                        </div>
                        
                        <div class="ld-help">
                            <span class="dashicons dashicons-info"></span>
                            <p>Configura emails específicos (<code>usuario@empresa.com</code>) o dominios completos (<code>empresa.com</code>). Encuentra los IDs de grupo en LearnDash > Grupos.</p>
                        </div>

                        <form method="post" action="options.php" id="auto-enroll-form">
                            <?php settings_fields('learndash_auto_enroll_group'); ?>
                            
                            <div class="ld-rules" id="rules-container">
                                <?php if (empty($rules)): ?>
                                    <div class="ld-empty-state">
                                        <span class="dashicons dashicons-admin-users"></span>
                                        <h3>No hay reglas configuradas</h3>
                                        <p>Haz clic en "Añadir Regla" para comenzar</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($rules as $index => $rule): ?>
                                        <?php $this->render_rule_item($rule, $index); ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($rules)): ?>
                                <div class="ld-form-actions">
                                    <?php submit_button('Guardar Cambios', 'primary large', 'submit', false); ?>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <div class="ld-sidebar">
                    <div class="ld-card">
                        <div class="ld-card-header">
                            <h3>Estadísticas</h3>
                        </div>
                        <div class="ld-stats">
                            <div class="ld-stat">
                                <div class="ld-stat-number"><?php echo count($rules); ?></div>
                                <div class="ld-stat-label">Reglas Activas</div>
                            </div>
                            <div class="ld-stat">
                                <div class="ld-stat-number"><?php echo count($logs); ?></div>
                                <div class="ld-stat-label">Inscripciones Recientes</div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($logs)): ?>
                    <div class="ld-card">
                        <div class="ld-card-header">
                            <h3>Actividad Reciente</h3>
                        </div>
                        <div class="ld-activity">
                            <?php foreach ($logs as $log): ?>
                                <div class="ld-activity-item <?php echo $log['status'] === 'error' ? 'error' : 'success'; ?>">
                                    <div class="ld-activity-status">
                                        <?php echo $log['status'] === 'success' ? '✓' : '✗'; ?>
                                    </div>
                                    <div class="ld-activity-content">
                                        <div class="ld-activity-email"><?php echo esc_html($log['user_email']); ?></div>
                                        <div class="ld-activity-details">
                                            <?php echo esc_html($log['group_name']); ?> • 
                                            <?php echo human_time_diff(strtotime($log['date']), current_time('timestamp')); ?> ago
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Template para nueva regla -->
        <script type="text/template" id="rule-template">
            <?php $this->render_rule_item(['email_or_domain' => '', 'group_id' => ''], '__INDEX__'); ?>
        </script>

        <style>
            .ld-auto-enroll {
                max-width: 1200px;
                margin: 0;
            }
            
            .ld-header {
                margin-bottom: 24px;
                padding-bottom: 16px;
                border-bottom: 1px solid #e1e5e9;
            }
            
            .ld-header h1 {
                margin: 0 0 8px 0;
                font-size: 24px;
                font-weight: 600;
                color: #1d2327;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .ld-header .dashicons {
                font-size: 24px;
                color: #0073aa;
            }
            
            .ld-description {
                margin: 0;
                color: #646970;
                font-size: 14px;
            }
            
            .ld-container {
                display: grid;
                grid-template-columns: 1fr 300px;
                gap: 24px;
            }
            
            .ld-card {
                background: #fff;
                border: 1px solid #c3c4c7;
                border-radius: 4px;
                margin-bottom: 24px;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
            }
            
            .ld-card-header {
                padding: 16px 20px;
                border-bottom: 1px solid #f0f0f1;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .ld-card-header h2,
            .ld-card-header h3 {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
                color: #1d2327;
            }
            
            .ld-btn {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 6px 12px;
                border: 1px solid #c3c4c7;
                border-radius: 3px;
                background: #f6f7f7;
                color: #2c3338;
                text-decoration: none;
                font-size: 13px;
                cursor: pointer;
                transition: all 0.15s ease;
            }
            
            .ld-btn:hover {
                background: #f0f0f1;
                border-color: #8c8f94;
            }
            
            .ld-btn-primary {
                background: #0073aa;
                border-color: #0073aa;
                color: #fff;
            }
            
            .ld-btn-primary:hover {
                background: #005a87;
                border-color: #005a87;
            }
            
            .ld-btn-danger {
                background: #d63638;
                border-color: #d63638;
                color: #fff;
            }
            
            .ld-btn-danger:hover {
                background: #b32d2e;
                border-color: #b32d2e;
            }
            
            .ld-help {
                padding: 16px 20px;
                background: #f9f9f9;
                border-bottom: 1px solid #f0f0f1;
                display: flex;
                align-items: flex-start;
                gap: 8px;
            }
            
            .ld-help .dashicons {
                color: #0073aa;
                margin-top: 2px;
            }
            
            .ld-help p {
                margin: 0;
                font-size: 13px;
                color: #646970;
                line-height: 1.4;
            }
            
            .ld-rules {
                padding: 20px;
            }
            
            .ld-rule {
                display: grid;
                grid-template-columns: 1fr 120px auto;
                gap: 16px;
                align-items: end;
                padding: 16px;
                background: #f9f9f9;
                border: 1px solid #f0f0f1;
                border-radius: 4px;
                margin-bottom: 12px;
                transition: all 0.15s ease;
            }
            
            .ld-rule:hover {
                background: #f6f7f7;
                border-color: #c3c4c7;
            }
            
            .ld-field {
                display: flex;
                flex-direction: column;
            }
            
            .ld-field label {
                font-size: 13px;
                font-weight: 500;
                color: #1d2327;
                margin-bottom: 4px;
            }
            
            .ld-field input {
                padding: 8px 12px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-size: 13px;
                transition: border-color 0.15s ease;
            }
            
            .ld-field input:focus {
                border-color: #0073aa;
                outline: none;
                box-shadow: 0 0 0 1px #0073aa;
            }
            
            .ld-field input.error {
                border-color: #d63638;
            }
            
            .ld-empty-state {
                text-align: center;
                padding: 60px 20px;
                color: #8c8f94;
            }
            
            .ld-empty-state .dashicons {
                font-size: 48px;
                margin-bottom: 16px;
                opacity: 0.5;
            }
            
            .ld-empty-state h3 {
                margin: 0 0 8px 0;
                font-size: 18px;
                font-weight: 500;
            }
            
            .ld-empty-state p {
                margin: 0;
                font-size: 14px;
            }
            
            .ld-form-actions {
                padding: 16px 20px;
                border-top: 1px solid #f0f0f1;
                background: #f9f9f9;
                text-align: center;
            }
            
            .ld-stats {
                padding: 20px;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }
            
            .ld-stat {
                text-align: center;
            }
            
            .ld-stat-number {
                display: block;
                font-size: 28px;
                font-weight: 600;
                color: #0073aa;
                margin-bottom: 4px;
            }
            
            .ld-stat-label {
                font-size: 12px;
                color: #646970;
                text-transform: uppercase;
                font-weight: 500;
                letter-spacing: 0.5px;
            }
            
            .ld-activity {
                padding: 16px 20px;
                max-height: 300px;
                overflow-y: auto;
            }
            
            .ld-activity-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 12px 0;
                border-bottom: 1px solid #f0f0f1;
            }
            
            .ld-activity-item:last-child {
                border-bottom: none;
            }
            
            .ld-activity-status {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: 600;
                flex-shrink: 0;
            }
            
            .ld-activity-item.success .ld-activity-status {
                background: #00a32a;
                color: #fff;
            }
            
            .ld-activity-item.error .ld-activity-status {
                background: #d63638;
                color: #fff;
            }
            
            .ld-activity-content {
                flex: 1;
                min-width: 0;
            }
            
            .ld-activity-email {
                font-weight: 500;
                color: #1d2327;
                margin-bottom: 2px;
                word-break: break-word;
            }
            
            .ld-activity-details {
                font-size: 12px;
                color: #646970;
            }
            
            @media (max-width: 782px) {
                .ld-container {
                    grid-template-columns: 1fr;
                }
                
                .ld-rule {
                    grid-template-columns: 1fr;
                    gap: 12px;
                }
                
                .ld-stats {
                    grid-template-columns: 1fr;
                }
                
                .ld-card-header {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 12px;
                }
            }
        </style>

        <script>
            jQuery(document).ready(function($) {
                let ruleIndex = <?php echo count($rules); ?>;
                
                // Añadir nueva regla
                $('#add-rule-btn').on('click', function() {
                    const template = $('#rule-template').html();
                    const newRule = template.replace(/__INDEX__/g, ruleIndex);
                    
                    if ($('.ld-empty-state').length) {
                        $('.ld-empty-state').remove();
                    }
                    
                    $('#rules-container').append(newRule);
                    
                    // Mostrar botón de guardar si no existe
                    if (!$('.ld-form-actions').length) {
                        $('#auto-enroll-form').append('<div class="ld-form-actions"><?php submit_button('Guardar Cambios', 'primary large', 'submit', false); ?></div>');
                    }
                    
                    ruleIndex++;
                });
                
                // Eliminar regla
                $(document).on('click', '.remove-rule', function() {
                    if (confirm('¿Estás seguro de que quieres eliminar esta regla?')) {
                        $(this).closest('.ld-rule').remove();
                        
                        if ($('.ld-rule').length === 0) {
                            $('#rules-container').html('<div class="ld-empty-state"><span class="dashicons dashicons-admin-users"></span><h3>No hay reglas configuradas</h3><p>Haz clic en "Añadir Regla" para comenzar</p></div>');
                            $('.ld-form-actions').remove();
                        }
                    }
                });
                
                // Validación en tiempo real
                $(document).on('input', 'input[name*="[email_or_domain]"]', function() {
                    const value = $(this).val().toLowerCase();
                    const isValid = validateEmailOrDomain(value);
                    
                    $(this).toggleClass('error', value && !isValid);
                });
                
                function validateEmailOrDomain(value) {
                    if (!value) return true;
                    
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (emailRegex.test(value)) return true;
                    
                    const domainRegex = /^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?)*$/;
                    return domainRegex.test(value);
                }
            });
        </script>
        <?php
    }

    private function render_rule_item($rule, $index) {
        $email_or_domain = esc_attr($rule['email_or_domain'] ?? '');
        $group_id = esc_attr($rule['group_id'] ?? '');
        ?>
        <div class="ld-rule">
            <div class="ld-field">
                <label for="email_<?php echo $index; ?>">Email o Dominio</label>
                <input type="text" 
                       id="email_<?php echo $index; ?>"
                       name="learndash_auto_enroll_rules[<?php echo $index; ?>][email_or_domain]" 
                       value="<?php echo $email_or_domain; ?>"
                       placeholder="usuario@ejemplo.com o ejemplo.com" />
            </div>
            <div class="ld-field">
                <label for="group_<?php echo $index; ?>">ID del Grupo</label>
                <input type="number" 
                       id="group_<?php echo $index; ?>"
                       name="learndash_auto_enroll_rules[<?php echo $index; ?>][group_id]" 
                       value="<?php echo $group_id; ?>"
                       placeholder="123" 
                       min="1" />
            </div>
            <div class="ld-field">
                <button type="button" class="ld-btn ld-btn-danger remove-rule" title="Eliminar Regla">
                    <span class="dashicons dashicons-trash"></span>
                </button>
            </div>
        </div>
        <?php
    }
}

// Inicializar el plugin
new LearnDashAutoEnroll();
?>