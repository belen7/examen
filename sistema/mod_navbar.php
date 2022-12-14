
<nav class="navbar navbar-expand-sm navbar-light" style="background-color:#e3e1e2" id="myNavbar">
<a href="#" class="navbar-brand"><img src="../assets/img/logo.png" width="60">&nbsp;Gesti&oacute;n de Acreditación</a>
 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
 </button>

 <div class="collapse navbar-collapse" id="mainNav">

 <ul class="navbar-nav">

   <li class="nav-item <?=$nav_item_home;?>">
    <a class="nav-link" href="home.php">
      <img src="../assets/img/icons/home_icon.png" width="23">
      <span class="sr-only">(current)</span></a>
   </li>

</ul>

<ul class="navbar-nav ml-auto">


<li class="nav-item <?=$nav_item_disertante;?>">
       <a class="nav-link" href="itemDisertante.php">
         Disertante
       <span class="sr-only">(current)</span></a>
   </li>

   <li class="nav-item <?=$nav_item_interesado;?>">
       <a class="nav-link" href="itemInteresado.php">
         Interesados
       <span class="sr-only">(current)</span></a>
   </li>
   
   <li class="nav-item <?=$nav_item_usuario;?>">
       <a class="nav-link <?=$nav_item_usuario_disabled;?>" href="itemUsuario.php">
         Usuarios
       <span class="sr-only">(current)</span></a>
   </li>

   <li class="nav-item <?=$nav_item_escanear;?>">
       <a class="nav-link <?=$nav_item_escanear_disabled;?>" href="itemEscanearQr.php">
         Escanear QR
       <span class="sr-only">(current)</span></a>
   </li>

<li class="nav-item px-4 dropdown">
      <a class="nav-link dropdown-toggle text-white" href="#" id="servicesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="../assets/img/icons/user_icon.png" width="22">
      </a>
      <div class="dropdown-menu dropdown-menu-right bg-info" aria-labelledby="servicesDropdown">
      <a class="dropdown-item <?=$nav_item_cambiar_pwd;?>" href="subItemCambiarContrasena.php">Cambiar contraseña</a>
       <a class="dropdown-item" href="logout.php">Salir</a>
       
   </div>
   
 </li>
</ul>

 </div>
</nav>