<?php

function wgs2lest($Lat, $Lon)
{

$Lat_Radiaan = deg2rad($Lat);
$Lon_Radiaan = deg2rad($Lon);

$B0 = 1.003870694;
$B0 = deg2rad(57+31/60+03.19415/(60*60));
$B1 = 1.012290966;
$B1 = deg2rad(58);
$B2 = 1.035562023;
$B2 = deg2rad(59+20/60);
$L0 = 0.41887902;
$ee  = 0.081819191;
$a  = 6378137;
$F  = 1.798847851;
$P0 = 4020205.479;
$N  = 0.854175858;



$T1peal = (1 - Sin($Lat_Radiaan))/(1 + Sin($Lat_Radiaan));
$T2peal = (1 + Sin($Lat_Radiaan) * $ee)/(1 - Sin($Lat_Radiaan) * $ee);

$t = SQRT($T1peal * pow($T2peal, $ee));

$P = $a * $F * pow($t, $N);
$Q = $N * ($Lon_Radiaan - $L0);

$YY = $P0 - $P * Cos($Q) + 6375000;
$XX = $P * Sin($Q) + 500000;

return array("X" => $XX, "Y" => $YY);
}

?>
