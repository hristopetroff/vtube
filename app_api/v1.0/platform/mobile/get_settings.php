<?php


if (!PT_IsAdmin()) {
	$config = array_intersect_key($config, array_flip($site_public_data));
}

$response_data       = array(
    'api_status'     => '200',
    'api_version'    => $api_version,
    'data'           => array(
        'site_settings'  => $config,
        'categories'     => $categories
    )
);
