//************************************************************************************************ */
//************************************************************************************************ */
//*********************************** LISTADO DE ENTIDADES *************************************** */
//************************************************************************************************ */
//************************************************************************************************ */
let entidad_nombre = "categoria";
let entidad_titulo1 = "CATEGORÍAS";
let entidad_titulo2 = "Categorías";

$(function () {
    load(1);
});
  
//****************************************** */
// CARGA EL LISTADO DE TODOS LAS ENTIDADES   */
//****************************************** */
function load(page) {
        let id = $("#inputFiltroId").val();
        let descripcion = $("#inputFiltroDescripcion").val();
        let padre = $("#inputFiltroPadre").val();
        let busqueda = $("#inputBusquedaRapida").val();
        let per_page = 10;
        let parametros = {"action": "listar","page": page,"per_page": per_page, "id":id, "descripcion":descripcion, "padre":padre,"busqueda_rapida":busqueda};
  
        let titulo = "<h1><i><u>"+entidad_titulo1+"</u></i></h1>";
        let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item active" aria-current="page">`+entidad_titulo2+`</li>
                              </ol>
                          </nav>`;
  
        $("#titulo").html(titulo);
        $("#breadcrumb").html(breadcrumb);
        $.ajax({
            url: 'funciones/'+entidad_nombre+'Listar.php',
            data: parametros,
            method: 'POST',
            beforeSend: function () {
              $("#resultado").html("<img src='../assets/img/load_icon.gif' width='50' >");  
            },
            success: function (data) {
                $("#resultado").slideDown("slow").html(data);
            }
        });
};
  

//************************************************************************************************ */
//************************************************************************************************ */
//************************************* GESTION DE  FILTROS  ************************************* */
//************************************************************************************************ */
//************************************************************************************************ */  

//********************************************* */
// APLICA EL FILTRO AL LISTADO DE ENTIDADES     */
//********************************************* */
  function aplicarFiltro() {
    let id = $("#inputFiltroId").val();
    let descripcion = $("#inputFiltroDescripcion").val();
    let padre = $("#inputFiltroPadre").val();
    let busqueda = $("#inputBusquedaRapida").val();
    let per_page = 10;
    let parametros = {"action": "listar","page": 1,"per_page": per_page, "id":id, "descripcion":descripcion, "padre":padre,"busqueda_rapida":busqueda};
    let titulo = "<h1><i><u>"+entidad_titulo1+"</u></i></h1>";
  
        $("#titulo").html(titulo);
        $.ajax({
            url: 'funciones/'+entidad_nombre+'Listar.php',
            data: parametros,
            method: 'POST',
            success: function (data) {
                $("#resultado").slideDown("slow").html(data);
            }
        });
  };
  
  //******************************************* */
  // APLICA EL FILTRO AL LISTADO DE ENTIDADES   */
  //******************************************* */
  function aplicarBusquedaRapida() {
        let busqueda = $("#inputBusquedaRapida").val();  
        let id = $("#inputFiltroId").val();
        let descripcion = $("#inputFiltroDescripcion").val();
        let padre = $("#inputFiltroPadre").val();
        let per_page = 10;
        let parametros = {"action": "listar","page": 1,"per_page": per_page, "id":id, "descripcion":descripcion, "padre":padre,"busqueda_rapida":busqueda};
        let titulo = "<h1><i><u>"+entidad_titulo1+"</u></i></h1>";
        $("#titulo").html(titulo);
        $.ajax({
            url: 'funciones/'+entidad_nombre+'Listar.php',
            data: parametros,
            method: 'POST',
            success: function (data) {
                $("#resultado").slideDown("slow").html(data);
            }
        });
  };
  
  //******************************************* */
  // QUITA EL FILTRO DEL LISTADO DE ENTIDADES   */
  //******************************************* */
  function quitarFiltro() {
        $("#inputBusquedaRapida").val(""); 
        $("#inputFiltroId").val(""); 
        $("#inputFiltroDescripcion").val("");
        $("#inputFiltroPadre").val("");
        load(1);  
  };
  
  
//************************************************************************************************ */
//************************************************************************************************ */
//*********************************** CREACION DE UNA ENTIDAD ************************************ */
//************************************************************************************************ */
//************************************************************************************************ */

//************************************************ */
// NOS PERMITE CREAR UNA ENTIDAD                   */
//************************************************ */
function entidadCrear(){
      let arreglo="";
      let parametros = "";
      let url = "html/"+entidad_nombre+"Crear.html";
      let url_obtener_entidad = "funciones/"+entidad_nombre+"Obtener.php";
      let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
                              </ol>
                          </nav>`;
      $("#breadcrumb").slideDown("slow").html(breadcrumb);                    
      
      $.get(url,function(data) {
          $("#resultado").slideDown("slow").html(data);

          $.post(url_obtener_entidad,function(data1){
             if (data1.codigo==100) {
                    data1.data.forEach(entidad => {
                        $("#inputAltaPadre").append($('<option/>', {
                            text: '('+entidad.id+') '+entidad.descripcion,
                            value: entidad.id,
                        }));
                    });
                    $(function(){
                        $("#inputAltaPadre").select2({
                            theme: "bootstrap",
                            placeholder: "Search"
                        });
                    });
             };
          },"json");

      })
}
  
//************************************************* */
// GRABA LA ENTIDAD NUEVO EN LA BASE DE DATOS       */
//************************************************* */
  function entidadGuardarNuevo(){
    let descripcion = $("#inputAltaDescripcion").val();
    let idPadre = $("#inputAltaPadre").val();
    let parametros = {"id":idPadre,"descripcion":descripcion};
    let url = "funciones/"+entidad_nombre+"Crear.php";
    
    $.post(url,parametros, function(data) {
        if (data.codigo==100) {
                $("#resultado_accion").html(`
                                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                              <span style="color: #000000;">
                                              <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                  &nbsp;<strong>Atenci&oacute;n:</strong> `+data.mensaje+`
                                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>   
                                              </span>    
                                          </div>`);
        } else {
                $("#resultado_accion").html(`
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
                                            <span style="color: #000000;">
                                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                &nbsp;<strong>Atenci&oacute;n:</strong> `+data.mensaje+`
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>   
                                            </span>    
                                        </div>`);
        }
        load(1);
    },"json"); 
}


//************************************************************************************************ */
//************************************************************************************************ */
//************************************* EDICION DE LA ENTIDAD ************************************ */
//************************************************************************************************ */
//************************************************************************************************ */

//************************************************* */
// NOS PERMITE EDITAR UNA ENTIDAD                   */
//************************************************* */
function entidadEditar(entidad_id){
      let datos_entidad = "";
      let url = "html/"+entidad_nombre+"Editar.html";
      let url_obtener_entidad = "funciones/"+entidad_nombre+"Obtener.php";
      let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Editar</li>
                              </ol>
                          </nav>`;
      $("#breadcrumb").slideDown("slow").html(breadcrumb);   
      datos_entidad = entidadObtenerPorId(entidad_id);
      console.info(entidad_id);
      $.get(url,function(data) {
            $("#resultado").slideDown("slow").html(data);
            $("#inputEditarId").val(datos_entidad.datos[0].id);
            $("#inputEditarDescripcion").val(datos_entidad.datos[0].descripcion);

            $.post(url_obtener_entidad,function(data1){
                if (data1.codigo==100) {
                       data1.data.forEach(entidad => {
                           $("#inputEditarPadre").append($('<option/>', {
                               text: '('+entidad.id+') '+entidad.descripcion,
                               value: entidad.id,
                           }));
                       });
                       $(function(){
                           $("#inputEditarPadre").select2({
                               theme: "bootstrap",
                               placeholder: "Search"
                           });
                       });

                       $("#inputEditarPadre option[value="+ datos_entidad.datos[0].categoria_id +"]").attr("selected",true);
                };
             },"json");
      });
}
  
  
//************************************************** */
// NOS PERMITE BUSCAR UNA ENTIDAD POR ID ALUMNO      */
//************************************************** */
function entidadObtenerPorId(entidad_id){
    let url = "funciones/"+entidad_nombre+"ObtenerPorId.php";
    let resultado;
    
    $.ajaxSetup({
        async: false
      });
    $.post(url, {"id":entidad_id}, function (data) {
          resultado = data;
    },"json")
    
    return resultado;
}


//************************************************* */
// GRABA LA ENTIDAD NUEVO EN LA BASE DE DATOS       */
//************************************************* */
function entidadGuardarEditado(){
    let id = $("#inputEditarId").val();
    let descripcion = $("#inputEditarDescripcion").val();
    let padre = $("#inputEditarPadre").val();
    let parametros = {"id":id,"descripcion":descripcion,"padre":padre};
    let url = "funciones/"+entidad_nombre+"Editar.php";
    
    $.post(url,parametros, function(data) {
        if (data.codigo==100) {
                $("#resultado_accion").html(`
                                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                              <span style="color: #000000;">
                                              <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                  &nbsp;<strong>Atenci&oacute;n:</strong> `+data.mensaje+`
                                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>   
                                              </span>    
                                          </div>`);
        } else {
                $("#resultado_accion").html(`
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
                                            <span style="color: #000000;">
                                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                &nbsp;<strong>Atenci&oacute;n:</strong> `+data.mensaje+`
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>   
                                            </span>    
                                        </div>`);
        }
        load(1);
    },"json"); 
}

//************************************************************************************************ */
//************************************************************************************************ */
//************************************* ELIMINACION DEL ALUMNO   ********************************* */
//************************************************************************************************ */
//************************************************************************************************ */

//***************************************************************** */
// MANEJA LA SELECCION / DESELECCION DE TODOS LOS CHECKBOX          */
//***************************************************************** */
$("body").on("click","#seleccionar_todos", function() {
    if( $(this).is(':checked') ){
          // Hacer algo si el checkbox ha sido seleccionado
          $('.check').prop('checked',true);
      } else {
          // Hacer algo si el checkbox ha sido deseleccionado
          $('.check').prop('checked',false);
      }
  });
  
  
  //******************************************************************************************** */
  // VERFICICA SI HAY ENTIDADES SELECCIONADOS/CHECKBOX Y PIDE CONFIRMACION PARA SU ELIMINACION   */
  //******************************************************************************************** */
  function entidadEliminarSeleccionados(){
      let arreglo="";
      let cantidad_seleccionados = 0;
      $('.check:checked').each(
                  function() {
                      arreglo += ','+$(this).val();
                      cantidad_seleccionados++;
                  }
      );
      if (cantidad_seleccionados>0) {
              $("#confirmarEliminarTodosModal").modal("show");
      } else {
              $("#sinElementosModal").modal("show");
              $("#resultado_accion").html(`
                                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-warning">
                                              <span style="color: #000000;">
                                              <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                  &nbsp;<strong>Atenci&oacute;n:</strong> No hay usuarios seleccionados.
                                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>   
                                              </span>    
                                          </div>
              `);
      };
  }
  
  //*************************************************************************** */
  // ELIMINA TODOS LAS ENTIDADES CUYOS CHECKBOX ESTAN SELECCIONADOS             */
  //*************************************************************************** */
  function entidadEliminarSeleccionadosConfirmar(){
      let arreglo="";
      let parametros = "";
      let url = "funciones/"+entidad_nombre+"Eliminar.php";
      let cantidad_seleccionados = 0;
      $('.check:checked').each(
                  function() {
                      arreglo += ','+$(this).val();
                  }
      );
      arreglo = arreglo.substr(1,arreglo.length-1);
      parametros = {"id":arreglo};
      $.post(url, parametros, function (data) {
              $("#resultado_accion").html(`
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                          <span style="color: #000000;">
                          <i class="fa fa-info-circle" aria-hidden="true"></i>
                              <strong>Atención:</strong>&nbsp;`+data.mensaje+`
                          </span>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                          </button>       
                      </div>
              `);
              load(1);
      },"json");
  };
  
  //******************************************************* */
  // CONFIRMA LA ELIMINACION DE LA ENTIDAD DESDE EL MODAL   */
  //******************************************************* */
  $('#confirmarModal').on('shown.bs.modal', function (e) {
       let button = $(e.relatedTarget); // BUTTON QUE DISPARO EL MODAL
       let id=button.data("id");
      $("#inputEliminarId").val(id);
  })
  
  //************************************************ */
  // NOS PERMITE ELIMINAR UNA ENTIDAD ESPECIFICA     */
  //************************************************ */
  function entidadEliminarSeleccionado(entidad_id){
        let arreglo="";
        let parametros = "";
        let url = "funciones/"+entidad_nombre+"Eliminar.php";
        parametros = {"id":entidad_id};
                $.post(url, parametros, function (data) {
                        $("#resultado_accion").html(`
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                      <span style="color: #000000;">
                                      <i class="fa fa-info-circle" aria-hidden="true"></i>
                                          <strong>Atención:</strong>&nbsp;`+data.mensaje+`
                                      </span>
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                      </button>       
                                </div>
                        `);
                        load(1);
                },"json");
  }