<?php

class Error_Ctrl extends Ctrl
{

    public function error_404()
    {
        $this->renderPage('error_404', "404", "Page not found");
    }

    public function error_403()
    {
        $this->renderPage('error_403', "403", "Access denied");
    }
}
