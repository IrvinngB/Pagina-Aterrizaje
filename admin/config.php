<?php
require_once '../includes/functions.php';
requireAdmin();

$pageTitle = 'Configuración del Sistema';
include '../includes/header.php';

// Simulación de configuraciones del sistema
$configuracionesGenerales = [
    'nombre_sitio' => 'PixelPerfect',
    'logo' => '/assets/images/LogoColorFinal.svg',
    'email_contacto' => 'admin@pixelperfect.com',
    'telefono_contacto' => '+1 (555) 123-4567',
    'direccion' => 'Calle Principal 123, Ciudad',
    'idioma_predeterminado' => 'es',
    'zona_horaria' => 'America/Panama',
    'moneda_predeterminada' => 'USD',
    'impuesto' => '7%'
];

$configuracionesVisual = [
    'tema_color_primario' => '#114093',
    'tema_color_secundario' => '#f2f6f9',
    'tema_color_acento' => '#e94e1a',
    'fuente_principal' => 'Poppins, sans-serif',
    'fuente_titulos' => 'Montserrat, sans-serif',
    'tamaño_fuente_base' => '16px',
    'mostrar_banner_cookies' => 'true',
    'texto_banner_cookies' => 'Utilizamos cookies para mejorar tu experiencia de navegación.'
];

$configuracionesSeguridad = [
    'registro_abierto' => 'true',
    'verificacion_email' => 'true',
    'intentos_login' => '5',
    'tiempo_bloqueo' => '10 minutos',
    'complejidad_contraseña' => 'alta',
    'duracion_sesion' => '2 horas',
    'autenticacion_dos_factores' => 'opcional',
    'registrar_actividad' => 'true',
    'proteccion_spam' => 'true'
];

$configuracionesNotificaciones = [
    'notificar_nuevos_usuarios' => 'true',
    'notificar_nuevas_solicitudes' => 'true',
    'notificar_mensajes_contacto' => 'true',
    'notificar_pagos' => 'true',
    'frecuencia_resumen' => 'diaria',
    'emails_notificacion' => 'admin@pixelperfect.com, gerente@pixelperfect.com'
];
?>

<main class="container-fluid p-0">
    <section class="admin-header">
        <div class="admin-header-content">
            <h1><i class="fas fa-sliders-h"></i> Configuración del Sistema</h1>
            <p>Administra las preferencias y configuraciones globales del sistema</p>
            
            <div class="admin-actions">
                <a href="dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a>
                <div class="action-buttons">
                    <button class="btn-reset"><i class="fas fa-undo"></i> Restaurar valores</button>
                    <button class="btn-save"><i class="fas fa-save"></i> Guardar cambios</button>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="config-tabs">
                <button class="tab-btn active" data-tab="general">
                    <i class="fas fa-cog"></i>
                    <span>General</span>
                </button>
                <button class="tab-btn" data-tab="visual">
                    <i class="fas fa-paint-brush"></i>
                    <span>Apariencia</span>
                </button>
                <button class="tab-btn" data-tab="security">
                    <i class="fas fa-shield-alt"></i>
                    <span>Seguridad</span>
                </button>
                <button class="tab-btn" data-tab="notifications">
                    <i class="fas fa-bell"></i>
                    <span>Notificaciones</span>
                </button>
                <button class="tab-btn" data-tab="backup">
                    <i class="fas fa-database"></i>
                    <span>Respaldo</span>
                </button>
            </div>
            
            <div class="config-content">
                <!-- General Settings Tab -->
                <div class="tab-content active" id="general">
                    <div class="config-section">
                        <h2>Información del Sitio</h2>
                        <p>Configura la información básica de tu sitio web</p>
                        
                        <div class="config-form">
                            <div class="form-group">
                                <label for="nombre_sitio">Nombre del sitio</label>
                                <input type="text" id="nombre_sitio" value="<?php echo htmlspecialchars($configuracionesGenerales['nombre_sitio']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Logo del sitio</label>
                                <div class="logo-upload">
                                    <img src="<?php echo htmlspecialchars($configuracionesGenerales['logo']); ?>" alt="Logo actual">
                                    <div class="upload-actions">
                                        <button class="btn-upload"><i class="fas fa-upload"></i> Cambiar logo</button>
                                        <button class="btn-remove"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email_contacto">Email de contacto</label>
                                    <input type="email" id="email_contacto" value="<?php echo htmlspecialchars($configuracionesGenerales['email_contacto']); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="telefono_contacto">Teléfono de contacto</label>
                                    <input type="text" id="telefono_contacto" value="<?php echo htmlspecialchars($configuracionesGenerales['telefono_contacto']); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <textarea id="direccion"><?php echo htmlspecialchars($configuracionesGenerales['direccion']); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="config-section">
                        <h2>Configuración Regional</h2>
                        <p>Establece opciones regionales y de localización</p>
                        
                        <div class="config-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="idioma_predeterminado">Idioma predeterminado</label>
                                    <select id="idioma_predeterminado">
                                        <option value="es" <?php echo $configuracionesGenerales['idioma_predeterminado'] === 'es' ? 'selected' : ''; ?>>Español</option>
                                        <option value="en" <?php echo $configuracionesGenerales['idioma_predeterminado'] === 'en' ? 'selected' : ''; ?>>English</option>
                                        <option value="fr" <?php echo $configuracionesGenerales['idioma_predeterminado'] === 'fr' ? 'selected' : ''; ?>>Français</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="zona_horaria">Zona horaria</label>
                                    <select id="zona_horaria">
                                        <option value="America/Panama" <?php echo $configuracionesGenerales['zona_horaria'] === 'America/Panama' ? 'selected' : ''; ?>>América/Panamá</option>
                                        <option value="America/Mexico_City" <?php echo $configuracionesGenerales['zona_horaria'] === 'America/Mexico_City' ? 'selected' : ''; ?>>América/Ciudad de México</option>
                                        <option value="America/Bogota" <?php echo $configuracionesGenerales['zona_horaria'] === 'America/Bogota' ? 'selected' : ''; ?>>América/Bogotá</option>
                                        <option value="Europe/Madrid" <?php echo $configuracionesGenerales['zona_horaria'] === 'Europe/Madrid' ? 'selected' : ''; ?>>Europa/Madrid</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="moneda_predeterminada">Moneda predeterminada</label>
                                    <select id="moneda_predeterminada">
                                        <option value="USD" <?php echo $configuracionesGenerales['moneda_predeterminada'] === 'USD' ? 'selected' : ''; ?>>USD - Dólar estadounidense</option>
                                        <option value="EUR" <?php echo $configuracionesGenerales['moneda_predeterminada'] === 'EUR' ? 'selected' : ''; ?>>EUR - Euro</option>
                                        <option value="PAB" <?php echo $configuracionesGenerales['moneda_predeterminada'] === 'PAB' ? 'selected' : ''; ?>>PAB - Balboa panameño</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="impuesto">Impuesto predeterminado</label>
                                    <input type="text" id="impuesto" value="<?php echo htmlspecialchars($configuracionesGenerales['impuesto']); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Visual Settings Tab -->
                <div class="tab-content" id="visual">
                    <div class="config-section">
                        <h2>Tema y Colores</h2>
                        <p>Personaliza los colores y el aspecto visual del sitio</p>
                        
                        <div class="config-form">
                            <div class="color-pickers">
                                <div class="color-group">
                                    <label>Color primario</label>
                                    <div class="color-preview">
                                        <div class="color-box" style="background-color: <?php echo $configuracionesVisual['tema_color_primario']; ?>"></div>
                                        <input type="text" value="<?php echo $configuracionesVisual['tema_color_primario']; ?>">
                                    </div>
                                </div>
                                
                                <div class="color-group">
                                    <label>Color secundario</label>
                                    <div class="color-preview">
                                        <div class="color-box" style="background-color: <?php echo $configuracionesVisual['tema_color_secundario']; ?>"></div>
                                        <input type="text" value="<?php echo $configuracionesVisual['tema_color_secundario']; ?>">
                                    </div>
                                </div>
                                
                                <div class="color-group">
                                    <label>Color acento</label>
                                    <div class="color-preview">
                                        <div class="color-box" style="background-color: <?php echo $configuracionesVisual['tema_color_acento']; ?>"></div>
                                        <input type="text" value="<?php echo $configuracionesVisual['tema_color_acento']; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="theme-preview">
                                <h3>Vista previa del tema</h3>
                                <div class="preview-container">
                                    <div class="preview-header" style="background-color: <?php echo $configuracionesVisual['tema_color_primario']; ?>">
                                        <div class="preview-logo">PixelPerfect</div>
                                        <div class="preview-nav"></div>
                                    </div>
                                    <div class="preview-content" style="background-color: <?php echo $configuracionesVisual['tema_color_secundario']; ?>">
                                        <div class="preview-title" style="color: <?php echo $configuracionesVisual['tema_color_primario']; ?>">Título de ejemplo</div>
                                        <div class="preview-text">Texto de ejemplo para mostrar el aspecto.</div>
                                        <div class="preview-button" style="background-color: <?php echo $configuracionesVisual['tema_color_acento']; ?>">Botón</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="config-section">
                        <h2>Tipografía</h2>
                        <p>Selecciona las fuentes y tamaños de texto del sitio</p>
                        
                        <div class="config-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fuente_principal">Fuente principal</label>
                                    <select id="fuente_principal">
                                        <option value="Poppins, sans-serif" <?php echo $configuracionesVisual['fuente_principal'] === 'Poppins, sans-serif' ? 'selected' : ''; ?>>Poppins</option>
                                        <option value="Roboto, sans-serif" <?php echo $configuracionesVisual['fuente_principal'] === 'Roboto, sans-serif' ? 'selected' : ''; ?>>Roboto</option>
                                        <option value="Open Sans, sans-serif" <?php echo $configuracionesVisual['fuente_principal'] === 'Open Sans, sans-serif' ? 'selected' : ''; ?>>Open Sans</option>
                                        <option value="Lato, sans-serif" <?php echo $configuracionesVisual['fuente_principal'] === 'Lato, sans-serif' ? 'selected' : ''; ?>>Lato</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="fuente_titulos">Fuente para títulos</label>
                                    <select id="fuente_titulos">
                                        <option value="Montserrat, sans-serif" <?php echo $configuracionesVisual['fuente_titulos'] === 'Montserrat, sans-serif' ? 'selected' : ''; ?>>Montserrat</option>
                                        <option value="Poppins, sans-serif" <?php echo $configuracionesVisual['fuente_titulos'] === 'Poppins, sans-serif' ? 'selected' : ''; ?>>Poppins</option>
                                        <option value="Playfair Display, serif" <?php echo $configuracionesVisual['fuente_titulos'] === 'Playfair Display, serif' ? 'selected' : ''; ?>>Playfair Display</option>
                                        <option value="Raleway, sans-serif" <?php echo $configuracionesVisual['fuente_titulos'] === 'Raleway, sans-serif' ? 'selected' : ''; ?>>Raleway</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="tamaño_fuente_base">Tamaño base de fuente</label>
                                <select id="tamaño_fuente_base">
                                    <option value="14px" <?php echo $configuracionesVisual['tamaño_fuente_base'] === '14px' ? 'selected' : ''; ?>>14px</option>
                                    <option value="16px" <?php echo $configuracionesVisual['tamaño_fuente_base'] === '16px' ? 'selected' : ''; ?>>16px</option>
                                    <option value="18px" <?php echo $configuracionesVisual['tamaño_fuente_base'] === '18px' ? 'selected' : ''; ?>>18px</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="config-section">
                        <h2>Banner de Cookies</h2>
                        <p>Configura el mensaje de aceptación de cookies</p>
                        
                        <div class="config-form">
                            <div class="form-group switch-group">
                                <span>Mostrar banner de cookies</span>
                                <label class="switch">
                                    <input type="checkbox" <?php echo $configuracionesVisual['mostrar_banner_cookies'] === 'true' ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label for="texto_banner_cookies">Texto del banner</label>
                                <textarea id="texto_banner_cookies"><?php echo htmlspecialchars($configuracionesVisual['texto_banner_cookies']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Security Tab -->
                <div class="tab-content" id="security">
                    <div class="config-section">
                        <h2>Configuración de Cuentas</h2>
                        <p>Opciones de seguridad para la gestión de cuentas de usuario</p>
                        
                        <div class="config-form">
                            <div class="form-group switch-group">
                                <span>Permitir registro de usuarios</span>
                                <label class="switch">
                                    <input type="checkbox" <?php echo $configuracionesSeguridad['registro_abierto'] === 'true' ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="form-group switch-group">
                                <span>Requerir verificación por email</span>
                                <label class="switch">
                                    <input type="checkbox" <?php echo $configuracionesSeguridad['verificacion_email'] === 'true' ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="intentos_login">Intentos de login antes de bloqueo</label>
                                    <input type="number" id="intentos_login" min="1" max="10" value="<?php echo htmlspecialchars($configuracionesSeguridad['intentos_login']); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="tiempo_bloqueo">Tiempo de bloqueo</label>
                                    <input type="text" id="tiempo_bloqueo" value="<?php echo htmlspecialchars($configuracionesSeguridad['tiempo_bloqueo']); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="complejidad_contraseña">Complejidad mínima de contraseña</label>
                                <select id="complejidad_contraseña">
                                    <option value="baja" <?php echo $configuracionesSeguridad['complejidad_contraseña'] === 'baja' ? 'selected' : ''; ?>>Baja (mínimo 6 caracteres)</option>
                                    <option value="media" <?php echo $configuracionesSeguridad['complejidad_contraseña'] === 'media' ? 'selected' : ''; ?>>Media (mínimo 8 caracteres, incluir números)</option>
                                    <option value="alta" <?php echo $configuracionesSeguridad['complejidad_contraseña'] === 'alta' ? 'selected' : ''; ?>>Alta (mínimo 10 caracteres, incluir números, mayúsculas y símbolos)</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="duracion_sesion">Duración de sesión</label>
                                <input type="text" id="duracion_sesion" value="<?php echo htmlspecialchars($configuracionesSeguridad['duracion_sesion']); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="config-section">
                        <h2>Protección y Privacidad</h2>
                        <p>Configuración avanzada de seguridad</p>
                        
                        <div class="config-form">
                            <div class="form-group switch-group">
                                <span>Autenticación de dos factores</span>
                                <label class="switch">
                                    <input type="checkbox" <?php echo $configuracionesSeguridad['autenticacion_dos_factores'] === 'true' || $configuracionesSeguridad['autenticacion_dos_factores'] === 'opcional' ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label for="opcion_2fa">Opción 2FA</label>
                                <select id="opcion_2fa">
                                    <option value="opcional" <?php echo $configuracionesSeguridad['autenticacion_dos_factores'] === 'opcional' ? 'selected' : ''; ?>>Opcional para usuarios</option>
                                    <option value="requerido_admin" <?php echo $configuracionesSeguridad['autenticacion_dos_factores'] === 'requerido_admin' ? 'selected' : ''; ?>>Requerido solo para administradores</option>
                                    <option value="requerido_todos" <?php echo $configuracionesSeguridad['autenticacion_dos_factores'] === 'requerido_todos' ? 'selected' : ''; ?>>Requerido para todos los usuarios</option>
                                </select>
                            </div>
                            
                            <div class="form-group switch-group">
                                <span>Registrar actividad de usuario</span>
                                <label class="switch">
                                    <input type="checkbox" <?php echo $configuracionesSeguridad['registrar_actividad'] === 'true' ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="form-group switch-group">
                                <span>Protección contra spam en formularios</span>
                                <label class="switch">
                                    <input type="checkbox" <?php echo $configuracionesSeguridad['proteccion_spam'] === 'true' ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="security-actions">
                                <button class="security-btn"><i class="fas fa-lock"></i> Revisar configuración SSL</button>
                                <button class="security-btn"><i class="fas fa-broom"></i> Limpiar logs antiguos</button>
                                <button class="security-btn"><i class="fas fa-user-shield"></i> Ver informe de seguridad</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Notifications Tab -->
                <div class="tab-content" id="notifications">
                    <div class="config-section">
                        <h2>Notificaciones del Sistema</h2>
                        <p>Configura las notificaciones automáticas</p>
                        
                        <div class="config-form">
                            <div class="form-group switch-group">
                                <span>Notificar sobre nuevos usuarios</span>
                                <label class="switch">
                                    <input type="checkbox" <?php echo $configuracionesNotificaciones['notificar_nuevos_usuarios'] === 'true' ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="form-group switch-group">
                                <span>Notificar sobre nuevas solicitudes</span>
                                <label class="switch">
                                    <input type="checkbox" <?php echo $configuracionesNotificaciones['notificar_nuevas_solicitudes'] === 'true' ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="form-group switch-group">
                                <span>Notificar sobre nuevos mensajes de contacto</span>
                                <label class="switch">
                                    <input type="checkbox" <?php echo $configuracionesNotificaciones['notificar_mensajes_contacto'] === 'true' ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="form-group switch-group">
                                <span>Notificar sobre nuevos pagos</span>
                                <label class="switch">
                                    <input type="checkbox" <?php echo $configuracionesNotificaciones['notificar_pagos'] === 'true' ? 'checked' : ''; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="config-section">
                        <h2>Resumen de Actividad</h2>
                        <p>Configura los informes resumidos por email</p>
                        
                        <div class="config-form">
                            <div class="form-group">
                                <label for="frecuencia_resumen">Frecuencia del resumen</label>
                                <select id="frecuencia_resumen">
                                    <option value="diaria" <?php echo $configuracionesNotificaciones['frecuencia_resumen'] === 'diaria' ? 'selected' : ''; ?>>Diaria</option>
                                    <option value="semanal" <?php echo $configuracionesNotificaciones['frecuencia_resumen'] === 'semanal' ? 'selected' : ''; ?>>Semanal</option>
                                    <option value="quincenal" <?php echo $configuracionesNotificaciones['frecuencia_resumen'] === 'quincenal' ? 'selected' : ''; ?>>Quincenal</option>
                                    <option value="mensual" <?php echo $configuracionesNotificaciones['frecuencia_resumen'] === 'mensual' ? 'selected' : ''; ?>>Mensual</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="emails_notificacion">Emails para notificaciones</label>
                                <textarea id="emails_notificacion"><?php echo htmlspecialchars($configuracionesNotificaciones['emails_notificacion']); ?></textarea>
                                <small>Introduce múltiples emails separados por comas</small>
                            </div>
                            
                            <div class="form-group">
                                <button class="test-btn"><i class="fas fa-paper-plane"></i> Enviar email de prueba</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Backup Tab -->
                <div class="tab-content" id="backup">
                    <div class="config-section">
                        <h2>Respaldo de Datos</h2>
                        <p>Configura las copias de seguridad automáticas</p>
                        
                        <div class="config-form">
                            <div class="backup-status">
                                <div class="status-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="status-info">
                                    <h3>Estado de los respaldos</h3>
                                    <p>Último respaldo: <strong>16 de Julio, 2025 - 03:00 AM</strong></p>
                                    <p>Estado: <span class="status-success">Completado con éxito</span></p>
                                </div>
                                <div class="status-actions">
                                    <button class="backup-btn"><i class="fas fa-download"></i> Descargar</button>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="backup_frequency">Frecuencia de respaldo automático</label>
                                <select id="backup_frequency">
                                    <option value="diaria">Diaria</option>
                                    <option value="semanal" selected>Semanal</option>
                                    <option value="mensual">Mensual</option>
                                    <option value="manual">Solo manual</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="backup_time">Hora programada (servidor)</label>
                                <input type="time" id="backup_time" value="03:00">
                            </div>
                            
                            <div class="form-group">
                                <label for="backup_retention">Retención de respaldos</label>
                                <select id="backup_retention">
                                    <option value="7">Mantener los últimos 7 respaldos</option>
                                    <option value="30" selected>Mantener los últimos 30 respaldos</option>
                                    <option value="90">Mantener los últimos 90 respaldos</option>
                                    <option value="365">Mantener los últimos 365 respaldos</option>
                                </select>
                            </div>
                            
                            <div class="form-group switch-group">
                                <span>Incluir archivos de medios</span>
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="backup-actions">
                                <button class="backup-btn primary"><i class="fas fa-database"></i> Crear respaldo ahora</button>
                                <button class="backup-btn"><i class="fas fa-upload"></i> Restaurar desde respaldo</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="config-section">
                        <h2>Historial de Respaldos</h2>
                        <p>Respaldos anteriores disponibles para restaurar</p>
                        
                        <div class="backup-history">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tamaño</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>16 Julio, 2025 - 03:00 AM</td>
                                        <td>24.5 MB</td>
                                        <td>Automático</td>
                                        <td><span class="status-success">Completado</span></td>
                                        <td>
                                            <button class="action-icon"><i class="fas fa-download"></i></button>
                                            <button class="action-icon"><i class="fas fa-undo"></i></button>
                                            <button class="action-icon"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>9 Julio, 2025 - 03:00 AM</td>
                                        <td>23.8 MB</td>
                                        <td>Automático</td>
                                        <td><span class="status-success">Completado</span></td>
                                        <td>
                                            <button class="action-icon"><i class="fas fa-download"></i></button>
                                            <button class="action-icon"><i class="fas fa-undo"></i></button>
                                            <button class="action-icon"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>5 Julio, 2025 - 15:23 PM</td>
                                        <td>23.7 MB</td>
                                        <td>Manual</td>
                                        <td><span class="status-success">Completado</span></td>
                                        <td>
                                            <button class="action-icon"><i class="fas fa-download"></i></button>
                                            <button class="action-icon"><i class="fas fa-undo"></i></button>
                                            <button class="action-icon"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2 Julio, 2025 - 03:00 AM</td>
                                        <td>23.6 MB</td>
                                        <td>Automático</td>
                                        <td><span class="status-success">Completado</span></td>
                                        <td>
                                            <button class="action-icon"><i class="fas fa-download"></i></button>
                                            <button class="action-icon"><i class="fas fa-undo"></i></button>
                                            <button class="action-icon"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
:root {
    --primary-color: #114093;
    --secondary-color: #f2f6f9;
    --accent-color: #e94e1a;
    --text-color: #333;
    --background-color: #ffffff;
    --primary-light: #2a5bb7;
    --primary-dark: #0c2f6e;
    --accent-light: #ff6a3c;
    --accent-dark: #c93f12;
    --secondary-dark: #d1dce8;
    --status-success: #28a745;
    --status-warning: #ffc107;
    --status-danger: #dc3545;
}

/* Header Styles */
.admin-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
}

.admin-header-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.admin-header h1 {
    font-size: 2.2rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.admin-header h1 i {
    color: var(--accent-color);
}

.admin-header p {
    opacity: 0.8;
    margin-bottom: 1.5rem;
}

.admin-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    text-decoration: none;
    background: rgba(255, 255, 255, 0.15);
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    font-weight: 600;
    transition: background 0.3s ease;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.25);
    color: white;
}

.action-buttons {
    display: flex;
    gap: 1rem;
}

.btn-reset, .btn-save {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-reset {
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

.btn-reset:hover {
    background: rgba(255, 255, 255, 0.25);
}

.btn-save {
    background: var(--accent-color);
    color: white;
}

.btn-save:hover {
    background: var(--accent-dark);
}

/* Content Styles */
.content-section {
    padding: 1rem 0 4rem;
    background: var(--secondary-color);
    min-height: calc(100vh - 300px);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Tab Navigation */
.config-tabs {
    display: flex;
    overflow-x: auto;
    margin-bottom: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.tab-btn {
    padding: 1.2rem 2rem;
    border: none;
    background: transparent;
    font-weight: 600;
    color: var(--text-color);
    cursor: pointer;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    position: relative;
    transition: all 0.3s ease;
}

.tab-btn:hover {
    color: var(--primary-color);
}

.tab-btn.active {
    color: var(--primary-color);
}

.tab-btn.active:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--primary-color);
}

/* Tab Content */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Configuration Sections */
.config-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.config-section h2 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.config-section p {
    color: #777;
    margin-bottom: 1.5rem;
}

/* Form Styling */
.config-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

label {
    font-weight: 500;
    color: var(--text-color);
}

input[type="text"],
input[type="email"],
input[type="number"],
input[type="time"],
select,
textarea {
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
}

textarea {
    min-height: 100px;
    resize: vertical;
}

small {
    font-size: 0.8rem;
    color: #777;
}

/* Logo Upload */
.logo-upload {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.logo-upload img {
    max-width: 200px;
    max-height: 60px;
}

.upload-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-upload, .btn-remove {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-upload {
    background: var(--secondary-color);
    border: 1px solid #ddd;
    color: var(--text-color);
}

.btn-upload:hover {
    background: var(--secondary-dark);
}

.btn-remove {
    background: white;
    border: 1px solid #ddd;
    color: #dc3545;
}

.btn-remove:hover {
    background: #fef2f2;
}

/* Switch Toggle */
.switch-group {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}

.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
}

.switch input { 
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
}

input:checked + .slider {
    background-color: var(--primary-color);
}

input:focus + .slider {
    box-shadow: 0 0 1px var(--primary-color);
}

input:checked + .slider:before {
    transform: translateX(24px);
}

.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

/* Color Pickers */
.color-pickers {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.color-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.color-preview {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.color-box {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

/* Theme Preview */
.theme-preview {
    margin-top: 2rem;
}

.theme-preview h3 {
    font-size: 1.2rem;
    margin-bottom: 1rem;
    color: var(--primary-color);
}

.preview-container {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.preview-header {
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.preview-logo {
    font-weight: bold;
    font-size: 1.2rem;
}

.preview-nav {
    width: 60%;
    height: 10px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 5px;
}

.preview-content {
    padding: 2rem;
    min-height: 150px;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.preview-title {
    font-weight: bold;
    font-size: 1.2rem;
}

.preview-text {
    width: 80%;
    height: 8px;
    background: #ddd;
    border-radius: 4px;
    margin: 0.5rem 0;
}

.preview-button {
    align-self: flex-start;
    padding: 0.5rem 1.5rem;
    color: white;
    border-radius: 4px;
    font-size: 0.9rem;
    margin-top: 1rem;
}

/* Security Actions */
.security-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
}

.security-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    background: var(--secondary-color);
    border: 1px solid #ddd;
    color: var(--text-color);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.security-btn:hover {
    background: var(--secondary-dark);
}

/* Test Email Button */
.test-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    background: var(--primary-color);
    border: none;
    color: white;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    align-self: flex-start;
}

.test-btn:hover {
    background: var(--primary-dark);
}

/* Backup Status */
.backup-status {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 2rem;
    padding: 1.5rem;
    background: var(--secondary-color);
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.status-icon {
    font-size: 2.5rem;
    color: var(--status-success);
    display: flex;
    align-items: center;
}

.status-info h3 {
    font-size: 1.2rem;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.status-info p {
    margin-bottom: 0.25rem;
}

.status-success {
    color: var(--status-success);
    font-weight: 600;
}

.status-warning {
    color: var(--status-warning);
    font-weight: 600;
}

.status-danger {
    color: var(--status-danger);
    font-weight: 600;
}

.status-actions {
    display: flex;
    align-items: center;
}

/* Backup Actions */
.backup-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.backup-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 1.4rem;
    border-radius: 8px;
    background: var(--secondary-color);
    border: 1px solid #ddd;
    color: var(--text-color);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.backup-btn:hover {
    background: var(--secondary-dark);
}

.backup-btn.primary {
    background: var(--primary-color);
    color: white;
    border: none;
}

.backup-btn.primary:hover {
    background: var(--primary-dark);
}

/* Backup History */
.backup-history {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 0.75rem 1rem;
    text-align: left;
}

th {
    background: var(--secondary-color);
    color: var(--primary-color);
    font-weight: 600;
}

tbody tr {
    border-bottom: 1px solid #eee;
}

tbody tr:hover {
    background: #f9f9f9;
}

.action-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #ddd;
    background: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #666;
    transition: all 0.3s ease;
    cursor: pointer;
}

.action-icon:hover {
    background: var(--secondary-color);
    color: var(--primary-color);
}

/* Responsive Styles */
@media (max-width: 768px) {
    .admin-actions {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .action-buttons {
        width: 100%;
        justify-content: space-between;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .color-pickers {
        grid-template-columns: 1fr;
    }
    
    .backup-status {
        grid-template-columns: 1fr;
    }
    
    .status-icon, .status-info, .status-actions {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .backup-actions {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons and contents
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Show corresponding content
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Save changes button
    const btnSave = document.querySelector('.btn-save');
    if (btnSave) {
        btnSave.addEventListener('click', function() {
            alert('Cambios guardados correctamente.');
        });
    }
    
    // Reset values button
    const btnReset = document.querySelector('.btn-reset');
    if (btnReset) {
        btnReset.addEventListener('click', function() {
            if (confirm('¿Estás seguro de que deseas restaurar los valores predeterminados?')) {
                alert('Valores restaurados correctamente.');
            }
        });
    }
    
    // Test email button
    const testBtn = document.querySelector('.test-btn');
    if (testBtn) {
        testBtn.addEventListener('click', function() {
            alert('Email de prueba enviado correctamente.');
        });
    }
    
    // Backup now button
    const backupBtn = document.querySelector('.backup-btn.primary');
    if (backupBtn) {
        backupBtn.addEventListener('click', function() {
            alert('Creando respaldo del sistema. Esto puede tardar unos minutos.');
        });
    }
});
</script>

<?php include '../includes/footer.php'; ?>
