<?php $base = get_stylesheet_directory_uri() . '/custom-pages/sagicc-link'; ?>

<!-- Estilos locales -->
<link rel="stylesheet" href="<?php echo $base; ?>/style.css">
<link rel="stylesheet" href="<?php echo $base; ?>/phone.css">
<link rel="stylesheet" href="<?php echo $base; ?>/intl-tel-input.css">


<!-- Estilos externos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&family=Poppins:wght@400;700&display=swap"
      rel="stylesheet">

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js" defer></script>
<script src="<?php echo $base; ?>/main.js" defer></script>
<script src="<?php echo $base; ?>/qr-downloader.js" defer></script>
<script src="<?php echo $base; ?>/share.js" defer></script>
<script src="<?php echo $base; ?>/copy.js" defer></script>


    <!-- =============== HERO SECTION =============== -->
    <section class="hero-section fade-in-up-section">
      <div class="floating-icons">
          <i class="fab fa-whatsapp"></i><i class="fab fa-instagram"></i>
          <i class="fab fa-telegram"></i><i class="fab fa-facebook-messenger"></i>
          <i class="fab fa-twitter"></i>
      </div>
      <div class="container">
        <h1>Tu Centro de Enlaces de Chat</h1>
        <p>Crea, personaliza y comparte enlaces directos para WhatsApp, Instagram, Telegram, Facebook y Twitter. Todo en un solo lugar y en segundos.</p>
        <a href="#generator-section" class="hero-cta-button">Empezar Gratis <i class="fas fa-arrow-right-long" style="margin-left: 8px;"></i></a>
      </div>
    </section>


  <!-- Sección del generador -->
  <div id="generator-section">
    <section class="main-columns">
    <div class="generator-container">
      <h1>Generador de Enlaces</h1>
      <p id="desc-whatsapp" class="tab-desc active">Para crear tu enlace de <b>WhatsApp</b>, añade tu número e introduce
        un mensaje predefinido que los usuarios podrán enviarte en solo un clic.</p>
      <p id="desc-facebook" class="tab-desc">Para crear tu enlace de <b>Facebook Messenger</b>, indica tu usuario o
        página y un mensaje opcional.</p>
      <p id="desc-instagram" class="tab-desc">Para crear tu enlace de <b>Instagram Direct</b>, indica tu usuario y un
        mensaje opcional.</p>
      <p id="desc-telegram" class="tab-desc">Para crear tu enlace de <b>Telegram</b>, indica tu usuario y un mensaje
        opcional.</p>
      <p id="desc-twitter" class="tab-desc">Para crear tu enlace de <b>Twitter DM</b>, indica tu usuario y un mensaje
        opcional.</p>
      <div class="social-carousel-container">
        <div class="swipe-hint">Desliza para más</div>
      <div class="social-btn-bar">
          <button class="social-btn btn-whatsapp active" data-target="whatsapp">
            <i class="fab fa-whatsapp"></i>
            <span>WhatsApp</span>
          </button>
          <button class="social-btn btn-facebook" data-target="facebook">
            <i class="fab fa-facebook-messenger"></i>
            <span>Facebook</span>
          </button>
          <button class="social-btn btn-instagram" data-target="instagram">
            <i class="fab fa-instagram"></i>
            <span>Instagram</span>
          </button>
          <button class="social-btn btn-telegram" data-target="telegram">
            <i class="fab fa-telegram"></i>
            <span>Telegram</span>
          </button>
          <button class="social-btn btn-twitter" data-target="twitter">
            <i class="fab fa-twitter"></i>
            <span>Twitter</span>
          </button>
        </div>
      </div>
      <form id="form-whatsapp" class="active">
        <label for="wa-phone">Número de WhatsApp</label>
        <div class="phone-input-container">
          <input type="tel" id="wa-phone" placeholder="Ingresa tu número" required>
        </div>
        <label for="wa-message">Mensaje (opcional)</label>
        <textarea id="wa-message" rows="2" placeholder="Escribe tu mensaje..."></textarea>
        <button type="submit" class="btn-whatsapp-submit">Generar enlace WhatsApp</button>
      </form>
      <form id="form-facebook">
        <label for="fb-user">Usuario o página de Facebook</label>
        <input type="text" id="fb-user" placeholder="Ej: miempresa" required>
        <label for="fb-message">Mensaje (opcional)</label>
        <textarea id="fb-message" rows="2" placeholder="Escribe tu mensaje..."></textarea>
        <button type="submit" class="btn-facebook-submit">Generar enlace Facebook</button>
      </form>
      <form id="form-instagram">
        <label for="ig-user">Usuario de Instagram</label>
        <input type="text" id="ig-user" placeholder="Ej: miusuario" required>
        <label for="ig-message">Mensaje (opcional)</label>
        <textarea id="ig-message" rows="2" placeholder="Escribe tu mensaje..."></textarea>
        <button type="submit" class="btn-instagram-submit">Generar enlace Instagram</button>
      </form>
      <form id="form-telegram">
        <label for="tg-user">Usuario de Telegram</label>
        <input type="text" id="tg-user" placeholder="Ej: miusuario" required>
        <label for="tg-message">Mensaje (opcional)</label>
        <textarea id="tg-message" rows="2" placeholder="Escribe tu mensaje..."></textarea>
        <button type="submit" class="btn-telegram-submit">Generar enlace Telegram</button>
      </form>
      <form id="form-twitter">
        <label for="tw-user">Usuario de Twitter</label>
        <input type="text" id="tw-user" placeholder="Ej: miusuario" required>
        <label for="tw-message">Mensaje (opcional)</label>
        <textarea id="tw-message" rows="2" placeholder="Escribe tu mensaje..."></textarea>
        <button type="submit" class="btn-twitter-submit">Generar enlace Twitter</button>
      </form>
      <div class="copy-instruction">
        <span class="desktop-text">Click para copiar</span>
        <span class="mobile-text">Toca para copiar</span>
      </div>
      <div class="result" id="result" data-tooltip="Copiar" tabindex="0" style="display:none;"></div>
    </div>
    <div class="side-column">
      <div class="preview-container">
        <div class="iphone-x">
          <div class="phone-preview">
            <div class="top-bar">
              <button class="topbar-btn back-btn" aria-label="Volver">
                <i class="fas fa-arrow-left"></i>
              </button>
              <div class="topbar-avatar"></div>
              <div class="topbar-info">
                <div class="topbar-title" id="preview-title">Tu Empresa</div>
                <div class="topbar-status" id="preview-status">En línea</div>
              </div>
              <button class="topbar-btn video-btn" aria-label="Videollamada">
                <i class="fas fa-video"></i>
              </button>
              <button class="topbar-btn call-btn" aria-label="Llamar">
                <i class="fas fa-phone"></i>
              </button>
            </div>
            <div class="preview-message" id="preview-message">Este es el mensaje que enviará el usuario 😃</div>
          </div>
        </div>

        <!-- Action Buttons Column -->
        <div class="action-buttons">
          <button class="action-btn copy-btn" title="Copiar enlace">
            <i class="fas fa-copy"></i>
            <span>Copiar</span>
          </button>
          <button class="action-btn share-btn" title="Compartir">
            <i class="fas fa-share-alt"></i>
            <span>Compartir</span>
          </button>
          <button class="action-btn download-btn" title="Descargar QR" disabled>
            <i class="fas fa-qrcode"></i>
            <span>QR Code</span>
          </button>
        </div>
      </div>
      </div>
    </section>
  </div>

<!-- Sección de Beneficios -->
<div class="page-section" style="background: white; padding: 4rem 0;">
  <div class="container">
  <section class="info-section">
    <div class="container">
      <!-- Encabezado de Beneficios -->
      <div class="section-header">
        <h2>Una herramienta, múltiples beneficios</h2>
        <p>Descubre cómo un simple enlace puede transformar tu comunicación y potenciar tus resultados.</p>
      </div>

      <!-- Fila de Beneficios -->
      <div class="feature-row">
        <div class="icon-container icon-blue">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
        <div class="text-content">
          <h3>Mayor tasa de conversión</h3>
          <p>Los mensajes personalizados aumentan hasta un 30% las tasas de respuesta, ya que le dan al usuario una idea clara de qué esperar desde el inicio.</p>
        </div>
      </div>
      <div class="feature-row">
        <div class="icon-container icon-green">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
        </div>
        <div class="text-content">
          <h3>Construye confianza</h3>
          <p>Un mensaje profesional y claro genera confianza y credibilidad desde el primer contacto, estableciendo una base sólida con tus clientes potenciales.</p>
        </div>
      </div>
      <div class="feature-row">
        <div class="icon-container icon-yellow">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="text-content">
          <h3>Ahorra tiempo</h3>
          <p>Automatiza las preguntas frecuentes en tu mensaje inicial y reduce significativamente el tiempo de respuesta a las consultas más comunes.</p>
        </div>
      </div>
      <div class="feature-row">
        <div class="icon-container icon-purple">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V10zM15 10a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2a1 1 0 01-1-1v-4z"></path></svg>
        </div>
        <div class="text-content">
          <h3>Mejor segmentación</h3>
          <p>Crea diferentes enlaces para distintas campañas o públicos. Monitorea cuáles generan más interacciones para optimizar tu estrategia de marketing.</p>
        </div>
      </div>

      <!-- Separador Visual -->
      <div class="separator"></div>

      <!-- Encabezado de Ejemplos -->
      <div class="section-header">
        <h2>Casos de Uso Prácticos</h2>
        <p>Inspírate con estas ideas para aplicar en tu negocio o proyecto y mira cómo funciona en la vida real.</p>
      </div>
      
      <!-- Grid de Ejemplos -->
      <div class="examples-grid">
        <div class="example-card">
          <h4>Perfil de Instagram</h4>
          <p>Añade un enlace único en tu biografía para saber exactamente qué seguidores te contactan y qué les interesa.</p>
          <div class="message">
            <div class="quote-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M9.983 3v7.391c0 2.908-2.355 5.264-5.263 5.264s-5.264-2.356-5.264-5.264V3h10.527zm-2.023 7.391c0-1.725-1.4-3.125-3.125-3.125S1.709 8.666 1.709 10.391s1.4 3.125 3.125 3.125 3.125-1.4 3.125-3.125zm14.017-7.391v7.391c0 2.908-2.355 5.264-5.263 5.264s-5.264-2.356-5.264-5.264V3h10.527zm-2.023 7.391c0-1.725-1.4-3.125-3.125-3.125s-3.125 1.4-3.125 3.125 1.4 3.125 3.125 3.125 3.125-1.4 3.125-3.125z"/></svg>
            </div>
            Hola, ¡vengo de <span>Instagram</span>! Quisiera más información sobre sus servicios.
          </div>
        </div>
        <div class="example-card">
          <h4>Firma de Correo Electrónico</h4>
          <p>Facilita que tus contactos de correo inicien una conversación de chat con un solo clic desde tu firma.</p>
          <div class="message">
            <div class="quote-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M9.983 3v7.391c0 2.908-2.355 5.264-5.263 5.264s-5.264-2.356-5.264-5.264V3h10.527zm-2.023 7.391c0-1.725-1.4-3.125-3.125-3.125S1.709 8.666 1.709 10.391s1.4 3.125 3.125 3.125 3.125-1.4 3.125-3.125zm14.017-7.391v7.391c0 2.908-2.355 5.264-5.263 5.264s-5.264-2.356-5.264-5.264V3h10.527zm-2.023 7.391c0-1.725-1.4-3.125-3.125-3.125s-3.125 1.4-3.125 3.125 1.4 3.125 3.125 3.125 3.125-1.4 3.125-3.125z"/></svg>
            </div>
            Hola, vi tu firma en un correo y prefiero seguir la conversación por aquí.
          </div>
        </div>
        <div class="example-card">
          <h4>Tarjeta de Presentación (con QR)</h4>
          <p>Convierte tu tarjeta física en un canal digital. Un código QR puede llevar a un chat con un saludo profesional.</p>
          <div class="message">
            <div class="quote-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M9.983 3v7.391c0 2.908-2.355 5.264-5.263 5.264s-5.264-2.356-5.264-5.264V3h10.527zm-2.023 7.391c0-1.725-1.4-3.125-3.125-3.125S1.709 8.666 1.709 10.391s1.4 3.125 3.125 3.125 3.125-1.4 3.125-3.125zm14.017-7.391v7.391c0 2.908-2.355 5.264-5.263 5.264s-5.264-2.356-5.264-5.264V3h10.527zm-2.023 7.391c0-1.725-1.4-3.125-3.125-3.125s-3.125 1.4-3.125 3.125 1.4 3.125 3.125 3.125 3.125-1.4 3.125-3.125z"/></svg>
            </div>
            Hola, [Tu Nombre]. Recibí tu <span>tarjeta de presentación</span> y me gustaría conversar.
          </div>
        </div>
        <div class="example-card">
          <h4>Consulta sobre un Producto</h4>
          <p>Coloca un enlace en la página de un producto específico para responder dudas y agilizar la venta.</p>
          <div class="message">
            <div class="quote-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M9.983 3v7.391c0 2.908-2.355 5.264-5.263 5.264s-5.264-2.356-5.264-5.264V3h10.527zm-2.023 7.391c0-1.725-1.4-3.125-3.125-3.125S1.709 8.666 1.709 10.391s1.4 3.125 3.125 3.125 3.125-1.4 3.125-3.125zm14.017-7.391v7.391c0 2.908-2.355 5.264-5.263 5.264s-5.264-2.356-5.264-5.264V3h10.527zm-2.023 7.391c0-1.725-1.4-3.125-3.125-3.125s-3.125 1.4-3.125 3.125 1.4 3.125 3.125 3.125 3.125-1.4 3.125-3.125z"/></svg>
            </div>
            ¡Hola! Me interesa el producto: <span>Zapatillas Modelo X</span>. ¿Tienen stock en talla 42?
          </div>
        </div>
      </div>
    </div>
  </section>

    <!-- =============== FAQ SECTION =============== -->
    <section class="faq-section fade-in-up-section">
      <div class="container">
        <div class="section-title">
          <h2>Preguntas Clave, Respuestas Claras</h2>
          <p>Todo lo que necesitas saber sobre nuestro generador de enlaces de chat.</p>
        </div>
  
        <div class="faq-item">
          <div class="faq-question"><h3><i class="fas fa-network-wired"></i>¿Para qué redes puedo crear enlaces?</h3><i class="fas fa-chevron-down faq-icon"></i></div>
          <div class="faq-answer"><p>Puedes generar enlaces directos de chat para las principales plataformas: WhatsApp, Instagram, Telegram, Facebook Messenger y Twitter. Todo desde esta misma herramienta.</p></div>
        </div>
        <div class="faq-item">
          <div class="faq-question"><h3><i class="fas fa-magic-wand-sparkles"></i>¿Necesito conocimientos técnicos para usarlo?</h3><i class="fas fa-chevron-down faq-icon"></i></div>
          <div class="faq-answer"><p>Absolutamente no. La interfaz es intuitiva. Solo selecciona la plataforma, rellena tu usuario o número, añade un mensaje si quieres, y haz clic en generar. Es todo.</p></div>
        </div>
        <div class="faq-item">
          <div class="faq-question"><h3><i class="fas fa-dollar-sign"></i>¿Hay algún límite o costo?</h3><i class="fas fa-chevron-down faq-icon"></i></div>
          <div class="faq-answer"><p>No. El servicio es 100% gratuito, sin registros y sin límites de uso. Nuestra misión es facilitar la comunicación, sin barreras.</p></div>
        </div>
        <div class="faq-item">
          <div class="faq-question"><h3><i class="fas fa-qrcode"></i>¿Los enlaces vienen con código QR?</h3><i class="fas fa-chevron-down faq-icon"></i></div>
          <div class="faq-answer"><p>Sí. Por cada enlace que generas, también creamos automáticamente un código QR que puedes descargar. Perfecto para tus tarjetas de visita, menús, folletos o cualquier material impreso.</p></div>
        </div>
      </div>
    </section>



  <!-- Sección CTA para Sagicc -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-content">
        <h2>Lleva tus conversaciones al siguiente nivel</h2>
        <p>
          Este generador de enlaces es solo el comienzo. Con <strong>Sagicc</strong>, todas las conversaciones que inicies (desde WhatsApp, redes sociales, email y más) llegan a una bandeja de entrada única, potenciada con Inteligencia Artificial.
        </p>
        <a href="https://sagicc.co/" target="_blank" class="cta-button">Conoce más sobre Sagicc</a>
      </div>
    </div>
  </section>
</div>

  
  <script>
    // FAQ Toggle Functionality
    document.addEventListener('DOMContentLoaded', function() {
      const faqItems = document.querySelectorAll('.faq-item');
      
      faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        question.addEventListener('click', () => {
          const isActive = item.classList.contains('active');
          
          // Close all items
          faqItems.forEach(i => i.classList.remove('active'));
          
          // Toggle current item if it wasn't active
          if (!isActive) {
            item.classList.add('active');
          }
        });
      });
      
      // Smooth scroll for anchor links
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href');
          if (targetId === '#') return;
          
          const targetElement = document.querySelector(targetId);
          if (targetElement) {
            window.scrollTo({
              top: targetElement.offsetTop - 100, // Offset for fixed header if any
              behavior: 'smooth'
            });
          }
        });
      });
    });
  </script>