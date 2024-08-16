<?php

/**
 * Controller des pages de contenu
 */
class Page_Ctrl extends Ctrl
{
    public function contact()
    {
        $this->_arrData['strPage']        = "contact";
        $this->_arrData['strTitleH1']    = "Contact";
        $this->_arrData['strFirstP']    = "Page de contact";

        $this->render('contact');
    }

    public function mentions()
    {
        $this->_arrData['strPage']        = "mentions";
        $this->_arrData['strTitleH1']    = "Mentions légales";
        $this->_arrData['strFirstP']    = "Page de contenu";

        $this->render('mentions');
    }

    public function about()
    {
        if (!$_SESSION['user']) {
            header("Location:index.php?ctrl=error&action=error_403");
        }
        $this->_arrData['strPage']        = "about";
        $this->_arrData['strTitleH1']    = "A propos";
        $this->_arrData['strFirstP']    = "Page de contenu";

        $this->render('about');
    }
}
