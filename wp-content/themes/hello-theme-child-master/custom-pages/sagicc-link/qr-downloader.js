document.addEventListener('DOMContentLoaded', function() {
    const downloadBtn = document.querySelector('.download-btn');
    const resultDiv = document.getElementById('result');
    
    // Función para verificar si hay un enlace generado
    function checkForLink() {
        if (resultDiv && resultDiv.textContent.trim()) {
            downloadBtn.disabled = false;
            downloadBtn.title = 'Descargar QR';
        } else {
            downloadBtn.disabled = true;
            downloadBtn.title = 'Genera un enlace primero';
        }
    }
    
    // Verificar el estado inicial
    checkForLink();
    
    // Observar cambios en el div de resultado
    if (resultDiv) {
        const observer = new MutationObserver(checkForLink);
        observer.observe(resultDiv, { childList: true, characterData: true, subtree: true });
    }
    
    // Manejador de clic para el botón de descarga
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (!resultDiv || !resultDiv.textContent.trim()) {
                return; // No debería pasar ya que el botón está deshabilitado
            }
            
            const link = resultDiv.textContent.trim();
            const qrSize = 300; // Tamaño del QR
            
            // Crear el enlace de descarga directa
            const qrUrl = `https://api.qrserver.com/v1/create-qr-code/` +
                         `?size=${qrSize}x${qrSize}` +
                         `&data=${encodeURIComponent(link)}` +
                         `&download=1` +
                         `&margin=10` +
                         `&qzone=2` +

                         
                         `&format=png`;
            
            // Crear un nombre de archivo personalizado con la fecha actual
            const now = new Date();
            const formattedDate = now.toISOString()
                .replace(/[:.]/g, '-')  // Reemplazar : y . por -
                .replace('T', '_')       // Reemplazar T por _
                .slice(0, 19);           // Tomar solo la parte de fecha/hora
                
            const fileName = `Sagicc_Academy_QR_${formattedDate}.png`;
            
            // Crear un enlace temporal para la descarga
            const downloadLink = document.createElement('a');
            downloadLink.href = qrUrl;
            downloadLink.download = fileName;
            downloadLink.style.display = 'none';
            
            // Agregar al documento y simular clic
            document.body.appendChild(downloadLink);
            downloadLink.click();
            
            // Limpiar después de la descarga
            setTimeout(() => {
                document.body.removeChild(downloadLink);
            }, 100);
        });
    }
});
