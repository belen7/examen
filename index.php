<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SiGeAl - Bedelia</title>
   <?php include_once('mod_css.html'); ?>
   <link rel="stylesheet" href="./assets/css/custom2.css">
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
              <li class="breadcrumb-item" aria-current="page">Home</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="titulo"></div>
  </article>

  <article class="container">
       <section>
            <div class="row" id="filtro"></div><!-- Cierra Row-->
            <div class="row" id="resultado">
				<div class="col-xs-12 col-sm-12 col-md-3">&nbsp;</div>
				<div class="col-xs-12 col-sm-12 col-md-6">
				    <img src="./assets/img/esc40a.jpg">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3"></div>
			</div><!-- Cierra Row-->
            <div class="row" id="resultado_accion">&nbsp;</div><!-- Cierra Row-->
        </section>
  </article>

  

<!-- FOOTER -->
<?php include("mod_footer.html"); ?>

</body>
</html>