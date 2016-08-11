<?php
global $Page_Cont_Row;
global $Page_Conf_Array;
global $WebISConfig;

$Dokey = Get_Get('do');
$Page_Cont_Row = Get_Value($Page_Conf_Array, $Dokey);

$p = new PDFlib();

    /*  open new PDF file; insert a file name to create the PDF on disk */
    if ($p->begin_document("", "") == 0) {
        die("Error: " . $p->get_errmsg());
    }

    $path = LPath . "/pdf/";

global $db;

$db->set_paring(sprintf("select * from projektid where id=%d", $_SESSION["TeemaID"]));
$TeemaInfo = ab_fetch_object($db->execute());

$db->set_paring(sprintf("select * from proj_prtyld where id_prtyld=%d and id_projektid=%d", $_SESSION["prtid"], $_SESSION["TeemaID"]));
$PrtInfo = ab_fetch_object($db->execute());

$db->set_paring(sprintf("select * from prtmoot where id=%d", $_SESSION["mootid"]));
$MootInfo = ab_fetch_object($db->execute());

$PrtYldInfo = "Teema: " . $TeemaInfo->id . ", ". substr($TeemaInfo->nimi, 0, 100) .", proovitükk: " . $PrtInfo->prtnimi . ", mõõtmiskord: " . $MootInfo->mootkord;


$files1 = scandir($path);

function posY($mm)
{
    return (842-842/297*$mm);
    
}

function posX($mm)
{
    return (595/210*$mm);
}
   
    $p->set_parameter("FontOutline", "Arial=$path/arial.ttf");
    $p->set_parameter("FontOutline", "Coronet=$path/10249.ttf");
    $p->set_parameter("FontOutline", "StempelGaramondR=$path/11546.ttf");
    $p->set_parameter("FontOutline", "Coronet=$path/90249.ttf");
    
    
    $p->set_info("Creator", "hello.php");
    $p->set_info("Author", "Allan Sims");
    $p->set_info("Title", "ForMIS");

    $p->begin_page_ext(595, 842, "");

    $fontArial = $p->load_font("Arial", "winansi", "embedding=true");
    $fontCoron = $p->load_font("Coronet", "winansi", "embedding=true");
    $fontGoudy = $p->load_font("StempelGaramondR", "winansi", "embedding=true");

    $p->setfont($fontCoron, 8.0);
    $p->set_text_pos(posX(15), posY(5));
    $p->show("ForMIS - Metsandusliku modelleerimise infosüsteem");
    $p->set_text_pos(posX(15), posY(10));
    $p->show($PrtYldInfo);

    $p->setfont($fontArial, 12.0);
    $p->set_text_pos(posX(20), posY(20));
    
    $p->show($Page_Cont_Row["head"] . " " . $p->get_value("major", 0) . "." . $p->get_value("minor", 0));

    $p->setfont($fontArial, 9.0);
    
    
    /*
        $textx = $p->get_value("textx", 0);
        $texty = $p->get_value("texty", 0);
        
        if($texty < 30):
            $p->end_page_ext("");
            $p->begin_page_ext(595, 842, "");
            $p->setfont($fontArial, 10.0);
            $p->set_text_pos(posX(5), posY(15));
            $p->show("");
        endif;
*/
    $tbl = 0;
    
    //$db->set_paring($Page_Cont_Row["csql"]);
    //$Tulem $db->execute();
    
    $row = 1; $col = 1;
    $tbl = $p->add_table_cell($tbl, $col, $row, "A", "");
    
    $p->fit_table($tbl, 30, 30, 100, 100);

    $p->end_page_ext("");

    $p->end_document("");

    $buf = $p->get_buffer();
    $len = strlen($buf);

    header("Content-type: application/pdf");
    header("Content-Length: $len");
    header("Content-Disposition: inline; filename=ForMIS_". Get_Value($_GET, 'do').".pdf");
    print $buf;
    $p->delete();
?>
