<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Modal con Listas Dependientes</title>
</head>
<body>

<!-- Botón para abrir la modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Abrir Modal
</button>

<!-- Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Cabecera de la Modal -->
      <div class="modal-header">
        <h4 class="modal-title">Listas Dependientes</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Cuerpo de la Modal -->
      <div class="modal-body">
        <div class="form-group">
          <label for="lista1">Lista 1:</label>
          <select class="form-control" id="lista1" onchange="cargarLista2()">
            <option value="opcion1">Opción 1</option>
            <option value="opcion2">Opción 2</option>
            <!-- Agrega más opciones según sea necesario -->
          </select>
        </div>
        <div class="form-group">
          <label for="lista2">Lista 2:</label>
          <select class="form-control" id="lista2">
            <!-- Esta lista se llenará dinámicamente al seleccionar una opción en la Lista 1 -->
          </select>
        </div>
      </div>

      <!-- Pie de la Modal -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Guardar</button>
      </div>

    </div>
  </div>
</div>

<!-- Scripts necesarios -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- Script personalizado -->
<script>
  function cargarLista2() {
    // Aquí puedes escribir lógica para cargar dinámicamente las opciones de la Lista 2
    // Puedes usar jQuery o JavaScript puro según tu preferencia
    var lista1Valor = $('#lista1').val();

    // Limpia la Lista 2
    $('#lista2').empty();

    // Llena dinámicamente la Lista 2 según la opción seleccionada en la Lista 1
    if (lista1Valor === 'opcion1') {
      $('#lista2').append('<option value="subopcion1">Subopción 1</option>');
      $('#lista2').append('<option value="subopcion2">Subopción 2</option>');
    } else if (lista1Valor === 'opcion2') {
      $('#lista2').append('<option value="subopcion3">Subopción 3</option>');
      $('#lista2').append('<option value="subopcion4">Subopción 4</option>');
    }
  }
</script>

</body>
</html>
