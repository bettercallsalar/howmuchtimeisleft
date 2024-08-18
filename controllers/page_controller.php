<?php

/**
 * Controller des pages de contenu
 */
class Page_Ctrl extends Ctrl
{
    public function contact()
    {
        $this->renderPage('contact', "Contact", "Contact us");
    }

    public function mentions()
    {
        $this->renderPage('mentions', "Legal notice", "Legal notice");
    }

    public function about()
    {
        if (!$_SESSION['user']) {
            header("Location:index.php?ctrl=error&action=error_403");
        }

        $this->renderPage('about', "About", "About us");
    }
}
