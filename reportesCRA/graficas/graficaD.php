<?
include_once( '../../open-fc/ofc-library/open-flash-chart.php' );	
$g = new graph();
$g->title( "Gráfica de Desempeño del Usuario $responsablet", '{font-size: 14px; color: #736AFF}' );
$resto4 = explode(",", $resto4);
$resto2 = explode(",", $resto2);

$g->set_data($resto4);

$g->line( 3, '0x0066FF', '% Desempeño', 10 );

$g->set_x_labels($resto2);
$g->set_y_max( 100 );
$g->set_y_legend( 'N° Radicados', 12, '#736AFF' );

// display the data
echo $g->render();

?>
       
 