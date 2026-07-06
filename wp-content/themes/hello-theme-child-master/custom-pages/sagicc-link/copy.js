document.addEventListener('DOMContentLoaded', function() {
    const copyBtn = document.querySelector('.copy-btn');
    const resultDiv = document.getElementById('result');
    
    // Función para verificar si hay un enlace generado
    function checkForLink() {
        if (resultDiv && resultDiv.textContent.trim()) {
            copyBtn.disabled = false;
            copyBtn.title = 'Copiar enlace';
        } else {
            copyBtn.disabled = true;
            copyBtn.title = 'Genera un enlace primero';
        }
    }
    
    // Verificar el estado inicial
    checkForLink();
    
    // Observar cambios en el div de resultado
    if (resultDiv) {
        const observer = new MutationObserver(checkForLink);
        observer.observe(resultDiv, { childList: true, characterData: true, subtree: true });
    }
    
    if (copyBtn) {
        copyBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            if (!resultDiv || !resultDiv.textContent.trim()) {
                return; // No debería pasar ya que el botón está deshabilitado
            }
            
            const link = resultDiv.textContent.trim();
            
            try {
                await navigator.clipboard.writeText(link);
                
                // Cambiar temporalmente el ícono y el texto
                const icon = copyBtn.querySelector('i');
                const originalIcon = icon.className;
                const originalText = copyBtn.querySelector('span').textContent;
                
                icon.className = 'fas fa-check';
                copyBtn.querySelector('span').textContent = '¡Copiado!';
                
                // Restaurar después de 2 segundos
                setTimeout(() => {
                    icon.className = originalIcon;
                    copyBtn.querySelector('span').textContent = originalText;
                }, 2000);
                
            } catch (err) {
                console.error('Error al copiar el enlace:', err);
                // Fallback para navegadores más antiguos
                const textArea = document.createElement('textarea');
                textArea.value = link;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                // Mostrar feedback visual de todas formas
                const icon = copyBtn.querySelector('i');
                const originalIcon = icon.className;
                const originalText = copyBtn.querySelector('span').textContent;
                
                icon.className = 'fas fa-check';
                copyBtn.querySelector('span').textContent = '¡Copiado!';
                
                setTimeout(() => {
                    icon.className = originalIcon;
                    copyBtn.querySelector('span').textContent = originalText;
                }, 2000);
            }
        });
    }
});
