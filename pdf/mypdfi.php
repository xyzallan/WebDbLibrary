<?php

LoadLibrary("Libs.tcpdf.tcpdf_import", RPath);


class MYPDF extends TCPDF_IMPORT {

    public $_FontTavatekst;
    public $_FontPealkiri;
    public $_FontMuu;
    public $_FontMuuB;

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
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
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