<?php

$carpetaBodega = 'bodega/';
define('FPDF_FONTPATH',"$ruta_raiz/fpdf/font/");
require("$ruta_raiz/config.php");
require("$ruta_raiz/fpdf/fpdf.php");

require("$ruta_raiz/radsalida/masiva/OpenDocText.class.php");
class ReportExpressPDF extends FPDF{


    var $versionForm;
    var $dependencia;
    var $objODT;
  
    function  __construct($db=null)
    {
        //$this->cnn = $db;
        $this->objODT = new OpenDocText();
    }
    
    function creaFormato($vecDepDest)
    {
        parent::__construct('L','mm', 'letter');
        $this->SetFont('Arial','B',5);
        $this->SetTitle("Formato Radicaci�n Express");
        $this->SetAuthor("");
        $this->SetCreator("Developed by Grupo Iyunxi Ltda.");
        $this->AliasNbPages();
        $this->cuerpo($vecDepDest,$vecDepInf);
    }

    function Header()
    {
      
    }

   
    function Footer()
    {
     
    }

    function cuerpo($depDest)
    {
        $WIMP = $this->w - (2*$this->lMargin);
        //$this->SetFillColor(200,220,255);
        $this->SetFont('Arial','B',7);        
        $evalDep=$depDest[0]['DEPDESTINO'];
        $evalRad=$depDest[0]['RADICADO'];
        $contadores=$this->contadores($depDest);
        $band=true;
        $this->dependencia=$evalDep;
        $this->AddPage();
        $this->Cell($WIMP,5,iconv($this->objODT->codificacion($this->Entidad),'ISO-8859-1',$this->Entidad),0,2,'C');
        $this->Cell($WIMP,5,'Secci�n Gesti�n Documental',0,2,'C');
        $this->Cell($WIMP,5,$this->titulo,0,2,'C');
        $this->SetFont('Arial','',7);
        $this->Cell($WIMP,5,"",0,1,'C');
        $this->Cell($WIMP*0.35,5,"Dependencia",0,0,'C');$this->Cell($WIMP*0.35,5,"Rango de Impresi�n",0,0,'C');$this->Cell($WIMP*0.20,5,"Fecha de Impresi�n",0,0,'C');$this->Cell($WIMP*0.10,5,"No de Registros",0,1,'C');
        $this->Cell($WIMP*0.35,5,$this->dependencia,0,0,'C');$this->Cell($WIMP*0.35,5,"Desde:".$this->fechaIni." Hasta:".$this->fechaFin,0,0,'C');$this->Cell($WIMP*0.20,5,$this->fechaHoy,0,0,'C');$this->Cell($WIMP*0.10,5,$contadores[$this->dependencia],0,1,'C');
        $this->Cell($WIMP,5,"",0,1,'C');
        $this->Cell($WIMP*0.11,5,"RADICADO",1,0,'C');
        $this->Cell($WIMP*0.23,5,"No. OFICIO",1,0,'C');
        $this->Cell($WIMP*0.34,5,"REMITENTE",1,0,'C');
        $this->Cell($WIMP*0.16,5,"DIGNATARIO",1,0,'C');
        $this->Cell($WIMP*0.10,5,"ANEXOS",1,0,'C');
        $this->SetFont('Arial','',6);
        $this->Cell($WIMP*0.03,5,"FOL.",1,0,'C');
        //$this->Cell($WIMP*0.03,5,"Urg ?",1,0,'C');
        $this->Cell($WIMP*0.03,5,"Cop",1,1,'C');
        $this->SetFont('Arial','',7);
        while(count($depDest)>0)
        {
	        foreach ($depDest as $a =>$valDest)
	        {
	                if($evalDep==$valDest['DEPDESTINO'])
	                {
	                        $this->Cell($WIMP*0.11,5,$valDest['RADICADO'],1,0);
	                        $this->Cell($WIMP*0.23,5,$valDest['NOFICIO'],1,0);
	                        $this->Cell($WIMP*0.34,5,substr(iconv($this->objODT->codificacion($valDest['REMITENTE']),'ISO-8859-1',$valDest['REMITENTE']),0,59),1,0);
	                        $this->Cell($WIMP*0.16,5,substr(iconv($this->objODT->codificacion($valDest['DIGNATARIO']),'ISO-8859-1',$valDest['DIGNATARIO']),0,35),1,0);
	                        $this->Cell($WIMP*0.10,5,substr(iconv($this->objODT->codificacion($valInf['ANEXOS']),'ISO-8859-1',$valInf['ANEXOS']),0,20),1,0);
	                        $this->Cell($WIMP*0.03,5,$valDest['NHOJA'],1,0);
	                        //$this->Cell($WIMP*0.03,5,$valDest['URGENTE'],1,0);
	                        $valDest['TIPO']?$cc='X':$cc='';
	                        $this->Cell($WIMP*0.03,5,$cc,1,1);
	                        if($band)
	                        {
	                            foreach ($depDest as $b => $valInf)
	                            {
	                                if($valDest['RADICADO']!=$valInf['RADICADO'] && $evalDep==$valInf['DEPDESTINO'])
	                                {
	                                    $this->Cell($WIMP*0.11,5,$valInf['RADICADO'],1,0);
	                                    $this->Cell($WIMP*0.23,5,$valInf['NOFICIO'],1,0);
	                                    $this->Cell($WIMP*0.34,5,substr(iconv($this->objODT->codificacion($valInf['REMITENTE']),'ISO-8859-1',$valInf['REMITENTE']),0,59),1,0);
	                                    $this->Cell($WIMP*0.16,5,substr(iconv($this->objODT->codificacion($valInf['DIGNATARIO']),'ISO-8859-1',$valInf['DIGNATARIO']),0,35),1,0);
	                                    $this->Cell($WIMP*0.10,5,substr(iconv($this->objODT->codificacion($valInf['ANEXOS']),'ISO-8859-1',$valInf['ANEXOS']),0,20),1,0);
	                                    $this->Cell($WIMP*0.03,5,$valInf['NHOJA'],1,0);
	                                    //$this->Cell($WIMP*0.03,5,$valInf['URGENTE'],1,0);
	                                    $valInf['TIPO']?$cc='X':$cc='';
	                        			$this->Cell($WIMP*0.03,5,$cc,1,1);
	                                   	unset($depDest[$b]);
	                                }
	                            }
	                            $band=false;
	                        }
	                        $evalRad=$valDest['RADICADO'];
	                        unset($depDest[$a]);
	                }
	                else
	                {
	                    foreach ($depDest as $b => $valInf)
	                    {
	                        if($valDest['RADICADO']!=$valInf['RADICADO'] && $evalDep==$valInf['DEPDESTINO'])
	                        {
	                            $this->Cell($WIMP*0.11,5,$valInf['RADICADO'],1,0);
	                            $this->Cell($WIMP*0.23,5,$valInf['NOFICIO'],1,0);
	                            $this->Cell($WIMP*0.34,5,substr(iconv($this->objODT->codificacion($valInf['REMITENTE']),'ISO-8859-1',$valInf['REMITENTE']),0,59),1,0);
	                            $this->Cell($WIMP*0.16,5,substr(iconv($this->objODT->codificacion($valInf['DIGNATARIO']),'ISO-8859-1',$valInf['DIGNATARIO']),0,35),1,0);
	                            $this->Cell($WIMP*0.10,5,substr(iconv($this->objODT->codificacion($valInf['ANEXOS']),'ISO-8859-1',$valInf['ANEXOS']),0,20),1,0);
	                            $this->Cell($WIMP*0.03,5,$valInf['NHOJA'],1,0);
	                            //$this->Cell($WIMP*0.03,5,$valInf['URGENTE'],1,0);
	                            $valInf['TIPO']?$cc='X':$cc='';
	                        	$this->Cell($WIMP*0.03,5,$cc,1,1);
	                            unset($depDest[$b]);
	                        }
	                    }
	                    
                            $this->SetFont('Arial','',7);
                            $this->Cell(70,20,'Observaciones:',0,1,'L');
                            $this->Cell($WIMP*0.33,5,'Fecha de Entrega:',0,0,'L');
                            $this->Cell($WIMP*0.33,5,'Entreg�:',0,0);
                            $this->Cell($WIMP*0.33,5,'Recibi�:',0,1);
	                    	$this->dependencia=$valDest['DEPDESTINO'];
                            $this->AddPage();
                            $this->SetFont('Arial','B',7);
                            $this->Cell($WIMP,5,$this->Entidad,0,2,'C');
                            $this->Cell($WIMP,5,'Secci�n Gesti�n Documental',0,2,'C');
                            $this->Cell($WIMP,5,$this->titulo,0,2,'C');
                            $this->SetFont('Arial','',7);
                            $this->Cell($WIMP,5,"",0,1,'C');
                            $this->Cell($WIMP*0.35,5,"Dependencia",0,0,'C');$this->Cell($WIMP*0.35,5,"Rango de Impresi�n",0,0,'C');$this->Cell($WIMP*0.20,5,"Fecha de Impresi�n",0,0,'C');$this->Cell($WIMP*0.10,5,"No de Registros",0,1,'C');
                            $this->Cell($WIMP*0.35,5,utf8_decode($this->dependencia),0,0,'C');$this->Cell($WIMP*0.35,5,"Desde:".$this->fechaIni." Hasta:".$this->fechaFin,0,0,'C');$this->Cell($WIMP*0.20,5,$this->fechaHoy,0,0,'C');$this->Cell($WIMP*0.10,5,$contadores[$this->dependencia],0,1,'C');
                            $this->Cell($WIMP,5,"",0,1,'C');
                            $this->Cell($WIMP*0.11,5,"RADICADO",1,0,'C');
                            $this->Cell($WIMP*0.23,5,"No. OFICIO",1,0,'C');
                            $this->Cell($WIMP*0.34,5,"REMITENTE",1,0,'C');
                            $this->Cell($WIMP*0.16,5,"DIGNATARIO",1,0,'C');
                            $this->Cell($WIMP*0.10,5,"ANEXOS",1,0,'C');
                            $this->SetFont('Arial','',6);
                            $this->Cell($WIMP*0.03,5,"FOL.",1,0,'C');
                            //$this->Cell($WIMP*0.03,5,"Urg ?",1,0,'C');
                            $this->Cell($WIMP*0.03,5,"Cop",1,1,'C');
                            $this->SetFont('Arial','',7);
                            $this->Cell($WIMP*0.11,5,$valDest['RADICADO'],1,0);
                            $this->Cell($WIMP*0.23,5,$valDest['NOFICIO'],1,0);
                            $this->Cell($WIMP*0.34,5,substr(iconv($this->objODT->codificacion($valDest['REMITENTE']),'ISO-8859-1',$valDest['REMITENTE']),0,59),1,0);
                            $this->Cell($WIMP*0.16,5,substr(iconv($this->objODT->codificacion($valDest['DIGNATARIO']),'ISO-8859-1',$valDest['DIGNATARIO']),0,35),1,0);
                            $this->Cell($WIMP*0.10,5,substr(iconv($this->objODT->codificacion($valInf['ANEXOS']),'ISO-8859-1',$valInf['ANEXOS']),0,20),1,0);
                            $this->Cell($WIMP*0.03,5,$valDest['NHOJA'],1,0);
                            //$this->Cell($WIMP*0.03,5,$valDest['URGENTE'],1,0);
                            $valDest['TIPO']?$cc='X':$cc='';
	                    $this->Cell($WIMP*0.03,5,$cc,1,1);
                            $evalRad=$valDest['RADICADO'];
                            $band=true;
                            unset($depDest[$a]);
	                }
	               $evalDep=$valDest['DEPDESTINO'];
	               break;
	        }
        }
        $this->SetFont('Arial','',7);
        $this->Cell(70,20,'Observaciones:',0,1,'L');
        $this->Cell($WIMP*0.33,5,'Fecha de Entrega:',0,0,'L');
        $this->Cell($WIMP*0.33,5,'Entreg�:',0,0);
        $this->Cell($WIMP*0.33,5,'Recibi�:',0,1);
    }

    function enlacePDF($i=0)
    {
    	include("../config.php");
        $band = true;
        $archivo = "tmp/RepExp".date('YmdHis')."$i.pdf";
//        $this->Output("../".$carpetaBodega.$archivo);
		$this->Output('F',BODEGA.$archivo); 

//               $band="../".$carpetaBodega.$archivo;
				 $band=BODEGA.$archivo;

      //  ($this->Output("../".$carpetaBodega.$archivo) == '') ? $band = "../seguridadImagen.php?fec=".base64_encode($archivo) : $band = false;
       return $band;
    }
    
    function contadores($arreglo)
    {		
    	$count=null;
    	foreach ($arreglo as $i=>$val)
    	{
    		$cont[$val['DEPDESTINO']]=$cont[$val['DEPDESTINO']]+1;
    	}
    	return $cont;
    }
}
?>
