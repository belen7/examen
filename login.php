<!doctype html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SiAcreAc - Sistema Acreditacion Academica</title>
   <?php include_once('mod_css.html'); ?>
   <link rel="stylesheet" href="./sistema/css/custom.css">
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
  <!-- NAVBAR -->
 <header>
    <?php include("mod_navbar.php"); ?>
  </header>

  <article>
    <div id="breadcrumb">
      <nav aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">
              <li class="breadcrumb-item" aria-current="page">Home</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="titulo" style="background-color: #C1E0F5;"></div>
  </article>

  <article class="container">
       <section>
            <div id="resultado">

            <div class="jumbotron jumbotron-fluid rounded">
  <div class="container">
    <h1 class="display-4">Ingresar al Sistema</h1>
    <hr>
    <p class="lead">Estos datos son estrictamente confidenciales.</p>

<form id="form">
<div class="form-group row">
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-address-card"></i>
          </div>
        </div> 
        <input id="inputEmail" placeholder="Ingrese Usuario" type="email" class="form-control" minlength="4" maxlength="35" required>
      </div>
    </div>
  </div>
  
  <div class="form-group row">
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-address-card"></i>
          </div>
        </div> 
        <input id="inputPassword" placeholder="Ingrese Contraseña" type="password" class="form-control" minlength="8" maxlength="15" required>
      </div>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-8">
       <button type="button" class="btn btn-primary btn-block" onclick="autenticarUsuario()">Ingresar</button>
    </div>
  </div>
</form>
</div>
</div>



            </div><!-- Cierra Row-->
            <div class="row" id="resultado_accion">

            </div><!-- Cierra Row-->
        </section>
  </article>


<!-- FOOTER -->
<?php include("mod_footer.html"); ?>


<!-- JAVASCRIPT CUSTOM -->
<script>
    
    function autenticarUsuario() {
        let usuario = $("#inputEmail").val();
        let password = $("#inputPassword").val();
        let parametros = {'usuario':usuario,'password':password};
        let url = "./sistema/funciones/usuarioAutenticar.php";
        $.post(url, parametros, function (data) {
             if (data.codigo==100) {
                  location.href="./sistema/home.php";
             } else {
                  $("#resultado_accion").html(`<div class="col-xs-12 col-sm-12 col-md-12 alert alert-danger">
                                               <strong>Atención: </strong>`+data.mensaje+`
                                           </div>`);
             }
        },"json");
    }    
    
</script>

</body>
</html>