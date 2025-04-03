<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingCharge;
use App\Models\Country;

class ShippingChargeSeeder extends Seeder
{
    public function run()
    {
        $shippingCosts = [
            'HN' => 30000, 'HCM' => 35000, 'HP' => 25000, 'DN' => 28000, 'CT' => 27000,
            'AG' => 29000, 'BRVT' => 31000, 'BG' => 26000, 'BK' => 27000, 'BL' => 32000,
            'BN' => 26000, 'BT' => 28000, 'BD' => 30000, 'BDU' => 31000, 'BP' => 29000,
            'BTH' => 27000, 'CM' => 32000, 'CB' => 25000, 'DL' => 31000, 'DN' => 29000,
            'DB' => 26000, 'DNA' => 28000, 'DT' => 30000, 'GL' => 29000, 'HG' => 27000,
            'HNA' => 26000, 'HT' => 29000, 'HD' => 28000, 'HG' => 27000, 'HB' => 25000,
            'HY' => 26000, 'KH' => 30000, 'KG' => 31000, 'KT' => 27000, 'LC' => 25000,
            'LD' => 29000, 'LS' => 26000, 'LCA' => 27000, 'LA' => 28000, 'ND' => 27000,
            'NA' => 29000, 'NB' => 26000, 'NT' => 28000, 'PT' => 27000, 'PY' => 30000,
            'QB' => 27000, 'QNA' => 29000, 'QN' => 28000, 'QNIN' => 26000, 'QT' => 27000,
            'ST' => 31000, 'SL' => 26000, 'TN' => 28000, 'TB' => 27000, 'TNIN' => 26000,
            'TH' => 29000, 'TTH' => 28000, 'TG' => 30000, 'TV' => 32000, 'TQ' => 27000,
            'VL' => 31000, 'VP' => 27000, 'YB' => 26000
        ];

        foreach ($shippingCosts as $code => $cost) {
            $country = Country::where('code', $code)->first();
            if ($country) {
                ShippingCharge::updateOrCreate(
                    ['country_id' => $country->id],
                    ['shipping_cost' => $cost]
                );
            }
        }
    }
}
