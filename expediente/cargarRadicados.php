<?php
session_start ();
$ruta_raiz = "../";
if (! $_SESSION ['dependencia'])
	header ( "Location: $ruta_raiz/cerrar_session.php" );

include_once ("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler ( "$ruta_raiz" );
include_once ("$ruta_raiz/include/tx/Expediente.php");
include_once "$ruta_raiz/tx/verLinkArchivo.php";
$verLinkArchivo = new verLinkArchivo ( $db );

// cho "<script> alert('Intento imprimir el mensaje de actualización de datos');</script>";
$expediente = new Expediente ( $db );
$numExpediente = $_POST ['expediente'];
$limit = 25;
$page = $_POST ['page'];
$inicio = ($page - 1) * $limit;
$permisosExpediente = $verLinkArchivo->permisosExpediente ( $_SESSION ["usua_doc"], $_SESSION ["codusuario"], $_SESSION ["dependencia"], $numExpediente );

if ($permisosExpediente ["permiso"]) {
	
	$radicados = $expediente->consultarRadicados ( $numExpediente, $inicio, $limit );
	foreach ( $radicados as $key2 => $value ) {
		?>
<li class="parent_li" style="" role="treeitem"><span>
                        <?
		$sExp = $expediente->listarOtrosExpedientes ( $value ["NUM_RADICADO"], $numExpediente );
		
		if ($sExp) {
			$SExps = "<table><TR><TD COLSPAN=5></small><b>Expedientes Adicionales :</b></small><TD></TR>";
			foreach ( $sExp as $valueExpedientes ) {
				$SExps .= "<tr><td><small>" . $valueExpedientes ["numero"] . "&nbsp; </small> </td><td> <small>" . $valueExpedientes ["param"] . "&nbsp; </small></td><td></tr>";
			}
			$SExps .= "</table>";
		}
		$permiso = $verLinkArchivo->havePermisosRadicado ( $value ["NUM_RADICADO"], $_SESSION ["usua_doc"], $_SESSION ["codusuario"], $_SESSION ["dependencia"], $_SESSION ["nivelus"], $permisosExpediente );
		if ($permiso ["ver"]) {
			$numRadicado = "<a onclick=\"funlinkArchivo('" . $value ["NUM_RADICADO"] . "','.');\" href=\"javascript:void(0)\">" . $value ["NUM_RADICADO"] . "</a>";
		} else {
			$numRadicado = $value ["NUM_RADICADO"];
		}
		$fechaRad = "<a href='verradicado.php?verrad=" . $value ["NUM_RADICADO"] . "&nomcarpeta=" . $nomcarpeta . "#tabs-d'>" . $value ["FECHA_RADICADO"] . "</a>";
		
		echo "<TABLE  WIDTH='100%'><TR><TD WIDTH=30><i class='icon-leaf'></i> </TD>
		                        <TD width=140 align=left>$numRadicado</TD>
								<TD width=140>$fechaRad</TD>
								<TD width=120>" . $value ["TIPO_DRADICADO"] . "</TD>
								<TD width=450>" . $value ["ASUNTO_RADICADO"] . "</TD>
								<TD width=350>$SExps</TD></TR>
                        </TABLE>";
		$carpetaDep = intval ( substr ( $value ["NUM_RADICADO"], 4, $digitosDependencia ) );
		$rutaAnexos = "" . substr ( $value ["NUM_RADICADO"], 0, 4 ) . "/$carpetaDep/docs/";
		$anexos = $value ["ANEXOS"];
		if ($anexos) {
			echo "<ul>";
			foreach ( $anexos as $valueAnexos ) {
				?>
                        <li>
						<?php
				if (! empty ( $valueAnexos ["RADI_SALIDA"] ))
					$numero = $valueAnexos ["RADI_SALIDA"];
				else
					$numero = $valueAnexos ["NUMERO"];
				
				if ($valueAnexos ["ANEX_PATH"]) {
					echo "<a onclick=\"funlinkArchivo('$numero','.');\" href=\"javascript:void(0)\" target='" . date ( "ymdhis" ) . "'>";
				}
				?>
                                - <?=$numero." -".$valueAnexos["DESCRIPCION"]?>
                                <? if($valueAnexos["ANEX_PATH"]) {?> </a> <? } ?>
                        </li>

<?php }?>
</ul>
<?php
		
}
		?>
</span></li>
<?php
	}
}
?>

