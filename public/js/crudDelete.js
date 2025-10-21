// crud-delete.js
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            const nombre = this.dataset.nombre || 'este elemento';

            Swal.fire({
                title: '¿Estás seguro?',
                text: `Se eliminará: ${nombre}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                // Puedes cambiar el icono usando html
                // iconHtml: '<i class="fas fa-trash"></i>', 
                // customClass: { icon: 'text-red-600' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
