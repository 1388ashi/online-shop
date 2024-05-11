<?php
return [
    'name' => 'Order',

    'drivers' => [
        'zarinpal' => [
            'label' => 'زرین پال',
            'image' => asset('assets/images/drivers/zarinpal.png'),
            'options' => [
                'transaction_id' => 'Authority'
            ]
        ]
    ]
];
