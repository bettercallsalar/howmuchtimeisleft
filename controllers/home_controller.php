<?php

class Home_Ctrl extends Ctrl
{
    public function index()
    {
        $this->_arrData['strPage'] = "Home";
        $this->_arrData['strTitleH1'] = "How Much Time Is Left?";
        $this->_arrData['strFirstP'] = "";
        $this->render('index_view');
    }
}
