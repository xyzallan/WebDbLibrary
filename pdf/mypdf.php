<?php

LoadLibrary("Libs.tcpdf.tcpdf", RPath);


class MYPDF extends TCPDF {

    public $_FontTavatekst;
    public $_FontPealkiri;
    public $_FontMuu;
    public $_FontMuuB;
	public $_KeskTitle;

    public function LaeFondid()
    {
        //TrueTypeUnicode
        $this->_FontPealkiri = TCPDF_FONTS::addTTFfont(LPath . '/pdf/goudy.ttf', 'TrueTypeUnicode', '', 96);
        $this->_FontTavatekst = TCPDF_FONTS::addTTFfont(LPath . '/pdf/arialnarrow.ttf', 'TrueTypeUnicode', '', 96);
        $this->_FontMuu = TCPDF_FONTS::addTTFfont(LPath . '/pdf/goudy.ttf', 'TrueTypeUnicode', '', 96);
        $this->_FontMuuB = TCPDF_FONTS::addTTFfont(LPath . '/pdf/goudybi.ttf', 'TrueTypeUnicode', '', 96);
    }
    
    //Page header
    public function Header() {
        // Logo
        $this->SetFont($this->_FontMuuB, '', 10);
        $this->Cell(0, 5,'', 0, 1);        // Title
        $this->Cell(30, 10, ($this->title), 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $this->Cell(60, 10, ($this->_KeskTitle), 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Cell(0, 10, ('Väljatrükk: ' . date('d.m.Y')), 0, false, 'R', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-10);
        // Set font
        $this->SetMuuFont();
        // Page number
        $this->Cell(0, 10, 'Lk ' . $this->getAliasNumPage().' / '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
    
    public function SetTavaFont()
    {
        $this->SetFont($this->_FontTavatekst, '', 9);
    }

    public function SetMuuFont()
    {
        $this->SetFont($this->_FontMuu, '', 10);
    }
    
    public function SetPealFont()
    {
        $this->SetFont($this->_FontPealkiri, '', 14);
    }
    
}