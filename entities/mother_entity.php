<?php

class Entity
{
    protected  string $_prefixe;
    protected int $_id;

    public function hydrate($arrData)
    {
        foreach ($arrData as $key => $value) {
            $strSetter  = "set" . ucfirst(str_replace($this->_prefixe, "", $key));
            if (method_exists($this, $strSetter)) {
                $this->$strSetter($value);
            }
        }
    }

    public function setId(int $intId)
    {
        $this->_id = $intId;
    }

    public function getId()
    {
        return $this->_id;
    }
}
