<?php

class Home_Ctrl extends Ctrl
{
    public function index()
    {
        $this->_arrData['strPage'] = "home";
        $this->_arrData['strTitleH1'] = "Home";
        $this->_arrData['strFirstP'] = "Welcome to the home page";
        $this->render('index_view');
    }
}
