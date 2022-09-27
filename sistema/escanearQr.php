<!doctype html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SiAcreAc - Sistema Acreditacion Academica</title>
   <?php include_once('mod_css.html'); ?>
   <link rel="stylesheet" href="./css/custom.css">
   <link rel="stylesheet" href="./dist/css/qrcode-reader.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
   <?php //include("mod_jquery.html"); ?>
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
              <li class="breadcrumb-item active" aria-current="page">Interesados</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="titulo" style="background-color: #C1E0F5;"></div>
  </article>

  <article class="container">
       <section>
          <div class="jumbotron jumbotron-fluid">
              <div class="container">
                  <h1 class="display-4">Escanear QR</h1>
                  <hr>
                  <p class="lead">Estos datos son totalmente confidenciales.</p>
                  <form>
                  <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-9">
                         <textarea id="target-input" style="display:none;"></textarea>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-9">
                         <input class="btn btn-success" type="button" id="openreader-btn" value="Scan QRCode"/>
                      </div>
                  </div>
                  </form>  
              </div>
          </div>
        </section>
  </article>

<!-- FOOTER -->
<?php include("mod_footer.html"); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="./dist/js/qrcode-reader.js?v=20190604"></script>
<!-- JAVASCRIPT CUSTOM -->

<script>
  
  $("#openreader-btn").qrCodeReader({
  target: "#target-input",
  audioFeedback: true,
  multiple: true,
  skipDuplicates: false,
  callback: function(codes) {
      console.log(codes[0]);
      window.location.href = codes[0];

  }
});

    
</script>


</body>
</html>