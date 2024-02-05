<?php






$dni=$_POST["dni"];


if(strlen($dni)<8 || strlen($dni)>8)
{
    $prueba=1;
}
else{   
$prueba=file_get_contents('https://macexpress2.pcm.gob.pe/AtencionCiudadano/AtenderCiudadano/listarciudadano?dni='.$dni.'');
}








echo $prueba;
