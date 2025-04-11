<?php

class Calendar
{

    private $pathRutas;

    public $debug = false;

    private $festivos = array();

    private $db; 

    private $laborales="1-5";

    private $diasLaborales = array(1, 2, 3, 4, 5);

    public function __construct($db)
    {
        $this->pathRutas = dirname(__FILE__);
        $this->db=$db;
    }

    public function calcularDias($fechaInicial, $fechaFinal)
    {
        $signo = - 1;
        $fetvidades = array();
        $tInicial = strtotime($fechaInicial);
        $tFinal = strtotime($fechaFinal);
        if ($tFinal < $tInicial) {
            $signo = 1;
            $tTemp = $tFinal;
            $tFinal = $tInicial;
            $tInicial = $tTemp;
        }
        
        $agnoInicial = date("Y", $tInicial);
        $agnoFinal = date("Y", $tFinal);
        //$festividades = $this->festivosPeriodo($agnoFinal, $agnoInicial);
        $festividades = $this->loadFestivos($agnoInicial);
        $semanas = $this->calculaFds($tInicial, $tFinal);
        $festivosAplicables = $this->numFestivos($tInicial, $tFinal);
        $total = $this->calculoDias($tInicial, $tFinal) - ($festivosAplicables) - ($semanas * 2);
        return $total * $signo;
    }

    //CARLOS RICAURTE
    /***************************************************************************************/

    protected function calculaFds($tInicial, $tFinal)
    {
        $agnoInicial = date("Y", $tInicial);
        $agnoFinal = date("Y", $tFinal);
        
        $semanasInter = 0;
        $semanaInicio = date('W', $tInicial);
        $semaFinal = date('W', $tFinal);
        
        if ($agnoInicial != $agnoFinal) {
            if (($agnoInicial - $agnoFinal) > 1) {
                for ($agn = $agnoInicial; $agn < $agnoFinal; $agn ++) {
                    $semanasInter += $this->semanasAgno($agn);
                }
            }
            $semanaInicio = $this->semanasAgno($agnoFinal) - $semanaInicio;
            $semanas = $semanaInicio + $semaFinal;
        } else {
            $semanas = $semaFinal - $semanaInicio;
        }
        $semanas = $semanas + $semanasInter;
        return $semanas;
    }

    protected function calculoDias($tInicial, $tFinal)
    {
        return ceil(($tFinal - $tInicial) / (24 * 60 * 60));
    }

    protected function semanasAgno($year)
    {
        $date = new DateTime();
        $date->setISODate($year, 53);
        if ($date->format("W") == "53")
            return 53;
        else
            return 52;
    }

    public function festivosPeriodo($agnoInicial, $agnoFinal)
    {
        $festivosPeriodo = array();
        for ($i = $agnoInicial; $i <= $agnoFinal; $i ++) {
            $festivosPeriodo = array_merge($this->generarFestivos($i), $festivosPeriodo);
        }
        $this->festivos = array_merge($this->festivos, $festivosPeriodo);
        return $festivosPeriodo;
    }

    public function calculaInicioPeriodo($tiempo)
    {
        $conteoHabil = $tiempo +(24 * 60 * 60);
        $conteoHabil = $this->siguienteHabil($conteoHabil);
        return $conteoHabil;
    }

    private function calculaConNonHab($fecha, $dias)
    {
        $inicioPeriodo = $fecha;
        $calculo = $inicioPeriodo + ($dias * (24 * 60 * 60));
        $calculo = $this->siguienteNoFds($calculo);
        $finsemana = $this->noHabiles($inicioPeriodo, $calculo);
        $diasAplicados = $dias - ($finsemana * 2);
        if (($diasAplicados < $dias)) {
            return $this->calculaConNonHab($calculo, $dias - $diasAplicados);
        } else {
            return $calculo;
        }
    }
    protected function siguienteNoFds($tiempo)
    {
        $conteoHabil = $tiempo;
        switch (date('w', $conteoHabil)) {
            case 0:
                $conteoHabil = $conteoHabil + ((24 * 60 * 60));
                break;
            case 6:
                $conteoHabil = $conteoHabil + (2 * (24 * 60 * 60));
                break;
        }
        return $conteoHabil;
    }

    protected function numFestivos($fechaInicial, $fechaFinal)
    {
        $annoInicio = date('Y', $fechaInicial);
        $annoFinal = date('Y', $fechaFinal);
        if ($annoInicio != $annoFinal) {
            $festivoInicial = $this->generarFestivos($annoInicio);
            $festivos = $this->generarFestivos($annoFinal);
            $festivos = array_merge($festivoInicial, $festivos);
        } else {
            $festivos = $this->generarFestivos($annoInicio);
        }
        $this->festivos = array_merge($this->festivos, $festivos);
        $festivocontado = $this->contarFestivosLaborales($festivos, $fechaInicial, $fechaFinal);
        return $festivocontado;
    }

    //CARLOS RICAURTE
    /***************************************************************************************/
    public function loadFestivos($fechaInicial){

        $sql="SELECT NOH_FECHA FROM SGD_NOH_NOHABILES WHERE NOH_FECHA >= '$fechaInicial'";
                //WHERE NOH_FECHA >= '01/01/2020'";
        $rs = $this->db->query($sql);
        $x=0;
        while(!$rs->EOF){
            $NOH_FECHA = $rs->fields['NOH_FECHA'];
            $this->festivos[$x]=$NOH_FECHA;
            $x++;
            $rs->MoveNext();
        }
        
        return $this->festivos;
    }

    public function calcular($fechaInicial, $dias)
    {
        /*
        CARLOS RICAURTE 18/06/2018
        NUEVO ALGORITMO
        */
        $this->festivos=$this->loadFestivos($fechaInicial);
        $fechaInicial = new DateTime($fechaInicial);
        while(1){
            if (!in_array($fechaInicial->format('N'), $this->diasLaborales)) $fechaInicial->modify('+1 day');
            else if (in_array($fechaInicial->format('Y-m-d'), $this->festivos)) $fechaInicial->modify('+1 day');
            else if (in_array($fechaInicial->format('*-m-d'), $this->festivos)) $fechaInicial->modify('+1 day');
            else break;
        }
        while ($dias) {
            $fechaInicial->modify('+1 day');

            if (!in_array($fechaInicial->format('N'), $this->diasLaborales)) continue;
            if (in_array($fechaInicial->format('Y-m-d'), $this->festivos)) continue;
            if (in_array($fechaInicial->format('*-m-d'), $this->festivos)) continue;

            $dias--;
            $ultimoDia=$fechaInicial->format('Y-m-d');
        }
        return strtotime($ultimoDia);
    }

    /*
        CARLOS RICAURTE 4/04/2021
    */
    public function restaFechasHabiles($fechaInicial, $fechaFinal)
    {        
        $this->festivos=$this->loadFestivos($fechaInicial);
        
        $fechaInicial = new DateTime($fechaInicial);
        $fechaFinal = new DateTime($fechaFinal);
        $cdias=0;
        $cfestivos=0;
        
        while ($fechaInicial<$fechaFinal) {
            $fechaInicial->modify('+1 day');
            if (in_array($fechaInicial->format('N'), $this->diasLaborales)) $cdias++;
            
            if (in_array($fechaInicial->format('Y-m-d'), $this->festivos)) {
                $cdias--;
                $cfestivos++;
            }
        }
        return $cdias;
    }

    public function setDebug($habiltar)
    {
        $this->debug = $habiltar;
    }

    protected function esHabil($tiempo)
    {
        require $this->pathRutas . '/festivos.php';
        $semana = $laborales;
        $minLab = $semana[0];
        $maxLab = $semana[2];
        $dia = date('w', $tiempo);
        return ($dia >= $minLab && $dia <= $maxLab);
    }

    protected function verificaFestivos($tiempo)
    {
        $tiempocalculo = $tiempo;
        if (count($this->festivos) == 0) {
            if (date("m-d") == "12-31") {
                $tiempocalculo += (24 * 60 * 60);
            }
            $this->festivos = $this->generarFestivos(date("Y", $tiempocalculo));
        }
    }

    protected function siguienteHabil($tiempo)
    {
        $conteoHabil = $tiempo;
        
        if ($this->esHabil($conteoHabil)) {
            $this->verificaFestivos($conteoHabil);
            if ($this->esFestivo($conteoHabil)) {
                $conteoHabil += ((24 * 60 * 60));
            }
        }
        switch (date('w', $conteoHabil)) {
            case 0:
                $conteoHabil = $conteoHabil + ((24 * 60 * 60));
                break;
            case 6:
                $conteoHabil = $conteoHabil + (2 * (24 * 60 * 60));
                break;
        }
        return $conteoHabil;
    }

    protected function esFestivo($tiemstamp)
    {
        return in_array($tiemstamp, $this->festivos);
    }

    protected function noHabiles($fechaInicio, $fechaFinal)
    {
        $semanaInicio = date('W', $fechaInicio);
        $semaFinal = date('W', $fechaFinal);
        if ($semaFinal < $semanaInicio) {
            $semanaInicio = $semanaInicio - $this->semanasAgno(date('Y', $fechaInicio));
        }
        $operacion = $semanaInicio - $semaFinal;
        return $operacion < 0 ? (- 1) * $operacion : $operacion;
    }

    protected function pascua($anno)
    {
        $mes = 0;
        $dia = 0;
        $M = 0;
        $N = 0;
        if ($anno >= 1583 && $anno <= 1699) {
            $M = 22;
            $N = 2;
        } else 
            if ($anno >= 1700 && $anno <= 1799) {
                $M = 23;
                $N = 3;
            } else 
                if ($anno >= 1800 && $anno <= 1899) {
                    $M = 23;
                    $N = 4;
                } else 
                    if ($anno >= 1900 && $anno <= 2099) {
                        $M = 24;
                        $N = 5;
                    } else 
                        if ($anno >= 2100 && $anno <= 2199) {
                            $M = 24;
                            $N = 6;
                        } else 
                            if ($anno >= 2200 && $anno <= 2299) {
                                $M = 25;
                                $N = 0;
                            }
        
        $a = $anno % 19;
        $b = $anno % 4;
        $c = $anno % 7;
        $d = ((19 * $a) + $M) % 30;
        $e = ((2 * $b) + (4 * $c) + (6 * $d) + $N) % 7;
        
        if (($d + $e) < 10) {
            $dia = ($d + $e + 22);
            $mes = 3;
        } else {
            $dia = $d + $e - 9;
            $mes = 4;
        }
        
        // Caso especial
        if (($dia == 26) && ($mes == 4))
            $dia = 19;
            
            // Caso especial
        if (($dia == 25) && ($mes == 4) && ($d == 28) && ($e == 6) && ($a > 10))
            $dia = 18;
        $resultado = strtotime($anno . "-" . $mes . "-" . $dia);
        return $resultado;
    }

    protected function loadLunes($anno)
    {
        require $this->pathRutas . '/festivos.php';
        $festivosLunes = array();
        $festivos = $lunes;
        if (count($festivos) > 0) {
            foreach ($festivos as $value) {
                $tiempo = strtotime($anno . "-" . $value);
                $dia = date('w', $tiempo);
                $dias = (9 - $dia) % 6;
                if ($dia == 1) {
                    $festivosLunes[] = ($tiempo);
                } else {
                    $dias = (8 - $dia) % 7;
                    $dias = $dias * (24 * 60 * 60);
                    $festivosLunes[] = ($tiempo + $dias);
                }
            }
        }
        return $festivosLunes;
    }

    public function contarFestivosLaborales($festivos, $tiempoInicial, $tiempoFinal)
    {
        $contar = 0;
        $festiv = array();
        if (count($festivos) > 0) {
            foreach ($festivos as $valor) {
                $tiempo = $valor;
                if ($tiempo >= $tiempoInicial && $tiempo <= $tiempoFinal && $this->esHabil($tiempo)) {
                    $festiv[] = $tiempo;
                    $contar ++;
                }
            }
        }
        return $contar;
    }

    protected function loadFijos($anno)
    {
        require $this->pathRutas . '/festivos.php';
        $festivosFijos = array();
        $festivos = $fijos;
        if (count($festivos) > 0) {
            foreach ($festivos as $value) {
                $tiempo = strtotime($anno . "-" . $value);
                if ($this->esHabil($tiempo)) {
                    $festivosFijos[] = ($tiempo);
                }
            }
        }
        return $festivosFijos;
    }

    protected function loadPascua($anno)
    {
        require $this->pathRutas . '/festivos.php';
        $festivosPascua = array();
        $festivos = $pascua;
        $pascuaTiempo = $this->pascua($anno);
        if (count($festivos) > 0) {
            foreach ($festivos as $value) {
                $tiempo = $pascuaTiempo + ($value * (24 * 60 * 60));
                $festivosPascua[] = ($tiempo);
            }
        }
        return $festivosPascua;
    }

    public function setPathRutas($path)
    {
        $this->pathRutas = $path;
    }

    public function getPathRutas()
    {
        return $this->pathRutas;
    }

    public function generarFestivos($anno)
    {
        $festivos = array();
        $festivos = array_merge($festivos, $this->loadFijos($anno));
        $festivos = array_merge($this->loadLunes($anno), $festivos);
        $festivos = array_merge($this->loadPascua($anno), $festivos);
        sort($festivos);
        return $festivos;
    }
    // traducefecha.php
    // 14 de Octubre de 2003
    // Traduce una fecha en formato mm/dd/yyy a formato texto en castellano
    // Desde la pagina llamaremos a la funcion
    // include("traducefecha.php");
    // echo traducefecha("11/15/2003"); Visualiza la fecha
    // Donde la fecha ponemos la variable que queremos traducir en formato mm/dd/yyyy
    //
    function traducefecha($fecha)
    {
        if (strlen(trim($fecha)) == 0)
            return ("<NO ESPECIFICADA>");
        
        $fecha = strtotime($fecha); // convierte la fecha de formato mm/dd/yyyy a marca de tiempo
        $diasemana = date("w", $fecha); // optiene el n�mero del dia de la semana. El 0 es domingo
        switch ($diasemana) {
            case "0":
                $diasemana = "Domingo";
                break;
            case "1":
                $diasemana = "Lunes";
                break;
            case "2":
                $diasemana = "Martes";
                break;
            case "3":
                $diasemana = "Miercoles";
                break;
            case "4":
                $diasemana = "Jueves";
                break;
            case "5":
                $diasemana = "Viernes";
                break;
            case "6":
                $diasemana = "Sabado";
                break;
        }
        $dia = date("d", $fecha); // d�a del mes en n�mero
        $mes = date("m", $fecha); // n�mero del mes de 01 a 12
        switch ($mes) {
            case "01":
                $mes = "Enero";
                break;
            case "02":
                $mes = "Febrero";
                break;
            case "03":
                $mes = "Marzo";
                break;
            case "04":
                $mes = "Abril";
                break;
            case "05":
                $mes = "Mayo";
                break;
            case "06":
                $mes = "Junio";
                break;
            case "07":
                $mes = "Julio";
                break;
            case "08":
                $mes = "Agosto";
                break;
            case "09":
                $mes = "Septiembre";
                break;
            case "10":
                $mes = "Octubre";
                break;
            case "11":
                $mes = "Noviembre";
                break;
            case "12":
                $mes = "Diciembre";
                break;
        }
        $ano = date("Y", $fecha); // optenemos el a�o en formato 4 digitos
        $fecha = $diasemana . " " . $dia . " de " . $mes . " de " . $ano; // unimos el resultado en una unica cadena
        return $fecha; // enviamos la fecha al programa
    }

    function traducefecha_sinDia($fecha)
    {
        if (strlen(trim($fecha)) == 0)
            return ("<NO ESPECIFICADA>");
        
        $fecha = strtotime($fecha); // convierte la fecha de formato mm/dd/yyyy a marca de tiempo
        
        $dia = date("d", $fecha); // día del mes en número
        $mes = date("m", $fecha); // número del mes de 01 a 12
        switch ($mes) {
            case "01":
                $mes = "Enero";
                break;
            case "02":
                $mes = "Febrero";
                break;
            case "03":
                $mes = "Marzo";
                break;
            case "04":
                $mes = "Abril";
                break;
            case "05":
                $mes = "Mayo";
                break;
            case "06":
                $mes = "Junio";
                break;
            case "07":
                $mes = "Julio";
                break;
            case "08":
                $mes = "Agosto";
                break;
            case "09":
                $mes = "Septiembre";
                break;
            case "10":
                $mes = "Octubre";
                break;
            case "11":
                $mes = "Noviembre";
                break;
            case "12":
                $mes = "Diciembre";
                break;
        }
        
        switch ($dia) {
            case "1":
                $dia = "un";
                break;
            case "2":
                $dia = "dos";
                break;
            case "3":
                $dia = "tres";
                break;
            case "4":
                $dia = "cuatro";
                break;
            case "5":
                $dia = "cinco";
                break;
            case "6":
                $dia = "seis";
                break;
            case "7":
                $dia = "siete";
                break;
            case "8":
                $dia = "ocho";
                break;
            case "9":
                $dia = "nueve";
                break;
            case "10":
                $dia = "diez";
                break;
            case "11":
                $dia = "once";
                break;
            case "12":
                $dia = "doce";
                break;
            case "13":
                $dia = "trece";
                break;
            case "14":
                $dia = "catorce";
                break;
            case "15":
                $dia = "quince";
                break;
            case "16":
                $dia = "dieciseis";
                break;
            case "17":
                $dia = "diecisiete";
                break;
            case "18":
                $dia = "dieciocho";
                break;
            case "19":
                $dia = "dicinueve";
                break;
            case "20":
                $dia = "veinte";
                break;
            case "21":
                $dia = "veintiun";
                break;
            case "22":
                $dia = "veintidos";
                break;
            case "23":
                $dia = "veintitres";
                break;
            case "24":
                $dia = "veinticuatro";
                break;
            case "25":
                $dia = "veinticinco";
                break;
            case "26":
                $dia = "veintiseis";
                break;
            case "27":
                $dia = "veintisiete";
                break;
            case "28":
                $dia = "veintiocho";
                break;
            case "29":
                $dia = "veintiueve";
                break;
            case "30":
                $dia = "treinta";
                break;
            case "31":
                $dia = "treinta y un";
                break;
        }
        $ano = date("Y", $fecha); // obtenemos el a�o en formato 4 digitos
        $fecha = $dia . " dia(s) del mes de " . $mes . " de " . $ano; // unimos el resultado en una unica cadena
        return $fecha; // enviamos la fecha al programa
    }

    function traducefechaDocto($fecha)
    {
        if (strlen(trim($fecha)) == 0)
            return ("<NO ESPECIFICADA>");
        
        $fecha = strtotime(trim($fecha)); // convierte la fecha de formato mm/dd/yyyy a marca de tiempo
        
        $dia = date("d", $fecha); // d�a del mes en n�mero
        $mes = date("m", $fecha); // n�mero del mes de 01 a 12
        switch ($mes) {
            case "01":
                $mes = "Enero";
                break;
            case "02":
                $mes = "Febrero";
                break;
            case "03":
                $mes = "Marzo";
                break;
            case "04":
                $mes = "Abril";
                break;
            case "05":
                $mes = "Mayo";
                break;
            case "06":
                $mes = "Junio";
                break;
            case "07":
                $mes = "Julio";
                break;
            case "08":
                $mes = "Agosto";
                break;
            case "09":
                $mes = "Septiembre";
                break;
            case "10":
                $mes = "Octubre";
                break;
            case "11":
                $mes = "Noviembre";
                break;
            case "12":
                $mes = "Diciembre";
                break;
        }
        
        switch ($dia) {
            case "1":
                $dia = "un";
                break;
            case "2":
                $dia = "dos";
                break;
            case "3":
                $dia = "tres";
                break;
            case "4":
                $dia = "cuatro";
                break;
            case "5":
                $dia = "cinco";
                break;
            case "6":
                $dia = "seis";
                break;
            case "7":
                $dia = "siete";
                break;
            case "8":
                $dia = "ocho";
                break;
            case "9":
                $dia = "nueve";
                break;
            case "10":
                $dia = "diez";
                break;
            case "11":
                $dia = "once";
                break;
            case "12":
                $dia = "doce";
                break;
            case "13":
                $dia = "trece";
                break;
            case "14":
                $dia = "catorce";
                break;
            case "15":
                $dia = "quince";
                break;
            case "16":
                $dia = "dieciseis";
                break;
            case "17":
                $dia = "diecisiete";
                break;
            case "18":
                $dia = "dieciocho";
                break;
            case "19":
                $dia = "dicinueve";
                break;
            case "20":
                $dia = "veinte";
                break;
            case "21":
                $dia = "veintiun";
                break;
            case "22":
                $dia = "veintidos";
                break;
            case "23":
                $dia = "veintitres";
                break;
            case "24":
                $dia = "veinticuatro";
                break;
            case "25":
                $dia = "veinticinco";
                break;
            case "26":
                $dia = "veintiseis";
                break;
            case "27":
                $dia = "veintisiete";
                break;
            case "28":
                $dia = "veintiocho";
                break;
            case "29":
                $dia = "veintiueve";
                break;
            case "30":
                $dia = "treinta";
                break;
            case "31":
                $dia = "treinta y un";
                break;
        }
        $ano = date("Y", $fecha); // obtenemos el a�o en formato 4 digitos
        $fecha = $dia . " de " . $mes . " de " . $ano; // unimos el resultado en una unica cadena
        return $fecha; // enviamos la fecha al programa
    }
}
?>
