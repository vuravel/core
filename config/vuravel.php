<?php

return [

	'default_date_format' => env('DEFAULT_DATE_FORMAT', 'Y-m-d'),

	'files_attributes' => [
		'name' => 'name',
		'path' => 'path',
		'mime_type' => 'mime_type',
		'size' => 'size',
		'id' => 'id' //not used when files are relationships => the model's primary key is used
	],

    'locales' => [
        //'en' => 'English',
        //'fr' => 'Français'
    ],
];
