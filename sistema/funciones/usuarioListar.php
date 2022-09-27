<?php
header("Content-type: text/html; charset=utf8");
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/');
//require_once 'seguridadNivel1.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'pagination.php';
//include_once 'ArrayHash.class.php';

//die(unserialize('a:1:{i:0;s:8:"empleado";}')[0]);

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
$busqueda = isset($_POST['busqueda_rapida'])?SanitizeVars::STRING_NORMAL($_POST['busqueda_rapida']):false;
$id =  isset($_POST['id'])?SanitizeVars::INT($_POST['id']):false;
$usuario = isset($_POST['usuario'])?SanitizeVars::USUARIO($_POST['usuario']):false;
$rol = isset($_POST['rol'])?SanitizeVars::STRING_NORMAL($_POST['rol']):false;
$email = isset($_POST['email'])?SanitizeVars::STRING_NORMAL($_POST['email']):false;

//die($action.'-'.$busqueda.'-'.$id.'-'.$usuario.'-'.$rol.'-'.$email);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$andX = array();
$orX = array();
$join = "u.rol_id=ur.id";//$join = "1=1"; $join = "p.id=c.id";
if ($join) {$andX[]=$join;}

$sql = $sqlCantidadFilas = "";

if($action == 'listar'){
	$tables = " usuario u, usuario_rol ur ";
	$campos = " u.id, u.usuario, u.email, u.rol_id as rol, ur.tipo as rol_tipo, ur.descripcion as rol_descripcion ";
	if ($id) $andX[] = 'u.id = "' . $id .'"';
    if ($usuario) $andX[] = 'u.usuario like "%' . $usuario . '%"';
	if ($rol) $andX[] = 'ur.tipo like "%' . $rol . '%"';
	if ($email) $andX[] = 'u.email like "%' . $email . '%"';

    if (count($andX)>0) $where = ' WHERE (' . implode(" and ",$andX) . ') ';
	else $where = '';

	$where = $where . " ORDER BY u.id asc ";
    $sql = "";
	if ($busqueda) 
	{
		$campos_nuevos = "  x.id, x.usuario, x.email, x.rol, x.rol_tipo, x.rol_descripcion ";
		$subConsultaFiltros = "SELECT $campos FROM  $tables $where";
		//die($subConsultaFiltros);
		$sqlCantidadFilas =  "SELECT count(*) AS numrows FROM  ($subConsultaFiltros) x
							  WHERE (x.usuario like '%$busqueda%' or 
									 x.email like '%$busqueda%' or 
									 x.rol_tipo like '%$busqueda%')";
		$sqlFinal =  "SELECT $campos_nuevos FROM  ($subConsultaFiltros) x 
		         WHERE (x.usuario like '%$busqueda%' or 
									 x.email like '%$busqueda%' or 
									 x.rol_tipo like '%$busqueda%')";
		//die($sqlFinal);
	} else {
		$sqlCantidadFilas = "SELECT count(*) AS numrows FROM $tables $where "; 
		$sqlFinal = "SELECT $campos FROM  $tables $where";
		//die($sqlFinal);
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
	$sql .=  " LIMIT $offset,$per_page ";
	//die($sql);
	$query = mysqli_query($conex,$sqlFinal);

	//*********************************************************** */
	//****************  PONER LOS NOMBRES DE LOS CAMPOS ********* */
	//*********************************************************** */
	$campo1 = "Id";$campo2 = "Usuario"; $campo3 = "Rol"; $campo4 = "Email";
	//*********************************************************** */
	//*********************************************************** */
	
	echo '<div class="table-responsive" ">
				<table class="table table-striped table-bordered table-hover" id="tabla_calendario">
					<thead>
						<tr>
							<th class="text-left" colspan="8">
							   <table width="100%">
							 	   <tr>
										<th class="text-left" colspan="5">
											<button class="btn btn-success" onclick="entidadCrear()">Agregar</button>&nbsp;
											<button class="btn btn-danger" onclick="entidadEliminarSeleccionados()">Borrar Seleccionados</button>&nbsp;
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
							<th width="10%" class="text-center text-primary"><small><b>'.$campo1.'</b><small></th>
							<th class="text-center text-primary" width="8%"><small><b>'.$campo2.'</b></small></th>
							<th class="text-center text-primary" width="9%"><small><b>'.$campo3.'</b></small></th>
							<th class="text-center text-primary" width="9%"><small><b>'.$campo4.'</b></small></th>
						</tr>
						<tr>
							<th class="text-right" colspan=4>
							        <button class="btn btn-secondary" onclick="quitarFiltro()" title="Quitar Filtro"><img src="../assets/img/icons/filter_minus_icon16.png" width="22"></button>
									<button class="btn btn-secondary" onclick="aplicarFiltro()" title="Aplicar Filtro"><img src="../assets/img/icons/filter_icon16.png" width="22"></button>
						    </th>
							<th class="text-center" width="6%"><small><b><input type="text" class="form-control" id="inputFiltro'.$campo1.'" value="'.($id==0?'':$id).'"></b></small></th>
							<th class="text-center" width="15%"><small><b><input type="text" class="form-control" id="inputFiltro'.$campo2.'" value="'.$usuario.'"></b></small></th>
							<th class="text-center" width="25%"><small><b><input type="text" class="form-control" id="inputFiltro'.$campo3.'" value="'.$rol.'"></b></small></th>
							<th class="text-center" width="35%"><small><b><input type="text" class="form-control" id="inputFiltro'.$campo4.'" value="'.$email.'"></b></small></th>
							
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
						$rowCampo2 = $row['usuario'];
						$rowCampo3 = $row['rol_descripcion'];
						$rowCampo4 = $row['email'];

						//$accion_editar = '<a href="#" onclick="calendarioEditar('.$rowId.')" ><img src="../assets/img/icons/edit_icon.png" width="23"></a>';
						//$accion_eliminar = '<a href="#" onclick="calendarioEliminar('.$rowId.')" ><img src="../assets/img/icons/delete_icon.png" width="20"></a>';
						
						/*$hash = ArrayHash::encode(array($secreto=>$rowId));*/
						echo '<tr>';
						echo '   <td align="center"><small><b><input type="checkbox" class="check" id="check_'.$rowIdCampo1.' " name="check_usu[]" value="'.$rowIdCampo1.'"></b></small></td>'.
							 '   <td align="center"><small><a href="#" class="disabledbutton" title="Ver"><img src="../assets/img/icons/file_view_icon.png" width="17"></a></small></td>'.
							 '   <td align="center"><small><a href="#" title="Modificar"><img src="../assets/img/icons/file_edit_icon.png" onclick="entidadEditar('.$rowIdCampo1.')" width="21" height="21"></a></small></td>'.
							 '   <td align="center"><small><a href="#" class="btn-eliminar" title="Eliminar" data-toggle="modal" data-target="#confirmarModal" data-id="'.$rowIdCampo1.'" ><img src="../assets/img/icons/file_delete_icon.png" width="16"></a></small></td>'.	
							 '   <td align="center"><small>'.$rowIdCampo1.'</small></td>'.
							 '   <td align="left"><small>'.$rowCampo2.'<small></td>'.
							 '   <td align="left"><small>'.$rowCampo3.'</small></td>'.
							 '   <td align="left"><small>'.$rowCampo4.'</small></td>';
						echo '</tr>';
					$finales++;
			};
			echo "</tbody><tfoot><tr><td colspan='8'>";
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
	echo '<tr><td colspan="12">
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
