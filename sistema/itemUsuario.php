<!doctype html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SiGeAl - Bedelia</title>
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

  </style>
  
      
</head>
<body>
  <!-- NAVBAR -->
 <header>
    <?php include("mod_navbar.php"); ?>
  </header>

  <article>
    <div id="breadcrumb">
      <nav aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">
              <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Alumno</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="titulo" style="background-color: #C1E0F5;"></div>
  </article>

  <article class="container">
       <section>
            <div class="row" id="resultado"></div><!-- Cierra Row-->
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
        <h6>Desea continuar con la Eliminaci&oacute;n del Usuario?</h6>
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
        <h6>Desea continuar con la Eliminaci&oacute;n de los Usuarios seleccionados?</h6>
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
        <h6>No ha seleccionado ningún Usuario.</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<?php include("mod_footer.html"); ?>


<!-- JAVASCRIPT CUSTOM -->
<script src="./js/usuario.js"></script>
</body>
</html>