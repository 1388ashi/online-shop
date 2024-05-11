<?php
use CyrildeWit\EloquentViewable\EloquentViewableServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    EloquentViewableServiceProvider::class,
    Shetabit\Payment\Provider\PaymentServiceProvider::class,
];

