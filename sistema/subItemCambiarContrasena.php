<?php 
  include_once('./funciones/controlAcceso.php');
  $nav_item_home = "active";
  $nav_item_interesado = "";
  $nav_item_usuario = "";
  $nav_item_escanear = "";
?> 
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SiGeAl - Bedelia</title>
   <?php include_once('mod_css.html'); ?>
   <link rel="stylesheet" href="./css/custom.css">
   <?php include("mod_jquery.html"); ?>
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
              <li class="breadcrumb-item active" aria-current="page">Calendario</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="titulo"></div>
  </article>

  <article class="container">
       <section>
            <div class="row" id="resultado">
				<div class="col-xs-12 col-sm-12 col-md-3">&nbsp;</div>
				<div class="col-xs-12 col-sm-12 col-md-6">
        <form id="form">
  <div class="form-group row">
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-address-card"></i>
          </div>
        </div> 
        <input id="inputContrasena" placeholder="Contraseña" type="password" class="form-control" minlength="8" maxlength="15" required>
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
        <input id="inputReContrasena" placeholder="confirmar Contraseña" type="password" class="form-control" minlength="8" maxlength="15" required>
      </div>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-8">
      <div class="input-group">
        <button id="btnguardar" class="btn-primary btn-block" onclick="guardarContrasena(event)" >Guardar</button>
      </div>
    </div>
  </div>

</form>				
</div>
</div><!-- Cierra Row-->

<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12" id="resultado_accion"></div>
</div>
</section>
</article>

  

<!-- FOOTER -->
<?php include("mod_footer.html"); ?>

</body>
</html>

<script >
  function guardarContrasena(evt) {
      let url = "./funciones/usuarioCambiarContrasena.php";
      let parametros = "";
      let pwd=$("#inputContrasena").val();
      let repwd=$("#inputReContrasena").val();
      let msg = "";
      evt.preventDefault();
      if (pwd!="" && repwd!="") {
          if (pwd == repwd) {
              parametros = {"password":pwd,"repassword":repwd};
              $.post(url,parametros, function(data) {
                  if (data.codigo==100) {
                      msg = `<div class="alert alert-success">
                                  <img src="../assets/img/icons/ok_icon.png" width="35">&nbsp; 
                                  <strong>Contraseña fue Cambiada Correctamente</strong></div>`;
                            $("#resultado_accion").html(msg);
                            $("#inputContrasena").prop('disabled', true);
                            $("#inputReContrasena").prop('disabled', true);
                            $("#btnguardar").prop('disabled', true);
                  } else {
                      let msg = `<div class="alert alert-danger">
                              <img src="../assets/img/icons/error_icon.png" width="35">&nbsp;
                              <strong>`+data.mensaje+`</strong></div>`;
                      $("#resultado_accion").html(msg);
                  }
              
            },"json");
          } else {
             msg = `<div class="alert alert-danger">
                          <img src="../assets/img/icons/error_icon.png" width="35">&nbsp;
                          <strong>No coinciden la Nueva Contraseña con su Confirmación.</strong></div>`;
            $("#resultado_accion").html(msg);
          };
    } else {
          msg = `<div class="alert alert-danger">
                          <img src="../assets/img/icons/error_icon.png" width="35">&nbsp;
                          <strong>Debe completar todos los campos de texto.</strong></div>`;
          $("#resultado_accion").html(msg);
    }

  };
</script>