<?php

namespace Unoconv;

class Unoconv {

    /**
    * Basic converter method
    * 
    * @param string $originFilePath Origin File Path
    * @param string $toFormat       Format to export To
    * @param string $outputDirPath  Output directory path
    */
    public static function convert($originFilePath, $outputDirPath, $toFormat){
        //$command = 'unoconv --format %s --output %s %s';
        //$command = sprintf($command, $toFormat, $outputDirPath, $originFilePath);
        
        //system($command, $output);
        /*
        $archivo = "/BodegaCopia/tmp/" . $originFilePath;
        $output = "/BodegaCopia/tmp/";
        */

	    $salida = system("export HOME=/tmp; unoconv -f " . $toFormat . " " . $originFilePath . '.odt', $outputDirPath);
        //system("chown apache:apache " . $originFilePath . "." . $toFormat, $output2);
        //echo (shell_exec("chown apache:apache " . $originFilePath . "." . $toFormat ));
        return $outputDirPath;
    }

    /**
    * Convert to PDF
    * 
    * @param string $originFilePath Origin File Path
    * @param string $outputDirPath  Output directory path
    */
    public static function convertToPdf($originFilePath, $outputDirPath){
        return self::convert($originFilePath, $outputDirPath, 'pdf');
    }

    /**
    * Convert to TXT
    * 
    * @param string $originFilePath Origin File Path
    * @param string $outputDirPath  Output directory path
    */
    public static function convertToTxt($originFilePath, $outputDirPath){
        return self::convert($originFilePath, $outputDirPath, 'txt');
    }

}
