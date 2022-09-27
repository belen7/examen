<?php
session_start();
session_destroy();
$id_pagina = 'login';
 ?>

<!doctype html>
<html lang="es">
<head>
  <title>Escuela Normal Superior 40 - Gestion de Alumnado</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="./public/img/favicon.ico">
  <!-- css custom bootstrap theme -->
  <link rel="stylesheet" href="./public/css/custom.css" type="text/css">
  <!-- assets fonts -->
  <link rel="stylesheet" href="https://www.santafe.gob.ar/assets/standard/css/fonts.css" type="text/css">
  <style>

  /* fallback */
  @font-face {
    font-family: 'Material Icons';
    font-style: normal;
    font-weight: 400;
    src: url(./public/assets/material-design-icons/MaterialIcons-Regular.eot); /* For IE6-8 */
    src: local('Material Icons'),
    local('MaterialIcons-Regular'),
    url(./public/assets/material-design-icons/MaterialIcons-Regular.woff2) format('woff2'),
    url(./public/assets/material-design-icons/MaterialIcons-Regular.woff) format('woff'),
    url(./public/assets/material-design-icons/MaterialIcons-Regular.ttf) format('truetype');
  }

  .material-icons {
    font-family: 'Material Icons';
    font-weight: normal;
    font-style: normal;
    font-size: 24px;
    line-height: 1;
    letter-spacing: normal;
    text-transform: none;
    display: inline-block;
    white-space: nowrap;
    word-wrap: normal;
    direction: ltr;
    -moz-font-feature-settings: 'liga';
    -moz-osx-font-smoothing: grayscale;
  }
  </style>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://www.google.com/recaptcha/api.js?render=6Ld6Y6oaAAAAAMqMNwMcdEBjkJugmHZ6Nu6Cpc5T"></script>
</head>


<body>
    <?php include_once('navBar.php') ?>


  <main role="main" class="container">
    <div class="row row-top">

      <div class="col-lg-12">



      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="card bg-light mb-12">
          <div class="card-header"><b><h1>Sistema de Gestion Academica</h1></b></div>
          <div class="card-body">
<form name="formAcceder" id="formAcceder" action='ajax/autenticar1.php'>
<div class="modal-body">
        <div class="form-row">
              <div class="form-group col-md-4">
                <label for="inputPerfil">Perfil</label>

                <select id="inputPerfil" name="inputPerfil" class="form-control" required>
                    <option value='' selected>Seleccione Perfil</option>
                    <option value='2' >Profesor</option>
                    <option value='1' >Alumno</option>
                </select>
              </div>
        </div>

       <div class="form-row">
              <div class="form-group col-xs-12 col-sm-4 col-md-4">
                    <label for="inputUsuario">Usuario</label>
                    <input type="text" class="form-control" name="inputUsuario" id="inputUsuario" maxlength="8" placeholder="Ingrese DNI">
              </div>

      </div>

      <div class="form-row">
        <div class="form-group col-xs-12 col-sm-4 col-md-4">
            <label for="inputPassword">Contrase&ntilde;a</label>
            <input type="password" class="form-control" name="inputPassword" id="inputPassword" maxlength="10" placeholder="Ingrese Contrase&ntilde;a">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group col-xs-12 col-sm-4 col-md-4 ">
          <button type="submit" class="btn btn-primary">Aceptar</button>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-xs-12 col-sm-12 col-md-12" id="resultado">

        </div>
      </div>

</div>
<div class="modal-footer">

</div>
</form>

</div>

          <div class="card-footer">
            <?php include_once('social.html');?>
          </div>

        </div>

      </div>
    </div>


    <div class="row">
      <div class="col-12">
        <p class="float-right">
          <a href="#"><i class="material-icons">keyboard_arrow_up</i></a>
        </p>
      </div>
    </div>


  </main>

  <div class="clearfix"><br></div>

<?php include_once('footer.php'); ?>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="./public/assets/jquery/jquery-3.2.1.slim/jquery-3.2.1.min.js"></script>
<script src="./public/assets/popper.js/1.12.3/umd/popper.min.js"></script>
<script src="./public/assets/bootstrap/bootstrap-4.0.0-beta.2/js/bootstrap.min.js"></script>
<script src="./public/assets/custom/verify.min.js"></script>
    </body>
</html>
