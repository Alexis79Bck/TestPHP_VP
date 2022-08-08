<?php

namespace App;

class Item
{
    public $name;

    public $quality;

    public $sellIn;

    public function setItem($name, $quality, $sellIn)
    {
        $this->name = $name;
        $this->quality = $quality;
        $this->sellIn = $sellIn;
    }

}

