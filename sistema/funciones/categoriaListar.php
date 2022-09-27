<?php
header("Content-type: text/html; charset=utf8");
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/');
//require_once 'seguridadNivel1.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'pagination.php';
//include_once 'ArrayHash.class.php';

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$busqueda = ( isset($_POST['busqueda_rapida']) && ($_POST['busqueda_rapida']) )?($_POST['busqueda_rapida']):false;
$id = ( isset($_POST['id']) && ($_POST['id']) )?($_POST['id']):false;
$descripcion = ( isset($_POST['descripcion']) && ($_POST['descripcion']) )?($_POST['descripcion']):false;
$padre = ( isset($_POST['padre']) && ($_POST['padre']) )?($_POST['padre']):false;

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$condiciones = "";
$andX = array();
$orX = array();
$join = "c.categoria_id = b.id";
$sql = $sqlCantidadFilas = "";

if($action == 'listar'){
	$tables = " categoria c, categoria b ";
	$campos = " c.id, c.descripcion, c.categoria_id, b.descripcion as descripcion_padre ";
	if ($id) $andX[] = 'c.id = "' . $id .'"';
    if ($descripcion) $andX[] = 'c.descripcion like "%' . $descripcion . '%"';
	if ($padre) $andX[] = 'b.descripcion like "%' . $padre . '%"';

    if (count($andX)>0) $condicion_and = ' where '.$join.' and (' . implode(" and ",$andX) . ') ';
	else $condicion_and = ' WHERE '.$join;

	$sWhere = $condicion_and . " ORDER BY c.id asc ";
    $sql = "";
	if ($busqueda) //$orX[] = 'a.habilitado = \'' . $alumno_habilitado.'\'';
	{
		$campos_nuevos = " x.id, x.descripcion, x.categoria_id, x.descripcion_padre as descripcion_padre ";
		$subconsulta = "SELECT $campos FROM  $tables $sWhere";
		//die($sql_tmp);
		$sqlCantidadFilas =  "SELECT count(*) AS numrows FROM  ($subconsulta) x
							  WHERE (x.descripcion like '%$busqueda%' or 
									 x.descripcion_padre like '%$busqueda%')";
		$sql =  "SELECT $campos_nuevos FROM  ($subconsulta) x 
		         WHERE (x.descripcion like '%$busqueda%' or 
					    x.descripcion_padre like '%$busqueda%')";
		//die($sql);

	} else {
		$sqlCantidadFilas = "SELECT count(*) AS numrows FROM $tables $sWhere "; 
		$sql = "SELECT $campos FROM  $tables $sWhere";
		//die($sql);
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
	$query = mysqli_query($conex,$sql);

	//*********************************************************** */
	//****************  PONER LOS NOMBRES DE LOS CAMPOS ********* */
	//*********************************************************** */
	$campo1 = "ID";$campo2 = "Descripcion"; $campo3 = "Padre";
	//*********************************************************** */
	//*********************************************************** */
	
	echo '<div class="table-responsive" ">
				<table class="table table-striped table-bordered table-hover" id="tabla_calendario">
					<thead>
						<tr>
							<th class="text-left" colspan="7">
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
						</tr>
						<tr>
							<th class="text-right" colspan=4>
							        <button class="btn btn-secondary" onclick="quitarFiltro()" title="Quitar Filtro"><img src="../assets/img/icons/filter_minus_icon16.png" width="22"></button>
									<button class="btn btn-secondary" onclick="aplicarFiltro()" title="Aplicar Filtro"><img src="../assets/img/icons/filter_icon16.png" width="22"></button>
						    </th>
							<th class="text-center" width="6%"><small><b><input type="text" class="form-control" id="inputFiltroId" value="'.$id.'"></b></small></th>
							<th class="text-center" width="35%"><small><b><input type="text" class="form-control" id="inputFiltroDescripcion" value="'.$descripcion.'"></b></small></th>
							<th class="text-center" width="35%"><small><b><input type="text" class="form-control" id="inputFiltroPadre" value="'.$padre.'"></b></small></th>
							
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
						$rowId = $row['id'];
						$rowCampo1 = $row['descripcion'];
						$rowCampo2 = $row['descripcion_padre'];

						//$accion_editar = '<a href="#" onclick="calendarioEditar('.$rowId.')" ><img src="../assets/img/icons/edit_icon.png" width="23"></a>';
						//$accion_eliminar = '<a href="#" onclick="calendarioEliminar('.$rowId.')" ><img src="../assets/img/icons/delete_icon.png" width="20"></a>';
						
						/*$hash = ArrayHash::encode(array($secreto=>$rowId));*/
						echo '<tr>';
						echo '   <td align="center"><small><b><input type="checkbox" class="check" id="check_'.$rowId.' " name="check_usu[]" value="'.$rowId.'"></b></small></td>'.
							 '   <td align="center"><small><a href="#" class="disabledbutton" title="Ver"><img src="../assets/img/icons/file_view_icon.png" width="17"></a></small></td>'.
							 '   <td align="center"><small><a href="#" title="Modificar"><img src="../assets/img/icons/file_edit_icon.png" onclick="entidadEditar('.$rowId.')" width="21" height="21"></a></small></td>'.
							 '   <td align="center"><small><a href="#" class="btn-eliminar" title="Eliminar" data-toggle="modal" data-target="#confirmarModal" data-id="'.$rowId.'" ><img src="../assets/img/icons/file_delete_icon.png" width="16"></a></small></td>'.	
							 '   <td align="center"><small>'.$rowId.'</small></td>'.
							 '   <td align="left"><small>'.ucfirst(mb_strtolower($rowCampo1, 'UTF-8')).'<small></td>'.
							 '   <td align="left"><small>'.$rowCampo2.'</small></td>';
						echo '</tr>';
					$finales++;
			};
			echo "</tbody><tfoot><tr><td colspan='7'>";
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
