<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
	'navigation' => array(
		'default' => array(
			array(
				'label' => 'Home',
				'route' => 'home',
			) ,
			array(
				'label' => 'Audio',
				'route' => 'audio',
				'pages' => array(
					array(
						'label' => 'Index',
						'route' => 'audio',
					) ,
					array(
						'label' => 'Audioindb',
						'route' => 'audio/default',
						'controller' => 'audio',
						'action' => 'audioindb',
					) ,
					array(
						'label' => 'Upload',
						'route' => 'audio/default',
						'controller' => 'Audio',
						'action' => 'upload',
					) ,
					array(
						'label' => 'Bar',
						'route' => 'audio/default',
						'controller' => 'index',
						'action' => 'bar',
					) ,
				) ,
			) ,
			array(
				'label' => 'Ffmpeg',
				'route' => 'ffmpeg',
			) ,
			array(
				'label' => 'System',
				'route' => 'system',
			) ,
		) ,
		'foo' => array(
			'label' => 'Foo',
			'route' => 'application/default',
			'controller' => 'index',
			'action' => 'foo',
		) ,
		'bar' => array(
			'label' => 'Bar',
			'route' => 'application/default',
			'controller' => 'index',
			'action' => 'bar',
		) ,
	) ,
	'service_manager' => array(
		'factories' => array(
			'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
		) ,
	) ,
	// ...
	
);
