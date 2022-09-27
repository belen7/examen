<!doctype html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SiAcreAc - Sistema Acreditacion Academica</title>
   <?php include_once('mod_css.html'); ?>
   <link rel="stylesheet" href="./css/custom.css">
   <?php include("mod_jquery.html"); ?>
  <style>
    .input-group > .select2-container--bootstrap {
      border-color: #EAEDED !important;
	width: auto;
	flex: 1 1 auto;
}

.input-group > .select2-container--bootstrap .select2-selection--single {
	height: 100%;
	line-height: inherit;
	padding: 0.5rem 1rem;
}

.disabledbutton {
          pointer-events: none;
          opacity: 0.5;
      }

      label.cameraButton {
  display: inline-block;
  margin: 1em 0;

  /* Styles to make it look like a button */
  padding: 0.5em;
  border: 2px solid #666;
  border-color: #EEE #CCC #CCC #EEE;
  background-color: #DDD;
}

/* Look like a clicked/depressed button */
label.cameraButton:active {
  border-color: #CCC #EEE #EEE #CCC;
}

/* This is the part that actually hides the 'Choose file' text box for camera inputs */
label.cameraButton input[accept*="camera"] {
  display: none;
}

  </style>
  
      
</head>
<body>
  <article class="container">
    <div id="titulo" style="background-color: #C1E0F5;"></div>
  </article>

  <article class="container">
       <section>
            <div id="resultado"></div><!-- Cierra Row-->
            <div class="row" id="resultado_accion">
            </div><!-- Cierra Row-->
        </section>
  </article>

<!-- Modal para confirmar eliminar solo uno -->
<div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><img src="../assets/img/icons/alert_icon_red.png" width="26">&nbsp;&nbsp;Atenci&oacute;n</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="inputEliminarId">
        <h6>Desea continuar con la Eliminaci&oacute;n del Interesado?</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="entidadEliminarSeleccionado($('#inputEliminarId').val())">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para confirmar eliminar solo varios-->
<div class="modal fade" id="confirmarEliminarTodosModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><img src="../assets/img/icons/alert_icon_red.png" width="26">&nbsp;&nbsp;Atenci&oacute;n</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6>Desea continuar con la Eliminaci&oacute;n de los Clientes seleccionados?</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="entidadEliminarSeleccionadosConfirmar()">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para advertir que no hay seleccionados -->
<div class="modal fade" id="sinElementosModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><img src="../assets/img/icons/alert_icon_red.png" width="26">&nbsp;&nbsp;Atenci&oacute;n</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6>No ha seleccionado ningún Cliente.</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalSuccess" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
         <div class="alert alert-success" role="alert">
            El registro ha sido exitoso.
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalError" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
         <div class="alert alert-danger" role="alert">
            <span id="contenido_modal"></span>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- JAVASCRIPT CUSTOM -->
<script src="./js/interesado.js"></script>
<script>
    $("body").on('change','#image', function() {
        var formData = new FormData();
        var files = $('#image')[0].files[0];
        formData.append('file',files);
        $.ajax({
            url: 'funciones/uploadFoto.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                  // setting a timeout
                  $(".rounded").attr("src", '../assets/img/load_icon.gif').width('300px');
            },
            success: function(response) {
                if (response != 0) {
                    $(".rounded").attr("src", '../fotos/'+response);
                    $('#inputAltaNombreFoto').val(response);
                } else {
                    alert('Formato de imagen incorrecto.');
                }
            }
        });
        return false;
    });
    
function valideKey(evt){
    
    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code==8) { // backspace.
      return true;
    } else if(code>=48 && code<=57) { // is a number.
      return true;
    } else{ // other keys.
      return false;
    }
}
    
</script>


</body>
</html>