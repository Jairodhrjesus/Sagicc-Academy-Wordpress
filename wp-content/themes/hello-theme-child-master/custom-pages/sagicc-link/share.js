document.addEventListener('DOMContentLoaded', function() {
    const shareBtn = document.querySelector('.share-btn');
    const resultDiv = document.getElementById('result');
    
    // Función para verificar si hay un enlace generado
    function checkForLink() {
        if (resultDiv && resultDiv.textContent.trim()) {
            shareBtn.disabled = false;
            shareBtn.title = 'Compartir enlace';
        } else {
            shareBtn.disabled = true;
            shareBtn.title = 'Genera un enlace primero';
        }
    }
    
    // Verificar el estado inicial
    checkForLink();
    
    // Observar cambios en el div de resultado
    if (resultDiv) {
        const observer = new MutationObserver(checkForLink);
        observer.observe(resultDiv, { childList: true, characterData: true, subtree: true });
    }
    
    if (shareBtn) {
        shareBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            if (!resultDiv || !resultDiv.textContent.trim()) {
                return; // No debería pasar ya que el botón está deshabilitado
            }
            
            const link = resultDiv.textContent.trim();
            
            try {
                // Verificar si la API de Web Share está disponible
                if (navigator.share) {
                    await navigator.share({
                        title: 'Enlace generado',
                        text: '¡Mira este enlace que he creado!',
                        url: link
                    });
                } else {
                    // Fallback para navegadores que no soportan la API de Web Share
                    await navigator.clipboard.writeText(link);
                    
                    // Cambiar temporalmente el ícono para indicar que se copió
                    const icon = shareBtn.querySelector('i');
                    const originalIcon = icon.className;
                    const originalText = shareBtn.querySelector('span').textContent;
                    
                    icon.className = 'fas fa-check';
                    shareBtn.querySelector('span').textContent = 'Copiado!';
                    
                    // Restaurar el ícono después de 2 segundos
                    setTimeout(() => {
                        icon.className = originalIcon;
                        shareBtn.querySelector('span').textContent = originalText;
                    }, 2000);
                }
            } catch (err) {
                console.error('Error al compartir:', err);
            }
        });
    }
});
