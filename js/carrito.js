// js/carrito.js
const NOMBRE_COOKIE = 'carrito_urbano';

const Carrito = {
    // 1. Obtener el carrito de la cookie
    obtener: function() {
        const cookies = document.cookie.split('; ');
        const cookie = cookies.find(c => c.startsWith(NOMBRE_COOKIE + '='));
        return cookie ? JSON.parse(decodeURIComponent(cookie.split('=')[1])) : [];
    },

    // 2. Guardar el carrito en la cookie
    guardar: function(carrito) {
        const str = encodeURIComponent(JSON.stringify(carrito));
        document.cookie = `${NOMBRE_COOKIE}=${str}; path=/; max-age=86400`; // 1 día
    },

    // 3. Agregar habitación (Llamado desde el botón en el listado)
    agregar: function(id, numero, precio, categoria) {
        let carrito = this.obtener();
        let existe = carrito.find(item => item.id === id);

        if (existe) {
            existe.cantidad++;
        } else {
            carrito.push({ id, numero, precio, categoria, cantidad: 1 });
        }
        
        this.guardar(carrito);
        alert(`Habitación ${numero} agregada al carrito.`);
    },

    // 4. Cambiar cantidad (Llamado desde carrito.php)
    cambiarCantidad: function(id, delta) {
        let carrito = this.obtener();
        let item = carrito.find(item => item.id === id);
        
        if (item) {
            item.cantidad += delta;
            if (item.cantidad <= 0) {
                this.eliminar(id); // Si baja a 0, eliminar
                return;
            }
            this.guardar(carrito);
            this.renderizarTabla();
        }
    },

    // 5. Eliminar habitación
    eliminar: function(id) {
        if(!confirm("¿Eliminar esta habitación de la reserva?")) return;
        let carrito = this.obtener();
        carrito = carrito.filter(item => item.id !== id);
        this.guardar(carrito);
        this.renderizarTabla();
    },

    // 6. Renderizar la tabla en carrito.php
    renderizarTabla: function() {
        const tbody = document.getElementById('tabla-carrito');
        const totalSpan = document.getElementById('total-carrito');
        const inputData = document.getElementById('datos_reserva_input');
        
        if (!tbody) return; 

        let carrito = this.obtener();
        let html = '';
        let total = 0;

        if (carrito.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center">Tu carrito está vacío.</td></tr>';
            totalSpan.innerText = '0.00';
            if(inputData) inputData.value = '';
            return;
        }

        carrito.forEach(item => {
            let subtotal = item.precio * item.cantidad;
            total += subtotal;
            
            html += `
                <tr>
                    <td>${item.numero} - ${item.categoria}</td>
                    <td>$${parseFloat(item.precio).toFixed(2)}</td>
                    <td style="text-align:center">
                        <button type="button" onclick="Carrito.cambiarCantidad(${item.id}, -1)">-</button>
                        <span style="margin:0 10px">${item.cantidad}</span>
                        <button type="button" onclick="Carrito.cambiarCantidad(${item.id}, 1)">+</button>
                    </td>
                    <td>$${subtotal.toFixed(2)}</td>
                    <td><button type="button" onclick="Carrito.eliminar(${item.id})" style="color:red">X</button></td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
        totalSpan.innerText = total.toFixed(2);
        
        // Actualizar el input oculto que se enviará al servidor
        if(inputData) inputData.value = JSON.stringify(carrito);
    }
};

// Inicializar tabla al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    if(document.getElementById('tabla-carrito')) {
        Carrito.renderizarTabla();
    }
});