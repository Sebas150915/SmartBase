document.getElementById("fecha_emision").addEventListener("change", function() {
    const fecha = this.value; // Obtener la fecha seleccionada
   // alert(fecha);
    if (fecha) {
        fetch(base_url+'/assets/ajax/ajax_obtener_tc.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ fecha: fecha })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data);
                document.getElementById("tcambio").value = data.tventa;
            } else {
                alert("No se encontró el tipo de cambio para la fecha seleccionada.");
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
