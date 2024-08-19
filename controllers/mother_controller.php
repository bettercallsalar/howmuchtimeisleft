<?php

class Ctrl
{
    protected array $_arrData = array();

    protected function render(string $strTemplate)
    {
        foreach ($this->_arrData as $key => $value) {
            $$key = $value;
        }

        include("views/_partial/header.php");
        include("views/" . $strTemplate . ".php");
        include("views/_partial/footer.php");
    }


    public function renderPage($page, $title, $firstP)
    {
        $this->_arrData['strPage'] = $page;
        $this->_arrData['strTitleH1'] = $title;
        $this->_arrData['strFirstP'] = $firstP;
        $this->render($page);
    }
}
