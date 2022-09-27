<?php
header("Content-type: text/html; charset=utf8");
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/');
//require_once 'seguridadNivel1.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'pagination.php';
//include_once 'ArrayHash.class.php';

//die(unserialize('a:1:{i:0;s:8:"empleado";}')[0]);

$rol = 'admin';
$rol_class = ($rol=='admin')?'':'disabledbutton';

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
$id =  isset($_POST['id'])?SanitizeVars::INT($_POST['id']):false;

$nombres = isset($_POST['nombres'])?SanitizeVars::APELLIDONOMBRES($_POST['nombres']):false;
$dni = isset($_POST['dni'])?SanitizeVars::NUMEROS($_POST['dni'],8,8):false;
$domicilio = isset($_POST['domicilio'])?SanitizeVars::STRING_NORMAL($_POST['domicilio']):false;
$telefono = isset($_POST['telefono'])?$_POST['telefono']:false;
$email = isset($_POST['email'])?SanitizeVars::EMAIL($_POST['email']):false;
$localidad = isset($_POST['localidad'])?SanitizeVars::APELLIDONOMBRES($_POST['localidad']):false;
$provincia = isset($_POST['provincia'])?SanitizeVars::APELLIDONOMBRES($_POST['provincia']):false;
$asistio = isset($_POST['asistio'])?$_POST['asistio']:false;
$pago = isset($_POST['pago'])?$_POST['pago']:false;

//var_dump($_POST);die;
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$andX = array();
$orX = array();
$join = "c.localidad_id=l.id";//$join = "1=1"; $join = "p.id=c.id";
$join2 = "l.provincia_id=p.id";//$join = "1=1"; $join = "p.id=c.id";
if ($join) {$andX[]=$join;}
if ($join2) {$andX[]=$join2;}

$sql = $sqlCantidadFilas = "";

if($action == 'listar'){
	$tables = " interesado c, localidad l, provincia p ";
	$campos = " c.id, c.apellido, c.nombres, c.dni, c.direccion, c.telefono, c.email, c.localidad_id, l.nombre as interesado_localidad, p.nombre as interesado_provincia, c.pago, c.asistio";
	//if ($id) $andX[] = 'c.id = "' . $id .'"';
    if ($nombres) $andX[] = '(c.apellido like "%' . $nombres . '%" or c.nombres like "%' . $nombres . '%")';  
	if ($dni) $andX[] = 'c.dni like "%' . $dni . '%"';
	if ($domicilio) $andX[] = 'c.direccion like "%' . $domicilio . '%"';
	if ($telefono) $andX[] = 'c.telefono like "%' . $telefono . '%"';
	if ($email) $andX[] = 'c.email like "%' . $email . '%"';
	if ($localidad) $andX[] = 'l.nombre like "%' . $localidad . '%"';
	if ($provincia) $andX[] = 'p.nombre like "%' . $provincia . '%"';
	if ($asistio) $andX[] = 'c.asistio = "' . $asistio . '"';
	if ($pago) $andX[] = 'c.pago = "' . $pago . '"';

    if (count($andX)>0) $where = ' WHERE (' . implode(" and ",$andX) . ') ';
	else $where = '';

	$where = $where . " ORDER BY c.id asc ";
    $sql = "";
	if ($busqueda) 
	{
		$campos_nuevos = "  x.id, x.apellido, x.nombres, x.dni, x.direccion, x.telefono, x.email, x.interesado_localidad, x.interesado_provincia, x.pago, x.asistio ";
		$subConsultaFiltros = "SELECT $campos FROM  $tables $where";
		//die($subConsultaFiltros);
		$sqlCantidadFilas =  "SELECT count(*) AS numrows FROM  ($subConsultaFiltros) x
							  WHERE (x.apellido like '%$busqueda%' or 
									 x.nombres like '%$busqueda%' or 
									 x.direccion like '%$busqueda%' or
									 x.email like '%$busqueda%')";
		$sqlFinal =  "SELECT $campos_nuevos FROM  ($subConsultaFiltros) x 
		              WHERE (x.apellido like '%$busqueda%' or 
							 x.nombres like '%$busqueda%' or 
							 x.direccion like '%$busqueda%' or
							 x.email like '%$busqueda%')";
		//die($sqlFinal);
	} else {
		$sqlCantidadFilas = "SELECT count(*) AS numrows FROM $tables $where "; 
		$sqlFinal = "SELECT $campos FROM  $tables $where";
		//die("555g".$sqlFinal);
	}


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
	$campo1 = "Id";$campo2 = "Nombres"; $campo4 = "Dni"; $campo5 = "Domicilio"; 
	$campo6 = "Telefono"; $campo7 = "Email"; $campo8 = "Localidad"; $campo9 = "Provincia"; $campo10 = "Asistio";  $campo11 = "Pago";
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
											<button class="btn btn-success '.$rol_class.'" onclick="entidadCrear()">Agregar</button>&nbsp;
											<button class="btn btn-danger '.$rol_class.'" onclick="entidadEliminarSeleccionados()">Borrar Seleccionados</button>&nbsp;
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
							<th class="text-center" width="5%"><small><b><input type="checkbox" id="seleccionar_todos"></b></small></th>
							<th width="10%" class="text-center text-primary" colspan=3><small><b>Acciones</b><small></th>
							<th class="text-center text-primary" width="8%"><small><b>'.$campo2.'</b></small></th>
							<th class="text-center text-primary" width="9%"><small><b>'.$campo4.'</b></small></th>
							<th class="text-center text-primary" width="9%"><small><b>'.$campo5.'</b></small></th>
							<th class="text-center text-primary" width="9%"><small><b>'.$campo6.'</b></small></th>
							<th class="text-center text-primary" width="9%"><small><b>'.$campo7.'</b></small></th>
							<th class="text-center text-primary" width="9%"><small><b>'.$campo8.'</b></small></th>
							<th class="text-center text-primary" width="9%"><small><b>'.$campo10.'</b></small></th>
							<th class="text-center text-primary" width="9%"><small><b>'.$campo11.'</b></small></th>
						</tr>
						<tr>
							<th class="text-right" colspan=4>
							        <button class="btn btn-primary" onclick="quitarFiltro()" title="Quitar Filtro"><img src="../assets/img/icons/filter_minus_icon16.png" width="22"></button>
									<button class="btn btn-primary" onclick="aplicarFiltro()" title="Aplicar Filtro"><img src="../assets/img/icons/filter_icon16.png" width="22"></button>
						    </th>
							<th class="text-center" width="15%"><small><b><input type="text" class="form-control" id="inputFiltro'.$campo2.'" value="'.$nombres.'"></b></small></th>
							<th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro'.$campo4.'" value="'.$dni.'"></b></small></th>
							<th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro'.$campo5.'" value="'.$domicilio.'"></b></small></th>
							<th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro'.$campo6.'" value="'.$telefono.'"></b></small></th>
							<th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro'.$campo7.'" value="'.$email.'"></b></small></th>
							<th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro'.$campo8.'" value="'.$localidad.'"></b></small></th>
							<th class="text-center" width="9%"><small><b><select class="form-control" id="inputFiltro'.$campo10.'"><option value="">Todos</option><option value="Si" '.(($asistio=="Si")?"selected":"").'>Si</option><option value="No" '.(($asistio=="No")?"selected":"").'>No</option></select></small></th>
							<th class="text-center" width="9%"><small><b><select class="form-control" id="inputFiltro'.$campo11.'"><option value="">Todos</option><option value="Si" '.(($pago=="Si")?"selected":"").'>Si</option><option value="No" '.(($asistio=="No")?"selected":"").'>No</option></select></small></th>
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
						$rowCampo2 = $row['apellido'].', '.$row['nombres'];
						$rowCampo4 = $row['dni'];
						$rowCampo5 = $row['direccion'];
						$rowCampo6 = $row['telefono'];
						$rowCampo7 = $row['email'];
						$rowCampo8 = $row['interesado_localidad'];
						$rowCampo9 = $row['interesado_provincia'];
						$rowCampo10 = $row['asistio'];
						$rowCampo11 = $row['pago'];

						echo '<tr>';
						echo '   <td align="center"><small><b><input type="checkbox" class="check" id="check_'.$rowIdCampo1.' " name="check_usu[]" value="'.$rowIdCampo1.'"></b></small></td>'.
							 '   <td align="center" colspan="3">
							 		<div class="btn-group pull-right" role="group">
										<button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Acciones
										</button>
										<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
											<a class="dropdown-item small" href="#"><i class="fa fa-address-card-o"></i>&nbsp;Ver</a>
											<a class="'.$rol_class.' dropdown-item small" href="#" onclick="entidadEditar('.$rowIdCampo1.')"><i class="fa fa-edit"></i>&nbsp;Editar</a>
											<a class="'.$rol_class.' dropdown-item small" href="#" data-toggle="modal" data-target="#confirmarModal" data-id="'.$rowIdCampo1.'"><i class="fa fa-trash"></i>&nbsp;Borrar</a>
										</div>
                             		</div>
							 </td>'.
							 '   <td align="left"><small>'.$rowCampo2.'<small></td>'.
							 '   <td align="left"><small>'.$rowCampo4.'</small></td>'.
							 '   <td align="left"><small>'.$rowCampo5.'</small></td>'.
							 '   <td align="left"><small>'.$rowCampo6.'</small></td>'.
							 '   <td align="left"><small>'.$rowCampo7.'</small></td>'.
							 '   <td align="left"><small>'.$rowCampo8.'-'.$rowCampo9.'</small></td>'.
							 '   <td align="left"><small>'.$rowCampo10.'</small></td>'.
							 '   <td align="left"><small>'.$rowCampo11.'</small></td>';
							 
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
