<?php

/**
 * Classe mère des entités
 */

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

    /**
     * Setter de l'identifiant
     * @param int $intId Identifiant
     * @return void
     */
    public function setId(int $intId)
    {
        $this->_id = $intId;
    }

    /**
     * Getter de l'id
     * @return int Identifiant de l'article
     */
    public function getId()
    {
        return $this->_id;
    }
}
