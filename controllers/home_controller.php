<?php

class Home_Ctrl extends Ctrl
{
    public function index()
    {
        $this->_arrData['strPage'] = "Home";
        $this->_arrData['strTitleH1'] = "How Much Time Is Left?";
        $this->_arrData['strFirstP'] = "Welcome to How Much Time Is Left?. This website is designed to help you determine how much time you have left to live based on your date of birth and the average life expectancy in your country. Simply enter your date of birth and we will calculate the time you have left in seconds, minutes, hours, days, weeks, months, and years. We will also show you a visual representation of the average life expectancy in your country. If you create an account, you can save your date of birth and country so you can quickly see how much time you have left each time you visit. Sign up today and start tracking your time!";
        $this->render('index_view');
    }
}
