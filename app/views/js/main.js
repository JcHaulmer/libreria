function eliminarProducto(id) {
    const producto = document.getElementById(`producto_${id}`);
    if (producto) {
        producto.remove();
    }
}
function loadProducts(movementId) {
    $.ajax({
        url: 'http://localhost/libreria/app/ajax/movementProductsAjax.php',
        type: 'POST',
        data: { id: movementId },
        success: function(response) {
            $('#modalContent').html(response);
        }
    });
}