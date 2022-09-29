<?php
require_once('Sanitize.class.php');
abstract class SanitizeVarsCustom extends SanitizeVars{
    /* Developer jfontanellaz@gmail.com */
    /* Recibe una cadena de texto y la devuelvo si son solo numeros y letras, sino devuelvo false*/
    public static function DOMICILIO($strNumber){
       $patron = "/^[a-zA-Z0-9_  áéíóúñüÁÉÍÓÚÑÜ.]/";
       if (preg_match($patron, $strNumber)) {
         return $strNumber;
      } else return false;
    }

    

}//-- end CLASS
