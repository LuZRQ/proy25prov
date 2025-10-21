document.addEventListener('DOMContentLoaded', () => {
    let pedido = [];
    const mesaSelect = document.getElementById('select-mesa');
    const pedidoItems = document.getElementById('pedido-items');
    const pedidoTotal = document.getElementById('pedido-total');
    const comentarioText = document.getElementById('comentario-text');
    const formEnviar = document.getElementById('form-enviar');

    // Filtrar productos
   // Filtrar productos
document.querySelectorAll('.btn-categoria').forEach(btn => {
    btn.addEventListener('click', () => {
        const categoriaId = btn.dataset.categoria;

        // Quitar la clase activa de todos los botones
        document.querySelectorAll('.btn-categoria').forEach(b => {
            b.classList.remove('bg-amber-700', 'text-white');
            b.classList.add('bg-amber-100', 'text-amber-800');
        });

        // Agregar clase activa al botón clickeado
        btn.classList.add('bg-amber-700', 'text-white');
        btn.classList.remove('bg-amber-100', 'text-amber-800');

        // Filtrar productos
        document.querySelectorAll('.producto-card').forEach(card => {
            card.style.display = (categoriaId === 'all' || card.dataset.categoria == categoriaId) ? 'block' : 'none';
        });
    });
});


    // Agregar productos
    document.querySelectorAll('.btn-agregar').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = parseInt(btn.dataset.id);
            const nombre = btn.dataset.nombre;
            const precio = parseFloat(btn.dataset.precio);
            let item = pedido.find(p => p.idProducto === id);
            if (item) item.cantidad++;
            else pedido.push({ idProducto: id, nombre, precio, cantidad: 1 });
            renderPedido();
        });
    });

    // Renderizar pedido
    function renderPedido() {
        pedidoItems.innerHTML = '';
        let total = 0;
        pedido.forEach(item => {
            total += item.precio * item.cantidad;

            const div = document.createElement('div');
            div.className = 'flex justify-between items-center mb-3';
            div.innerHTML = `
                <div class="flex items-center gap-2">
                    <button class="px-2 py-1 bg-amber-200 rounded hover:bg-amber-300 btn-decrement">-</button>
                    <span>${item.cantidad}</span>
                    <button class="px-2 py-1 bg-amber-200 rounded hover:bg-amber-300 btn-increment">+</button>
                    <span class="text-amber-900 font-semibold">${item.nombre}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-amber-700">Bs. ${(item.precio * item.cantidad).toFixed(2)}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 cursor-pointer btn-eliminar" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 11v6m4-6v6" />
                    </svg>
                </div>
            `;
            pedidoItems.appendChild(div);

            // Eventos
            div.querySelector('.btn-increment').addEventListener('click', () => cambiarCantidad(item.idProducto, 1));
            div.querySelector('.btn-decrement').addEventListener('click', () => cambiarCantidad(item.idProducto, -1));
            div.querySelector('.btn-eliminar').addEventListener('click', () => eliminarItem(item.idProducto));
        });
        pedidoTotal.innerText = 'Bs. ' + total.toFixed(2);
    }

    // Cambiar cantidad
    function cambiarCantidad(id, delta) {
        let item = pedido.find(p => p.idProducto === id);
        if (item) {
            item.cantidad += delta;
            if (item.cantidad <= 0) pedido = pedido.filter(p => p.idProducto !== id);
            renderPedido();
        }
    }

    // Eliminar item
    function eliminarItem(id) {
        pedido = pedido.filter(p => p.idProducto !== id);
        renderPedido();
    }

    // Enviar pedido
    const btnEnviar = document.getElementById('btn-enviar-pedido');
if (btnEnviar) {
    btnEnviar.addEventListener('click', () => {
        if (pedido.length === 0) return alert("No hay productos en el pedido");

        formEnviar.mesa.value = mesaSelect.value.replace("Mesa: ", "");
        formEnviar.comentarios.value = comentarioText.value;
        formEnviar.productos.value = JSON.stringify(pedido);
        formEnviar.submit();
        alert("✅ Pedido enviado correctamente a cocina.");
        cancelarPedido();
    });
}


    // Cancelar pedido
 const btnCancelar = document.getElementById('btn-cancelar-pedido');
if (btnCancelar) {
    btnCancelar.addEventListener('click', () => {
        cancelarPedido();
    });
}


    function cancelarPedido() {
        pedido = [];
        renderPedido();
    }
});
