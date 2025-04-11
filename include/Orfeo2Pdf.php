<?php
require_once realpath(dirname(__FILE__) . "/../") . "/include/tcpdf/tcpdf.php";
require_once realpath(dirname(__FILE__) . "/../") . "/include/utils/Utils.php";

// override the default TCPDF config file
if (! defined('K_TCPDF_EXTERNAL_CONFIG')) {
    define('K_TCPDF_EXTERNAL_CONFIG', TRUE);
}

if (! defined('K_CELL_HEIGHT_RATIO')) {
    define('K_CELL_HEIGHT_RATIO', 1.25);
}

class Orfeo2Pdf extends TCPDF
{

    /**
     * TCPDF system constants that map to settings in our config file
     *
     * @var array
     * @access private
     */
    private $cfg_constant_map = array(
        'K_PATH_MAIN' => 'base_directory',
        'K_PATH_URL' => 'base_url',
        'K_PATH_FONTS' => 'fonts_directory',
        'K_PATH_CACHE' => 'cache_directory',
        'K_PATH_IMAGES' => 'image_directory',
        'K_BLANK_IMAGE' => 'blank_image',
        'K_SMALL_RATIO' => 'small_font_ratio'
    );

    /**
     * Settings from our APPPATH/config/tcpdf.php file
     *
     * @var array
     * @access private
     */
    private $_config = array();

    private $xheadertext = 'PDF creado utilizando TCPDF';

    private $xheadercolor = array(
        0,
        0,
        200
    );

    private $xfootertext = ' generado el %s';

    private $gentext = ' generado el %s';

    private $radicado;

    private $dataSeg;

    private $fecha;

    private $imagenesPie;

    private $codigoVerificacion;

    /**
     * Initialize and configure TCPDF with the settings in our config file
     */
    public function __construct()
    {
        // load the config file
        // define('K_CELL_HEIGHT_RATIO', 1.25);
        require (realpath(dirname(__FILE__) . "/../") . '/conf/tcpdf.config.php');
        $this->_config = $tcpdf;
        unset($tcpdf);
        parent::__construct($this->_config['page_orientation'], $this->_config['page_unit'], $this->_config['page_format'], $this->_config['unicode'], $this->_config['encoding'], $this->_config['enable_disk_cache']);
        // set the TCPDF system constants
        foreach ($this->cfg_constant_map as $const => $cfgkey) {
            if (! defined($const)) {
                define($const, $this->_config[$cfgkey]);
            }
        }
        
        // initialize TCPDF
        // language settings
        if (is_file($this->_config['language_file'])) {
            include ($this->_config['language_file']);
            $this->setLanguageArray($l);
            unset($l);
        }
        
        // margin settings
        $this->SetMargins($this->_config['margin_left'], $this->_config['margin_top'], $this->_config['margin_right']);
        
        // header settings
        $this->print_header = $this->_config['header_on'];
        // this->print_header = FALSE;
        $this->setHeaderFont(array(
            $this->_config['header_font'],
            '',
            $this->_config['header_font_size']
        ));
        $this->setHeaderMargin($this->_config['header_margin']);
        $this->SetHeaderData($this->_config['header_logo'], $this->_config['header_logo_width'], $this->_config['header_title'], $this->_config['header_string']);
        
        // footer settings
        $this->print_footer = $this->_config['footer_on'];
        $this->setFooterFont(array(
            $this->_config['footer_font'],
            '',
            $this->_config['footer_font_size']
        ));
        $this->setFooterMargin($this->_config['footer_margin']);
        
        // page break
        $this->SetAutoPageBreak($this->_config['page_break_auto'], $this->_config['footer_margin']);
        
        // cell settings
        $this->cMargin = $this->_config['cell_padding'];
        $this->setCellHeightRatio($this->_config['cell_height_ratio']);
        
        // document properties
        $this->author = $this->_config['author'];
        $this->creator = $this->_config['creator'];
        
        // font settings
        // this->SetFont($this->_config['page_font'], '',
        // $this->_config['page_font_size']);
        
        // image settings
        $this->imgscale = $this->_config['image_scale'];
    }

    public function setCodigoVeficacion($codigoVerificacion)
    {
        $this->codigoVerificacion = $codigoVerificacion;
    }

    public function getCodigoVeficacion()
    {
        return $this->codigoVerificacion;
    }

    public function setImageHeader($imagePath)
    {
        $this->header_logo = $imagePath;
    }

    public function setImagenesPie($imagenesPie)
    {
        $this->imagenesPie = $imagenesPie;
    }

    public function getImagenesPie()
    {
        return $this->imagenesPie;
    }

    public function setRadicado($radicado)
    {
        $this->radicado = $radicado;
    }

    public function setFechaRadicado($fecha)
    {
        $this->fecha = $fecha;
    }

    public function getRadicado()
    {
        return $this->radicado;
    }

    public function getFechaRadicado()
    {
        return $this->fecha;
    }

    public function getData()
    {
        return $this->dataSeg;
    }

    public function setData($data)
    {
        $this->dataSeg = $data;
    }

    public function setFooterText($text)
    {
        $this->xfootertext = $text;
    }

    public function Header()
    {
        $ormargins = $this->getOriginalMargins();
        $headerfont = $this->getHeaderFont();
        $headerdata = $this->getHeaderData();
        if (($headerdata['logo']) && ($headerdata['logo'] != $this->_config['blank_image'])) {
            $imgtype = TCPDF_IMAGES::getImageFileType($this->header_logo);
            if (($imgtype == 'eps') || ($imgtype == 'ai')) {
                $this->ImageEps($this->header_logo, '', '', $headerdata['logo_width']);
            } elseif ($imgtype == 'svg') {
                $this->ImageSVG($this->header_logo, '', '', $headerdata['logo_width']);
            } else {
                $this->Image($this->header_logo, '', '', $headerdata['logo_width']);
            }
            $imgy = $this->getImageRBY();
        } else {
            $imgy = $this->GetY();
        }
        $cell_height = round(($this->getCellHeightRatio() * $headerfont[2]) / $this->getScaleFactor(), 1.83);
        // set starting margin for text data cell
        if ($this->getRTL()) {
            $header_x = $ormargins['right'] + ($headerdata['logo_width'] * 1.1);
        } else {
            $header_x = $ormargins['left'] + ($headerdata['logo_width'] * 1.1);
        }
        $this->SetTextColor(0, 0, 0);
        // header title
        $this->SetFont($headerfont[0], 'B', $headerfont[2]);
        $this->SetX($header_x);
        $this->Cell(0, $cell_height, $headerdata['title'], 0, 1, '', 0, '', 0);
        // header string
        $this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
        $this->SetX($header_x);
        $this->MultiCell($header_x, $cell_height, $headerdata['string'], 0, '', 0, 1, '', '', true, 0, false);
        $radicado = $this->getRadicado();
        $cur_y = $this->GetY() + 10;
        $line_width = 0.85 / $this->getScaleFactor();
        if (! empty($radicado)) {
            $this->Cell(0, $cell_height, "", 0, 1, 'R', 0, '', 0);
            $this->Cell(0, $cell_height, "", 0, 1, 'R', 0, '', 0);
            $this->Cell(0, $cell_height, "", 0, 1, 'R', 0, '', 0);
            $this->Cell(0, $cell_height, "Al responder cite :" . $radicado, 0, 1, 'R', 0, '', 0);
            $barcode_width = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 8);
            $style = array(
                'position' => $this->rtl ? 'L' : 'R',
                'align' => $this->rtl ? 'R' : 'L',
                'stretch' => true,
                'fitwidth' => true,
                'cellfitalign' => true,
                'border' => false,
                'padding' => 2,
                'fgcolor' => array(
                    0,
                    0,
                    0
                ),
                'bgcolor' => false,
                'text' => false,
                'font' => 'arial', 
                'fontsize' => 12
            );
            $this->write1DBarcode($radicado, 'C39+', ($cur_y), ($cur_y + 14), '', 14, 0.2, $style, '');
        }
        if ($this->codigoVerificacion != null) {
            $this->Cell(0, $cell_height, "Código de Verificación: " . $this->codigoVerificacion, 0, 1, 'R', 0, '', 0);
        }
        if ($this->fecha != null) {
            $this->Cell(0, $cell_height, "Fecha Rad: " . $this->fecha, 0, 1, 'R', 0, '', 0);
        }
        $this->Cell(0, $cell_height, "", 0, 1, 'R', 0, '', 0);
        $this->Cell(0, $cell_height, "", 0, 1, 'R', 0, '', 0);
        $this->Cell(0, $cell_height, "", 0, 1, 'R', 0, '', 0);
        
        if ($this->getRTL()) {
            $this->SetX($ormargins['right']);
        } else {
            $this->SetX($ormargins['left']);
        }
        $this->SetFont($headerfont[0], '', $headerfont[2]);
        
        $style = array(
            'border' => false,
            'padding' => 0,
            'fgcolor' => array(
                0,
                0,
                0
            ),
            'bgcolor' => false
        );
        $this->SetLineStyle(array(
            'width' => $line_width,
            'cap' => 'butt',
            'join' => 'miter',
            'dash' => 0,
            'color' => array(
                0,
                0,
                0
            )
        ));
        $cur_y = $this->GetY();
        
        $cur_y = $this->GetY();
    }

    public function Footer()
    {
        $year = date('Y-m-d H:i:s');
        $footertext = sprintf($this->gentext, $year);
        // $footertext =$this->xfootertext;
        $this->SetFont($this->_config['footer_font'], '', $this->_config['footer_font_size']);
        $cur_y = $this->GetY();
        $ormargins = $this->getOriginalMargins();
        $this->SetTextColor(0, 0, 0);
        // set style for cell border
        $line_width = 0.85 / $this->getScaleFactor();
        $this->SetLineStyle(array(
            'width' => $line_width,
            'cap' => 'butt',
            'join' => 'miter',
            'dash' => 0,
            'color' => array(
                0,
                0,
                0
            )
        ));
        // print document barcode
        $radicado = $this->getRadicado();
        $i = 10;
        if (! empty($this->imagenesPie) && is_array($this->imagenesPie)) {
            foreach ($this->imagenesPie as $value) {
                $imgtype = TCPDF_IMAGES::getImageFileType($value);
                if (($imgtype == 'eps') || ($imgtype == 'ai')) {
                    $this->ImageEps($value, $ormargins['left'] + $i);
                } elseif ($imgtype == 'svg') {
                    $this->ImageSVG($value, $ormargins['left'] + $i);
                } else {
                    $this->Image($value, $ormargins['left'] + $i);
                }
                $i += 10;
            }
        }
        unset($value);
        
        $this->SetFont('arial', '', 7);
        $this->Cell(0, 0, $footertext, 0, 1, 'C', 0, '', 0);
        if (! empty($this->xfootertext) && is_array($this->xfootertext)) {
            $this->Cell(0, 0, " ", 0, 1, 'C', 0, '', 0);
            foreach ($this->xfootertext as $value) {
                $this->Cell(0, 0, $value, 0, 1, 'L', 0, '', 0);
            }
        }
        if (empty($this->pagegroups)) {
            $pagenumtxt = $this->l['w_page'] . ' ' . $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages();
        } else {
            $pagenumtxt = $this->l['w_page'] . ' ' . $this->getPageNumGroupAlias() . ' / ' . $this->getPageGroupAlias();
        }
        $this->SetY($cur_y);
        // Print page number
        if ($this->getRTL()) {
            $this->SetX($ormargins['right']);
            $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');
        } else {
            $this->SetX($ormargins['left']);
            $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'R');
        }
        if (! empty($radicado)) {
            $this->Ln($line_width);
            $barcode_width = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
            $style = array(
                'position' => 'R' ,
                'align' => $this->rtl ? 'R' : 'L',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => false,
                'padding' => 10,
                'fgcolor' => array(
                    0,
                    0,
                    0
                ),
                'bgcolor' => false,
                'text' => false
            );
            $dataSeg = $this->getData();
            //$code,$type,$x = “,$y = “,$w = “,$h = “,$style = “,$align = “,$distort = false
            $this->write2DBarcode($dataSeg, 'QRCODE,H', $ormargins['left']-20 , "", 20, 20, $style, 'T');
        }
    }

    public function viewInfo(&$datos, &$blackList)
    {
        $info = "";
        foreach ($datos as $clave => $value) {
            if (! in_array($clave, $blackList)) {
                $info .= $clave . ": " . $value . "\n\r";
            }
        }
        
        $this->Write($h = 0, utf8_encode($info) . "\n\r", $link = '', $fill = 0, $align = 'J', $ln = true, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0);
    }

    public function infoPage($datos, $blackList = array ("Nombre", "Descripcion" ))
    {}
}
?>
