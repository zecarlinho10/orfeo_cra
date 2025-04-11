
<?php
echo "Hola Mundo";
//var_dump(shell_exec('whoami'));
echo "<pre>";
//var_dump(shell_exec('java  -jar ../include/JSignPdf-1.5.1/JSignPdf.jar pruebasWilsonFirmaP12.pdf -kst PKCS12  -ksf pruebasWilsonFirmaP12.p12   -ksp 123456 --font-size 7    -r \'Firmado al Radicar en OrfeoGPL\'  -V -v   --img-path ../imagenes/gnu.gif --render-mode  GRAPHIC_AND_DESCRIPTION -llx 0 -lly 0 -urx 550 -ury 27'));
echo "</pre>";
var_dump(shell_exec('soffice --headless --convert-to pdf doc.odt'));
?>
