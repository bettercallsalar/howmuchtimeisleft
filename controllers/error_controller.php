<?php

class Error_Ctrl extends Ctrl
{

    public function error_404()
    {
        $this->_arrData['strPage']        = "error_404";
        $this->_arrData['strTitleH1']    = "404";
        $this->_arrData['strFirstP']    = "Page d'erreur 404";

        $this->render('error_404');
    }

    public function error_403()
    {
        $this->_arrData['strPage']        = "error_403";
        $this->_arrData['strTitleH1']    = "403";
        $this->_arrData['strFirstP']    = "Page d'erreur 403";

        $this->render('error_403');
    }
}
