document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('track');
    const nextButton = document.getElementById('nextBtn');
    const prevButton = document.getElementById('prevBtn');
    
    //Obtenemos todas las tarjetas
    const cards = Array.from(track.children);
    
    if(cards.length === 0) return;

    const cardWidth = cards[0].getBoundingClientRect().width;
    const gap = 20; 
    const moveAmount = cardWidth + gap;

    let currentPosition = 0;

    //Funcion para mover el carrusel
    const moveToPosition = (position) => {
        track.style.transform = `translateX(-${position}px)`;
    };

    nextButton.addEventListener('click', () => {
        //Calculamos el ancho total del contenido
        const trackWidth = track.scrollWidth;
        const containerWidth = track.parentElement.clientWidth;
        
        /*Si movernos mas excede el ancho total, 
        volvemos al inicio o simplemente nos detenemos.*/
        const maxScroll = trackWidth - containerWidth;

        if (currentPosition < maxScroll) {
            currentPosition += moveAmount;
            if (currentPosition > maxScroll) currentPosition = maxScroll;
            moveToPosition(currentPosition);
        } else {
            currentPosition = 0;
            moveToPosition(currentPosition);
        }
    });

    prevButton.addEventListener('click', () => {
        if (currentPosition > 0) {
            currentPosition -= moveAmount;
            if (currentPosition < 0) currentPosition = 0;
            moveToPosition(currentPosition);
        }
    });
});

//Funcion para desplegar la descripcion
function toggleDetalles(id) {
    const desc = document.getElementById('desc-' + id);
    if (desc.style.display === "block") {
        desc.style.display = "none";
    } else {
        desc.style.display = "block";
    }
}