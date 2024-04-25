<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Custom Web Applications
     |--------------------------------------------------------------------------
     | TODO
     */
    'webapps' => [],

    /*
     |--------------------------------------------------------------------------
     | Web Applications Blacklist
     |--------------------------------------------------------------------------
     | TODO
     */
    'webapps_blacklist' => [],

    /*
     |--------------------------------------------------------------------------
     | Custom Features
     |--------------------------------------------------------------------------
     | This configuration allows you to define custom API Features.
     |
     | Features facilitate simpler management and standardisation of endpoints.
     | These routes can be registered via Gildsmith::registerFeatureRoutes()
     | and must be explicitly enabled either programmatically in vendor packages
     | or through this configuration setting.
     */
    'features' => [],

    /*
     |--------------------------------------------------------------------------
     | Features Blacklist
     |--------------------------------------------------------------------------
     | Define the features you wish to disable in the array below. This includes
     | all features, even those registered by third-party composer packages.
     |
     | Use this setting to temporarily disable specific functionalities, either
     | for troubleshooting, or to simulate behaviour when certain APIs are
     | unavailable.
     */
    'features_blacklist' => [],

    /*
     |--------------------------------------------------------------------------
     | Enable Silent Features
     |--------------------------------------------------------------------------
     | This configuration option controls the server response when a feature is
     | accessed but disabled. By default, if an endpoint is correctly registered
     | but the associated feature is disabled, a 503 HTTP status code with a
     | JSON error message is returned, indicating the service is unavailable.
     |
     | Setting this option to true changes the behavior to return a 404 HTTP
     | status code instead, as if the feature never existed. This can be useful
     | for hiding disabled features from end users.
     */
    'silent_features' => false,
];
