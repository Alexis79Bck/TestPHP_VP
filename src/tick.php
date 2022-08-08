<?php

namespace App;

class tick
{
    public function tick(Item $item)
    {
        switch ($this->name) {
            case 'Pisco Peruano':
                //Si el valor de quality se encuentra entre 0 y 50
                if ($this->quality > 0 && $this->quality < 50) {
                    if ($this->sellIn > 0) {
                        $this->quality = $this->quality + 1;
                    } else {
                        $this->quality = $this->quality == 49 ? $this->quality + 1 : $this->quality + 2;
                    }
                }
                //selIn disminuye en 1
                $this->sellIn = $this->sellIn - 1;
                break;

            case 'Ticket VIP al concierto de Pick Floid':
                //Si el valor de quality se encuentra entre 0 y 50
                if ($this->quality > 0 && $this->quality < 50) {
                    if ($this->sellIn > 10) {
                        $this->quality = $this->quality + 1;
                    } else {
                        if ($this->sellIn > 6) {
                            //Tomando en cuenta la premisa:
                            // - Los "Tickets VIP", así como "Pisco Peruano", incrementan su Quality conforme
                            // su SellIn se acerca a 0, el Quality incrementa en 2 cuando faltan 10 días o menos
                            // y en 3 cuando faltan 5 días o menos, pero el Quality disminuye a 0 después del concierto.

                            //Si el valor de quality es igual a 49 se incrementa en 1, de lo cotrario
                            //su valor sera incrementado en 2

                            $this->quality = $this->quality + 2;
                        } else {
                            if ($this->sellIn > 0) {
                                //Si el valor de quality es menor a 48 se incrementa en 3, de lo cotrario
                                //Si el valor de quality es igual a 48 se incrementa en 2, de lo cotrario
                                //su valor sera incrementado en 1
                                if ($this->quality < 48) {
                                    $this->quality = $this->quality + 3;
                                } elseif ($this->quality == 48) {
                                    $this->quality = $this->quality + 2;
                                } else {
                                    $this->quality = $this->quality + 1;
                                }
                            } else {

                                $this->quality = 0;
                            }
                        }
                    }
                }
                //selIn disminuye en 1
                $this->sellIn = $this->sellIn - 1;
                break;

            case 'Tumi de Oro Moche':
                //Tomando en cuenta la premisa:
                // - Los productos "Tumi", siendo un producto legendario,
                // nunca debe ser vendido o bajaría su Quality.
                // - Para dejarlo claro, un producto nunca puede incrementar su Quality mayor a 50,
                // sin embargo "Tumi" es un producto legendario y como tal su Quality es 80 y nunca cambia.

                // El valor de sellIn no sufren cambios, pero el quality siempre sera 80 y nunca cambia
                //$this->quality = 80;

                break;

            case 'Café Altocusco':
                //Tomando en cuenta la premisa:
                // - Los productos de "Café" se degradan en Quality el doble
                // que los productos normales
                if ($this->quality > 0 && $this->quality < 50) {
                    if ($this->sellIn > 0) {
                        $this->quality = $this->quality - 2;
                    } else {
                        $this->quality = $this->quality - 4;
                    }
                }


                $this->sellIn = $this->sellIn - 1;
                break;

            case 'normal':
                //Tomando en cuenta la premisa:
                // - Al final de cada día, nuestro sistema disminuye los ambos valores para cada producto,
                // - Cuando la fecha de venta ha pasado, el Quality se degrada dos veces más rápido
                if ($this->quality > 0) {
                    if ($this->sellIn > 0) {
                        $this->quality = $this->quality - 1;
                    } else {
                        $this->quality = $this->quality - 2;
                    }
                }
                $this->sellIn = $this->sellIn - 1;
                break;
        }
    }

}
