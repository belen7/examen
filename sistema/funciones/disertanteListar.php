<?php
header("Content-type: text/html; charset=utf8");
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/');
require_once 'controlAcceso.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'pagination.php';
//include_once 'ArrayHash.class.php';

//die(unserialize('a:1:{i:0;s:8:"empleado";}')[0]);

$rol_usuario = '';
$rol_admin = ($_SESSION['user_rol']=='admin' || $_SESSION['user_rol']=='SYSTEM')?'':'disabledbutton';


/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

/*
$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$alumno_busqueda = ( isset($_POST['alumno_busqueda']) && ($_POST['alumno_busqueda']) )?($_POST['alumno_busqueda']):false;
$alumno_id = ( isset($_POST['alumno_id']) && ($_POST['alumno_id']) )?($_POST['alumno_id']):false;
$alumno_dni = ( isset($_POST['alumno_dni']) && ($_POST['alumno_dni']) )?($_POST['alumno_dni']):false;
$alumno_nombre = ( isset($_POST['alumno_nombre']) && ($_POST['alumno_nombre']) )?($_POST['alumno_nombre']):false;
$alumno_email = ( isset($_POST['alumno_email']) && ($_POST['alumno_email']) )?($_POST['alumno_email']):false;
$alumno_telefono = ( isset($_POST['alumno_telefono']) && ($_POST['alumno_telefono']) )?($_POST['alumno_telefono']):false;
$alumno_ingreso = ( isset($_POST['alumno_ingreso']) && ($_POST['alumno_ingreso']) )?($_POST['alumno_ingreso']):false;
$alumno_titulo = ( isset($_POST['alumno_titulo']) && ($_POST['alumno_titulo']) )?($_POST['alumno_titulo']):false;
$alumno_habilitado = ( isset($_POST['alumno_habilitado']) && ($_POST['alumno_habilitado']) )?($_POST['alumno_habilitado']):false;
*/

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$busqueda = isset($_POST['busqueda_rapida'])?$_POST['busqueda_rapida']:false;

//var_dump($_POST);die;
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$where = "";


$sql = $sqlCantidadFilas = "";

if($action == 'listar'){
	$tables = " disertante d";
	$campos = " d.id, d.apellido, d.nombre, d.email, d.biografia";
	//if ($id) $andX[] = 'c.id = "' . $id .'"';
    
	if ($busqueda) $where = 'where d.biografia like "%' . $busqueda . '%"';

    //die($where);

    $sqlCantidadFilas = "SELECT count(*) AS numrows FROM $tables $where "; 
	$sqlFinal = "SELECT $campos FROM  $tables $where";

	$where = $where . " ORDER BY d.id asc ";
    //die($sqlFinal);


	//PAGINATION VARIABLES
	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:1; //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
   
	//die($sqlCantidadFilas);
	$count_query = mysqli_query($conex,$sqlCantidadFilas);
	if ($row = mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	$total_pages = ceil($numrows/$per_page);
	//main query to fetch the data
	$sqlFinal .=  " LIMIT $offset,$per_page ";
	//die($sql);
	$query = mysqli_query($conex,$sqlFinal);

	//*********************************************************** */
	//****************  PONER LOS NOMBRES DE LOS CAMPOS ********* */
	//*********************************************************** */
	$campo1 = "Id";$campo2 = "Apellido"; $campo4 = "Nombres"; $campo5 = "Email"; 
	$campo6 = "Biografia"; 
	//*********************************************************** */
	//*********************************************************** */
	
	echo '<div class="table-responsive" ">
				<table class="table table-striped table-bordered table-hover" id="tabla_calendario">
					<thead>
						<tr>
							<th class="text-left" colspan="13">
							   <table width="100%">
							 	   <tr>
										<th class="text-left" colspan="5">
											<button class="btn btn-success '.$rol_admin.'" onclick="entidadCrear()">Agregar</button>&nbsp;
											<button class="btn btn-danger '.$rol_admin.'" onclick="entidadEliminarSeleccionados()">Borrar Seleccionados</button>&nbsp;
										</th>
										<th class="text-right" colspan="7">
												<div class="col-7">
												<div class="input-group">
													<input id="inputBusquedaRapida" placeholder="Busqueda Rapida" type="text" class="form-control" value="'.$busqueda.'"> 
													<div class="input-group-append">
													<div class="input-group-text">
														<a href="#" onclick="aplicarBusquedaRapida()"><i class="fa fa-search"></i></a>
													</div>
													</div>
												</div>
												</div>
									    </th>
									</tr>  
							   </table>
							</th>
        				</tr>
						<tr>
							<th class="text-center" width="5%"><small><b><input type="checkbox" class="'.$rol_admin.'" id="seleccionar_todos"></b></small></th>
							<th width="10%" class="text-center text-primary" colspan=3><small><b>Acciones</b><small></th>
							<th class="text-center text-primary" width="8%"><small><b>'.$campo2.'</b></small></th>
							<th class="text-center text-primary" width="9%"><small><b>'.$campo4.'</b></small></th>
							<th class="text-center text-primary" width="9%"><small><b>'.$campo5.'</b></small></th>
							<th class="text-center text-primary" width="9%"><small><b>'.$campo6.'</b></small></th>
						</tr>
						
					</thead>';
			
	if ($numrows>0){
			$finales = $c = 0;
			$pagina = (($page-1)*$per_page);

			//$tipo_organismo = substr($_SESSION['organismo_codigo'],0,1);
			echo '<tbody>';
			while ($row=mysqli_fetch_assoc($query)) {
						$c++;
						$indice = $pagina + $c;
						$rowIdCampo1 = $row['id'];
						$rowCampo2 = $row['apellido'];
						$rowCampo4 = $row['nombre'];
						$rowCampo5 = $row['email'];
						$rowCampo6 = $row['biografia'];
					

						echo '<tr>';
						echo '   <td align="center"><small><b><input type="checkbox" class="'.$rol_admin.' check" id="check_'.$rowIdCampo1.'" name="check_usu[]" value="'.$rowIdCampo1.'"></b></small></td>'.
							 '   <td align="center" colspan="3">
							 		<div class="btn-group pull-right" role="group">
										<button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Acciones
										</button>
										<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
											<a class="'.$rol_usuario.'dropdown-item small" href="#" onclick="entidadVer('.$rowIdCampo1.')"><i class="fa fa-address-card-o"></i>&nbsp;Ver</a>
											<a class="'.$rol_admin.' dropdown-item small" href="#" data-toggle="modal" data-target="#confirmarModal" data-id="'.$rowIdCampo1.'"><i class="fa fa-trash"></i>&nbsp;Borrar</a>
										</div>
                             		</div>
							 </td>'.
							 '   <td align="left"><small>'.$rowCampo2.'<small></td>'.
							 '   <td align="left"><small>'.$rowCampo4.'</small></td>'.
							 '   <td align="left"><small>'.$rowCampo5.'</small></td>'.
							 '   <td align="left"><small>'.$rowCampo6.'</small></td>';
							 
						echo '</tr>';
					$finales++;
			};
			echo "</tbody><tfoot><tr><td colspan='13'>";
			$inicios=$offset+1;
			$finales+=$inicios-1;
			echo "<br>";
			echo "Mostrando <strong>$inicios</strong> al <strong>$finales</strong> de <strong>$numrows</strong> registros";
			echo "<br><p>";
			echo paginate($page, $total_pages, $adjacents);
			echo "</td></tr>";
			echo '</tfoot>';
			echo '</table>';
} else {
	echo '<tbody>';
	echo '<tr><td colspan="13">
	              <div class="alert alert-exclamation" role="alert">
				        <span style="color: #000000;">
				            <i class="fa fa-info-circle" aria-hidden="true"></i>
						    &nbsp;<strong>Atención:</strong> No existen Resultados.
					    </span>
			       </div>
			  </td></tr>';
	echo '</tbody>';
	echo '</table>';
};
};

?>