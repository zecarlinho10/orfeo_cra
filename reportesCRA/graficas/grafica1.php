<?
include_once( '../../open-fc/ofc-library/open-flash-chart.php' );	
$g = new graph();
$g->title( "Gráfica de gestión Documental del Usuario $responsablet", '{font-size: 14px; color: #736AFF}' );
$resto = explode(",", $resto);
$resto2 = explode(",", $resto2);
$resto3 = explode(",", $resto3);


$g->set_data($resto);
$g->set_data($resto3);

$g->line( 2, '0x9933CC', 'Radicados Proyectados', 10 );
$g->line_dot( 3, 5, '0xCC3399', 'Radicados con cumplimiento', 10);
$g->set_y_legend( 'N° Radicados', 12, '#736AFF' );
$g->set_x_labels($resto2);
$g->set_y_max( 100 );
//$g->y_label_steps( 3 );

// display the data
echo $g->render();

?>
       
 