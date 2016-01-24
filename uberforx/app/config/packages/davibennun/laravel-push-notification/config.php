<?php

return array(

    'ios_provider_sandbox'     => array(
        'environment' =>'development',
        'certificate' =>app_path() . '/ios_push/iph_cert/ckUberForXProvider.pem',
        'passPhrase'  =>'123456',
        'service'     =>'apns'
    ),

	'ios_client_sandbox'     => array(
		'environment' =>'development',
		'certificate' =>app_path() . '/ios_push/iph_cert/ckUberForXOwner.pem',
		'passPhrase'  =>'123456',
		'service'     =>'apns'
	),

	'ios_provider_prod'     => array(
		'environment' =>'production',
		'certificate' =>'/path/to/certificate.pem',
		'passPhrase'  =>'password',
		'service'     =>'apns'
	),

	'ios_client_prod'     => array(
		'environment' =>'production',
		'certificate' =>'/path/to/certificate.pem',
		'passPhrase'  =>'password',
		'service'     =>'apns'
	),

    'android_provider' => array(
        'environment' =>'production',
        'apiKey'      =>'AIzaSyCJIlMyKQ2NcN3lMPxRIal-4BTM0UUW6RA',
        'service'     =>'gcm'
    ),

	'android_client' => array(
		'environment' =>'production',
		'apiKey'      =>'AIzaSyCJIlMyKQ2NcN3lMPxRIal-4BTM0UUW6RA',
		'service'     =>'gcm'
	),

);