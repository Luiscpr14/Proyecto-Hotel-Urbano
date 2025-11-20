const NOMBRE_COOKIE = 'carrito_urbano';

const Carrito = {
    //Obtener datos de la cookie
    obtener: function() {
        const cookies = document.cookie.split('; ');
        const cookie = cookies.find(c => c.startsWith(NOMBRE_COOKIE + '='));
        if (cookie) {
            try {
                return JSON.parse(decodeURIComponent(cookie.split('=')[1]));
            } catch(e) { return []; }
        }
        return [];
    },

    //Guardar datos en la cookie (1 día de duración)
    guardar: function(carrito) {
        const str = encodeURIComponent(JSON.stringify(carrito));
        document.cookie = `${NOMBRE_COOKIE}=${str}; path=/; max-age=86400`;
    },

    // CAMBIO: Ahora recibimos 'imagen'
    agregar: function(id, numero, precio, categoria, imagen) {
        let carrito = this.obtener();
        //Verificar si ya está en el carrito
        let existe = carrito.find(item => item.id === id);

        if (existe) {
            existe.cantidad++;
            alert(`Se aumentó la cantidad de la habitación ${numero}.`);
        } else {
            carrito.push({ id, numero, precio, categoria, imagen, cantidad: 1 });
            alert(`Habitación ${numero} agregada al carrito.`);
        }
        
        this.guardar(carrito);
    },

    //Métodos para la página del carrito (carrito.php)
    cambiarCantidad: function(id, delta) {
        let carrito = this.obtener();
        let item = carrito.find(item => item.id === id);
        if (item) {
            item.cantidad += delta;
            if (item.cantidad <= 0) this.eliminar(id);
            else {
                this.guardar(carrito);
                this.renderizarTabla();
            }
        }
    },

    eliminar: function(id) {
        if(!confirm("¿Eliminar esta habitación del carrito?")) return;
        let carrito = this.obtener();
        carrito = carrito.filter(item => item.id !== id);
        this.guardar(carrito);
        this.renderizarTabla();
    },

    //Calcula días y total estancia
    renderizarTabla: function() {
        const tbody = document.getElementById('tabla-carrito');
        const thead = document.querySelector('table.carrito thead'); // Referencia al header
        
        if (!tbody) return;

        //Referencias a los elementos del DOM
        const totalNocheSpan = document.getElementById('total-noche');
        const diasEstanciaSpan = document.getElementById('dias-estancia');
        const totalEstanciaSpan = document.getElementById('total-estancia');
        const inputData = document.getElementById('datos_reserva_input');
        const checkinInput = document.getElementById('checkin');
        const checkoutInput = document.getElementById('checkout');

        let carrito = this.obtener();
        let html = '';
        let totalPorNoche = 0;

        // Lógica para ocultar/mostrar encabezados
        if (carrito.length === 0) {
            if(thead) thead.style.display = 'none'; // OCULTAR ENCABEZADOS
            
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" align="center" style="padding:40px;">
                        <div style="color:#888; font-size:1.1rem;">
                            <p>Tu carrito está vacío.</p>
                            <a href="index.php" class="btn-confirmar" style="display:inline-block; background:#666; margin-top:10px; text-decoration:none; width:auto;">Ver Habitaciones</a>
                        </div>
                    </td>
                </tr>`;
                
            totalNocheSpan.innerText = '0.00';
            diasEstanciaSpan.innerText = '0';
            totalEstanciaSpan.innerText = '0.00';
            if(inputData) inputData.value = '';
            return;
        } else {
            if(thead) thead.style.display = 'table-header-group'; // MOSTRAR ENCABEZADOS
        }

        //Renderizar filas de la tabla
        carrito.forEach(item => {
            let subtotal = item.precio * item.cantidad;
            totalPorNoche += subtotal;
            
            // Fallback por si es un item antiguo sin imagen
            let imgUrl = item.imagen ? item.imagen : 'img/sin_imagen.jpg';

            html += `
                <tr>
                    <!-- Nueva columna de imagen -->
                    <td class="col-imagen">
                        <img src="${imgUrl}" alt="Habitación" onerror="this.src='img/sin_imagen.jpg'">
                    </td>
                    <td>
                        <div class="info-hab">
                            <strong>${item.categoria}</strong>
                            <span class="num-hab">#${item.numero}</span>
                        </div>
                    </td>
                    <td class="precio-unitario">$${parseFloat(item.precio).toFixed(2)}</td>
                    <td align="center">
                        <div class="controles-cantidad">
                            <button type="button" onclick="Carrito.cambiarCantidad(${item.id}, -1)">-</button>
                            <span>${item.cantidad}</span>
                            <button type="button" onclick="Carrito.cambiarCantidad(${item.id}, 1)">+</button>
                        </div>
                    </td>
                    <td class="subtotal">$${subtotal.toFixed(2)}</td>
                    <td align="center">
                        <a href="javascript:void(0);" onclick="Carrito.eliminar(${item.id})" class="btn-eliminar" title="Eliminar">
                            <img src="imagenes/borrar.svg" alt="Eliminar">
                        </a>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;

        // Cálculos de fechas (Igual que antes)
        let dias = 0;
        if (checkinInput.value && checkoutInput.value) {
            try {
                const date1 = new Date(checkinInput.value + 'T00:00:00');
                const date2 = new Date(checkoutInput.value + 'T00:00:00');
                if (date2 > date1) {
                    const diffTime = date2.getTime() - date1.getTime();
                    dias = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                }
            } catch(e) { dias = 0; }
        }

        //Calcular total estancia
        let totalEstancia = totalPorNoche * dias;

        //Actualizar totales en el DOM
        totalNocheSpan.innerText = totalPorNoche.toFixed(2);
        diasEstanciaSpan.innerText = dias;
        totalEstanciaSpan.innerText = totalEstancia.toFixed(2);
        
        //Actualizar input oculto
        if(inputData) inputData.value = JSON.stringify(carrito);
    }
};

//Inicializar y añadir listeners de fechas
document.addEventListener('DOMContentLoaded', () => {
    if(document.getElementById('tabla-carrito')) {
        const checkin = document.getElementById('checkin');
        const checkout = document.getElementById('checkout');

        //Validar fecha mínima de checkout al cambiar checkin
        const actualizarFechas = function() {
            if (checkin.value) {
                const fechaCheckin = new Date(checkin.value + 'T00:00:00'); 
                const fechaMinSalida = new Date(fechaCheckin);
                fechaMinSalida.setDate(fechaCheckin.getDate() + 1);
                
                const mes = (fechaMinSalida.getMonth() + 1).toString().padStart(2, '0');
                const dia = fechaMinSalida.getDate().toString().padStart(2, '0');
                const anio = fechaMinSalida.getFullYear();
                const minStr = `${anio}-${mes}-${dia}`;
                
                checkout.min = minStr;

                //Si la fecha de salida seleccionada es menor a la nueva mínima, limpiarla
                if(checkout.value && checkout.value < minStr) {
                    checkout.value = minStr;
                }
            }
            Carrito.renderizarTabla();
        };

        if(checkin) {
            checkin.addEventListener('change', actualizarFechas);
            checkin.addEventListener('input', actualizarFechas);
        }

        if(checkout) {
            checkout.addEventListener('change', () => Carrito.renderizarTabla());
            checkout.addEventListener('input', () => Carrito.renderizarTabla());
        }

        //Renderizar tabla inicial
        Carrito.renderizarTabla();
    }
});