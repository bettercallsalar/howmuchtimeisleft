<?php

/**
 * Classe mère des entités
 */

class Entity
{
    protected string $_prefixe = '';
    protected ?int $_id = null;  // Making the ID nullable

    public function __construct() {}

    public function hydrate($arrData)
    {
        foreach ($arrData as $key => $value) {
            $strSetter = "set" . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            if (method_exists($this, $strSetter)) {
                $this->$strSetter($value);
            } else {
                echo "No setter found for $key<br>";
            }
        }
    }

    public function setId(int $intId)
    {
        $this->_id = $intId;
    }

    public function getId(): ?int
    {
        return $this->_id;
    }
}
