<?php
/*
 *
 * @ Multiple File upload script.
 *
 * @ Can do any number of file uploads
 * @ Just set the variables below and away you go
 *
 * @ Author: Kevin Waterson
 * @ Modified: Sebastian Ortiz V. 2012
 * @copywrite 2008 PHPRO.ORG
 *
 */

/**
 * * an array to hold messages **
 */

class CargarArchivo
{

    public $messages = array();

    public $FILES = array();

    public $subidos = array();

    public $sha1sums = array();

    public $sizes = array();

    private $nombreOrfeo = array();

    private $upload_dir = "/tmp/";

    private $bodega_dir = "/tmp/";

    private $listadoImprimible = "";

    private $tieneArchivos = false;

    private $rutaAbsoluta = "/";

    public function __construct($FilesList, $rutaAbsoluta)
    {
	include_once (realpath(dirname(__FILE__) . "/../")."/config.php");    
        $this->FILES = $FilesList;
        $this->rutaAbsoluta = $rutaAbsoluta;
        $this->upload_dir = BODEGA. "/" . "/tmp/";
        $this->bodega_dir = BODEGA;
    }

    public function getListadoImprimible()
    {
        return $this->listadoImprimible;
    }

    public function getBodegaDir()
    {
        return $this->bodega_dir;
    }

    public function tieneArchivos()
    {
        return $this->tieneArchivos;
    }

    public function getNombreOrfeo()
    {
        return $this->nombreOrfeo;
    }

    public function adjuntarYaSubidos()
    {
        $error = 0;
        $this->tieneArchivos = false;
        if (is_array($this->subidos) && ! empty($this->subidos)) {
            foreach ($this->subidos as $archivosSubidos) {
                if (! is_file($this->upload_dir . $archivosSubidos)) {
                    $error = 1;
                }
            }
            if ($error > 0 || sizeof($this->subidos) == 0) {
                $this->tieneArchivos = false;
                return false;
            } else {
                $this->tieneArchivos = true;
                $this->listadoAdjuntosConHashesYaSubidos();
                return true;
            }
        }
    }
    // Deprecated Ya no se usa por que se suben con FineUploader
    /**
     * @deprecade
     *
     * @return boolean
     */
    public function adjuntarArchivos()
    {
        $error = 0;
        error_reporting(E_ALL);
        /**
         * * the upload directory **
         */
        
        /**
         * * the maximum filesize from php.ini **
         */
        $ini_max = str_replace('M', '', ini_get('upload_max_filesize'));
        $upload_max = $ini_max * 1024 * 1024;
        $max_file_size = 5242880;
        /* Added to support a maximun upload size adding all individual file sizes */
        $uploaded_size = 0;
        /**
         * * check if a file has been submitted **
         */
        if (isset($this->FILES['userfile']['tmp_name']) && $this->FILES['userfile']['tmp_name'][0] == "") {
            return true;
        }
        if (isset($this->FILES['userfile']['tmp_name'])) {
            /**
             * loop through the array of files **
             */
            for ($i = 0; $i < count($this->FILES['userfile']['tmp_name']); $i ++) {
                // check if there is a file in the array
                if (! is_uploaded_file($this->FILES['userfile']['tmp_name'][$i])) {
                    if (strlen($this->FILES['userfile']['tmp_name'][$i]) == 0) {
                        continue;
                    }
                    
                    $this->messages[] = 'No se adjunto ningun archivo, o se alcanzo el tama&ntilde;o m&aacute;ximo.';
                    $error += 1;
                } /**
                 * * check if the file is less then the max php.ini size **
                 */
                elseif ($this->FILES['userfile']['size'][$i] + $uploaded_size > $upload_max) {
                    $this->messages[] = "Los archivos superan el m&aacute;ximo permitido $upload_max php.ini limit (20M)";
                    $error += 1;
                } // check the file is less than the maximum file size
elseif ($this->FILES['userfile']['size'][$i] > $max_file_size) {
                    $this->messages[] = "El archivo supera el m&aacute;ximo permitido $max_file_size limit (5M)";
                    $error += 1;
                } else {
                    // Copiar los archivos a un directorio temporal mientras se obtiene un numero de radicado para asociarlos.
                    if (@copy($this->FILES['userfile']['tmp_name'][$i], $this->upload_dir . '/' . basename($this->FILES['userfile']['tmp_name'][$i]))) {
                        /**
                         * * give praise and thanks to the php gods **
                         */
                        $this->messages[] = $this->FILES['userfile']['name'][$i] . ' uploaded';
                        $uploaded_size += $this->FILES['userfile']['size'][$i];
                        $this->calcularSHA1SumAnexos(basename($this->FILES['userfile']['tmp_name'][$i]));
                    } else {
                        /**
                         * * an error message **
                         */
                        $this->messages[] = 'Uploading ' . $this->FILES['userfile']['name'][$i] . ' Failed';
                        $error += 1;
                    }
                }
            }
            if ($error > 0) {
                return false;
            } else {
                $this->tieneArchivos = true;
                $this->listadoAdjuntosConHashes();
                return true;
            }
        }
    }

    public function calcularSHA1SumAnexos($fileName)
    {
        $this->sha1sums[] = sha1_file($this->upload_dir . "/" . $fileName);
        $this->sizes[] = intval(filesize($this->upload_dir . "/" . $fileName) / 1024);
    }

    public function listadoAdjuntosConHashes()
    {
        if (! $this->tieneArchivos) {
            $this->listadoImprimible = "No se adjunta ningún archivo\n";
            return;
        }
        $this->listadoImprimible = "Se adjuntan los siguientes archivos:\n";
        for ($i = 0; $i < count($this->FILES['userfile']['name']); $i ++) {
            if (strlen($this->FILES['userfile']['tmp_name'][$i]) == 0) {
                continue;
            }
            $this->listadoImprimible .= ($i + 1) . ". " . $this->FILES['userfile']['name'][$i] . " sha1sum: " . $this->sha1sums[$i] . "\n";
        }
    }

    public function listadoAdjuntosConHashesYaSubidos()
    {
        if (isset($this->subidos) and sizeof($this->subidos) == 0) {
            $this->listadoImprimible = "No se adjunta ningún archivo\n";
            return;
        }
        
        $this->listadoImprimible = "Se adjuntan los siguientes archivos:\n";
        
        for ($i = 0; $i < count($this->subidos); $i ++) {
            if (strlen($this->subidos[$i]) == 0) {
                continue;
            }
            $this->calcularSHA1SumAnexos($this->subidos[$i]);
            $this->listadoImprimible .= ($i + 1) . ". " . $this->subidos[$i] . " sha1sum: " . $this->sha1sums[$i] . "\n";
        }
    }

    public function moverArchivoCarpetaBodega($numrad, $dependencia)
    {
        if (! $this->tieneArchivos) {
            return;
        }
        $index = count($this->nombreOrfeo);
        $index = ($index == 0) ? 0 : $index;
        // Si todo fue bien entonces mover los archivos de la carpeta temporal a bodega.
        for ($i = 0; !empty($this->FILES['userfile']['name']) && is_array($this->FILES['userfile']['name']) && $i < count($this->FILES['userfile']['name']); $i ++) {
            if (strlen($this->FILES['userfile']['tmp_name'][$i]) != 0) {
                $index ++;
                $extension = end(explode('.', $this->FILES['userfile']['name'][$i]));
                // Bug fix si el archivo no tiene extensión.
                $extension = $extension ? '.' . $extension : '';
                $this->nombreOrfeo[$index]["extension"] = $extension;
                $nombreArchivo = $numrad . '_' . substr('00000' . ($index), - 5) . $extension;
                $this->nombreOrfeo[$index]["nombreArchivo"] = $nombreArchivo;
                $this->nombreOrfeo[$index]["descr"] = $this->subidos[$i];
                 
                if (rename($this->upload_dir . '/' . basename($this->FILES['userfile']['tmp_name'][$i]), $this->bodega_dir . '/' . $dependencia . "/docs/" . $nombreArchivo)) {
                  //   echo "Archivo movido exitoso";
                } else {
                     //echo "Error moviendo a destino final";
                }
            }
        }
    }

    public function moverArchivoCarpetaBodegaYaSubidos($numrad, $dependencia)
    {
        if (! $this->tieneArchivos) {
            return;
        }
        $index = count($this->nombreOrfeo);
        $index = ($index == 0) ? 0 : $index;
        // Si todo fue bien entonces mover los archivos de la carpeta temporal a bodega.
        for ($i = 0; $i < count($this->subidos); $i ++) {
            if (strlen($this->subidos[$i]) != 0) {
		    $index++;
		    $trozos = explode('.', $this->subidos[$i]);
                $extension = end($trozos);
                // Bug fix si el archivo no tiene extensión.
                $extension = $extension ? '.' . $extension : '';
                $this->nombreOrfeo[$index]["extension"] = $extension;
                $nombreArchivo = $numrad . '_' . substr('00000' . ($index), - 5) . $extension;
                $this->nombreOrfeo[$index]["nombreArchivo"] = $nombreArchivo;
                $this->nombreOrfeo[$index]["descr"] = $this->subidos[$i];
                if (@rename($this->upload_dir . '/' . basename($this->subidos[$i]), $this->bodega_dir . '/'.date('Y')."/" . $dependencia . "/docs/1" . $nombreArchivo)) {
                     echo "Archivo movido exitoso";
                } else {
                     echo "Error moviendo a destino final";
                }
            }
        }
    }
}
?>
