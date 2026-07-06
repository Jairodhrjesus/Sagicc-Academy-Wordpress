// Lógica para tabs y formularios
window.addEventListener('DOMContentLoaded', () => {
    // Initialize intl-tel-input
    const phoneInput = document.querySelector("#wa-phone");
    const iti = window.intlTelInput(phoneInput, {
        initialCountry: "ar", // Argentina por defecto
        preferredCountries: ["ar", "us", "es", "br", "cl", "co", "mx", "pe", "uy", "ve"],
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
    });

    // Validar número de teléfono
    const validatePhoneNumber = () => {
        if (phoneInput.value.trim()) {
            if (iti.isValidNumber()) {
                phoneInput.setCustomValidity("");
                return true;
            } else {
                phoneInput.setCustomValidity("Por favor ingresa un número de teléfono válido");
                return false;
            }
        }
        return true;
    };

    phoneInput.addEventListener('blur', validatePhoneNumber);
    phoneInput.addEventListener('change', validatePhoneNumber);
    phoneInput.addEventListener('keyup', () => {
        if (phoneInput.value.trim() === "") {
            phoneInput.setCustomValidity("");
        }
    });
    const previewMessage = document.getElementById('preview-message');
    const textareas = [
        document.getElementById('wa-message'),
        document.getElementById('fb-message'),
        document.getElementById('ig-message'),
        document.getElementById('tg-message'),
        document.getElementById('tw-message')
    ];
    const exampleMsgs = [
        'Hola, quiero más información por WhatsApp.',
        'Hola, quiero más información por Messenger.',
        'Hola, quiero más información por Instagram.',
        'Hola, quiero más información por Telegram.',
        'Hola, quiero más información por Twitter.'
    ];
    function updatePreview(idx) {
        const value = textareas[idx].value.trim();
        previewMessage.textContent = value ? value : exampleMsgs[idx];
    }
    // Cambia la previsualización cuando cambias de pestaña
    const btns = document.querySelectorAll('.social-btn');
    const forms = [
        document.getElementById('form-whatsapp'),
        document.getElementById('form-facebook'),
        document.getElementById('form-instagram'),
        document.getElementById('form-telegram'),
        document.getElementById('form-twitter')
    ];
    const phonePreview = document.querySelector('.phone-preview');
    const themeClasses = ['wa-theme', 'fb-theme', 'ig-theme', 'tg-theme', 'tw-theme'];
    function updateTheme(idx) {
      phonePreview.classList.remove(...themeClasses);
      phonePreview.classList.add(themeClasses[idx]);
    }
    btns.forEach((btn, idx) => {
        btn.addEventListener('click', () => {
            updateTheme(idx);
            updatePreview(idx);
            btns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            forms.forEach(f => f.classList.remove('active'));
            forms[idx].classList.add('active');
            // Mostrar solo el <p> de la pestaña activa
            const descs = document.querySelectorAll('.tab-desc');
            descs.forEach((desc, dIdx) => {
                if (dIdx === idx) {
                    desc.classList.add('active');
                } else {
                    desc.classList.remove('active');
                }
            });
            document.getElementById('result').style.display = 'none';
        });
    });
    // Tema inicial por defecto (WhatsApp)
    updateTheme(0);
    // Cambia la previsualización en tiempo real
    textareas.forEach((ta, idx) => {
        ta.addEventListener('input', () => {
            updatePreview(idx);
        });
    });
    // Inicializa preview
    updatePreview(0);

    // Formulario WhatsApp
    document.getElementById('form-whatsapp').onsubmit = function(e) {
        e.preventDefault();
        
        if (!validatePhoneNumber()) {
            document.getElementById('wa-phone').reportValidity();
            return false;
        }
        
        const phoneNumber = iti.getNumber(intlTelInputUtils.numberFormat.E164);
        const message = encodeURIComponent(document.getElementById('wa-message').value);
        const url = `https://wa.me/${phoneNumber.replace(/\D/g, '')}${message ? '?text=' + message : ''}`;
        showResult(url);
        return false;
    };
    document.getElementById('form-facebook').onsubmit = function(e) {
        e.preventDefault();
        const user = document.getElementById('fb-user').value.trim();
        const msg = encodeURIComponent(document.getElementById('fb-message').value.trim());
        if(!user) return;
        let url = `https://m.me/${user}`;
        if(msg) url += `?ref=${msg}`;
        showResult(url);
    };
    document.getElementById('form-instagram').onsubmit = function(e) {
        e.preventDefault();
        const user = document.getElementById('ig-user').value.trim();
        const msg = encodeURIComponent(document.getElementById('ig-message').value.trim());
        if(!user) return;
        let url = `https://ig.me/m/${user}`;
        if(msg) url += `?text=${msg}`;
        showResult(url);
    };
    document.getElementById('form-telegram').onsubmit = function(e) {
        e.preventDefault();
        const user = document.getElementById('tg-user').value.trim();
        const msg = encodeURIComponent(document.getElementById('tg-message').value.trim());
        if(!user) return;
        let url = `https://t.me/${user}`;
        if(msg) url += `?text=${msg}`;
        showResult(url);
    };
    document.getElementById('form-twitter').onsubmit = function(e) {
        e.preventDefault();
        const user = document.getElementById('tw-user').value.trim();
        const msg = encodeURIComponent(document.getElementById('tw-message').value.trim());
        if(!user) return;
        let url = `https://twitter.com/messages/compose?recipient_id=${user}`;
        if(msg) url += `&text=${msg}`;
        showResult(url);
    };

    function showResult(url) {
        const result = document.getElementById('result');
        const copyInstruction = document.querySelector('.copy-instruction');
        
        // Actualizar el resultado
        result.textContent = url;
        result.style.display = 'block';
        
        // Mostrar el mensaje de copiado solo en móviles/tablets
        if (window.innerWidth <= 1024) {
            copyInstruction.classList.add('active');
        } else {
            copyInstruction.classList.remove('active');
        }
        
        // Desplazarse suavemente al resultado
        result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    // Tooltip flotante que sigue el cursor
    const resultDiv = document.getElementById('result');
    let tooltipMsg = 'Copiar';
    let tooltipTimeout = null;
    if(resultDiv) {
        // Crea tooltip flotante
        const tooltip = document.createElement('div');
        tooltip.className = 'floating-tooltip';
        tooltip.textContent = tooltipMsg;
        document.body.appendChild(tooltip);

        function showTooltip() {
            tooltip.textContent = tooltipMsg;
            tooltip.classList.add('visible');
        }
        function hideTooltip() {
            tooltip.classList.remove('visible');
        }
        function moveTooltip(e) {
            const offset = 12;
            tooltip.style.left = (e.clientX + offset) + 'px';
            tooltip.style.top = (e.clientY - offset) + 'px';
        }
        resultDiv.addEventListener('mouseenter', showTooltip);
        resultDiv.addEventListener('mousemove', moveTooltip);
        resultDiv.addEventListener('mouseleave', hideTooltip);
        resultDiv.addEventListener('focus', (e) => {
            showTooltip();
            // Centra el tooltip en el div si es por teclado
            const rect = resultDiv.getBoundingClientRect();
            tooltip.style.left = (rect.left + rect.width/2) + 'px';
            tooltip.style.top = (rect.top - 16) + 'px';
        });
        resultDiv.addEventListener('blur', hideTooltip);
        function setTooltipMsg(msg) {
            tooltipMsg = msg;
            tooltip.textContent = msg;
        }
        function copyResult() {
            const textToCopy = resultDiv.textContent.trim();
            if(textToCopy.length > 0) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    setTooltipMsg('¡Copiado!');
                    showTooltip();
                    clearTimeout(tooltipTimeout);
                    tooltipTimeout = setTimeout(() => {
                        setTooltipMsg('Copiar');
                        showTooltip();
                    }, 1200);
                });
            }
        }
        resultDiv.addEventListener('click', copyResult);
        resultDiv.addEventListener('keydown', (e) => {
            if(e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                copyResult();
            }
        });
        resultDiv.setAttribute('tabindex', '0');
    }
});

// Funcionalidad del carrusel
    const carousel = document.querySelector('.social-btn-bar');
    const prevBtn = document.querySelector('.carousel-arrow.prev');
    const nextBtn = document.querySelector('.carousel-arrow.next');
    const socialBtns = document.querySelectorAll('.social-btn');
    const scrollAmount = 150; // Cantidad de desplazamiento en píxeles

    // Solo activar en móviles
    if (window.innerWidth <= 768 && carousel) {
        // Función para desplazar el carrusel
        const scrollCarousel = (direction) => {
            carousel.scrollBy({
                left: direction === 'next' ? scrollAmount : -scrollAmount,
                behavior: 'smooth'
            });
        };

        // Event listeners para los botones
        if (prevBtn && nextBtn) {
            prevBtn.addEventListener('click', () => scrollCarousel('prev'));
            nextBtn.addEventListener('click', () => scrollCarousel('next'));
        }

        // Actualizar visibilidad de los botones según la posición del scroll
        const updateButtonVisibility = () => {
            if (!prevBtn || !nextBtn) return;
            
            const { scrollLeft, scrollWidth, clientWidth } = carousel;
            
            // Mostrar/ocultar botón anterior
            if (scrollLeft > 10) {
                prevBtn.style.visibility = 'visible';
                prevBtn.style.opacity = '1';
            } else {
                prevBtn.style.visibility = 'hidden';
                prevBtn.style.opacity = '0';
            }
            
            // Mostrar/ocultar botón siguiente
            if (scrollLeft < scrollWidth - clientWidth - 10) {
                nextBtn.style.visibility = 'visible';
                nextBtn.style.opacity = '1';
            } else {
                nextBtn.style.visibility = 'hidden';
                nextBtn.style.opacity = '0';
            }
        };

        // Inicializar visibilidad de los botones
        updateButtonVisibility();
        
        // Actualizar visibilidad cuando se hace scroll
        carousel.addEventListener('scroll', updateButtonVisibility);
        
        // Actualizar visibilidad al cambiar el tamaño de la ventana
        window.addEventListener('resize', updateButtonVisibility);
    }
