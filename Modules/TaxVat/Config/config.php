<?php

return [
    'name' => 'TaxVat',
    'project'=>'6ammart',
    'version' => '1.0.0',
    'pagination'=> 25,
    'country_type'=> 'single',


'6ammart' =>[
    'tax_claculate_from'=> ['Calculate Tax on Billing Address Location'],
    'tax_calculate_on'=>['order_wise','product_wise','category_wise'],
    'additional_tax'=>['tax_on_additional_charge','tax_on_packaging_charge'],
],
'6valle' =>[
    'tax_claculate_from'=> ['Calculate Tax on Billing Address Location','Calculate Tax on Shipping Address Location'],
    'tax_calculate_on'=>['order_wise','product_wise','category_wise'],
    'additional_tax'=>['tax_on_delivery_charge'],
]


];
