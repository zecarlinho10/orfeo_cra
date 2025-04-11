
<?php
echo "Hola Mundo";
//var_dump(shell_exec('whoami'));
//var_dump(shell_exec('libreoffice --headless --convert-to pdf doc.odt'));
//var_dump(shell_exec('soffice --headless --convert-to pdf doc.odt'));
var_dump(exec("soffice --headless --convert-to pdf /var/www/html/orfeonew/pruebas/testodt.odt"));
?>
