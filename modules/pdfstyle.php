<?php

function Rinne1($pdf, $X, $Y, $Diam, $varv)
{
	$pdf->SetLineStyle($varv);

	$pdf->Circle($X, $Y, $Diam);
}

function Rinne2($pdf, $X, $Y, $Diam, $varv)
{
	$pdf->Polygon(array(
		$X - $Diam, $Y + $Diam, 
		$X + $Diam, $Y + $Diam, 
		$X + $Diam, $Y - $Diam, 
		$X - $Diam, $Y - $Diam
		), null, array('all' => $varv)
		);
}

function RinneA($pdf, $X, $Y, $Diam, $varv)
{
	$pdf->Polygon(array(
		$X - $Diam, $Y, 
		$X, $Y - $Diam,
		$X + $Diam, $Y, 
		$X, $Y + $Diam 
		), null, array('all' => $varv)
		);
}

function RinneJ($pdf, $X, $Y, $Diam, $varv)
{
		$pdf->Polygon(array(
			$X - $Diam, $Y + $Diam, 
			$X + $Diam, $Y + $Diam, 
			$X       ,  $Y - $Diam
			), null, array('all' => $varv)
			);
}

function RinneS($pdf, $X, $Y, $Diam, $varv)
{
	$pdf->SetLineStyle($varv);
	$pdf->StarPolygon($X , $Y, $Diam, 8, 3, 3, 0);
}

function RinneK($pdf, $X, $Y, $Diam, $varv)
{
	$pdf->Line( $X - $Diam, $Y, $X + $Diam, $Y, $varv);

}

function RinneM($pdf, $X, $Y, $Diam, $varv)
{
	$pdf->Line( $X - $Diam, $Y, $X + $Diam, $Y, $varv);

}

function RinneT($pdf, $X, $Y, $Diam, $varv)
{
	$pdf->Line( $X - $Diam, $Y, $X + $Diam, $Y, $varv);
	$pdf->Line( $X , $Y- $Diam, $X, $Y + $Diam, $varv);
}
