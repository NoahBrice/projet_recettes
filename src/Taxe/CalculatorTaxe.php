<?php

namespace App\Taxe;

class CalculatorTaxe
{

    public function calculerTVA(float $prixHT): float
    {
        $TVA = $prixHT * 0.2;
        return $TVA;
    }

    public function calculerTTC(float $prixHT): float
    {

        $prixTTC = $prixHT * 1.2;
        return $prixTTC;
    }
}
