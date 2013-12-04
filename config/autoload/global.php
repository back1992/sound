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
						'label' => 'Audioindb',
						'route' => 'audio',
						'controller' => 'audio',
						'action' => 'audioindb',
						) ,
					array(
						'label' => 'Upload',
						'route' => 'audio',
						'controller' => 'audio',
						'action' => 'upload',
						) ,
					array(
						'label' => 'Fetch',
						'route' => 'audio',
						'controller' => 'audio',
						'action' => 'fetch',
						) ,
					array(
						'label' => 'Update',
						'route' => 'audio',
						'controller' => 'audio',
						'action' => 'update',
						) ,
					) ,
				) ,
			array(
				'label' => 'Ffmpeg',
				'route' => 'ffmpeg',
				'pages' => array(
					array(
						'label' => 'Index',
						'route' => 'ffmpeg',
						'controller' => 'ffmpeg',
						'action' => 'index',
						) ,
					array(
						'label' => 'Audioindb',
						'route' => 'ffmpeg',
						'controller' => 'ffmpeg',
						'action' => 'ffmpegindb',
						) ,
					array(
						'label' => 'Read log',
						'route' => 'ffmpeg',
						'controller' => 'ffmpeg',
						'action' => 'readlog',
						) ,
					array(
						'label' => 'Scan dir',
						'route' => 'ffmpeg',
						'controller' => 'ffmpeg',
						'action' => 'scan',
						) ,
					array(
						'label' => 'Edit',
						'route' => 'ffmpeg',
						'controller' => 'ffmpeg',
						'action' => 'edit',
						) ,
					) ,
				) ,
			array(
				'label' => 'System',
				'route' => 'system',
				'pages' => array(
					array(
						'label' => 'phpinfo',
						'route' => 'system',
						'controller' => 'system',
						'action' => 'phpinfo',
						) ,
					array(
						'label' => 'PHPMpeg',
						'route' => 'system',
						'controller' => 'system',
						'action' => 'pffmpeg',
						) ,
					array(
						'label' => 'Format',
						'route' => 'system',
						'controller' => 'system',
						'action' => 'format',
						) ,
					),
				) ,
			array(
				'label' => 'Tools',
				'route' => 'tools',
				'pages' => array(
					array(
						'label' => 'Index',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'index',
						) ,
					array(
						'label' => 'PHPMpeg',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'pffmpeg',
						) ,
					array(
						'label' => 'Format',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'format',
						) ,
					array(
						'label' => 'Edit',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'edit',
						) ,
					array(
						'label' => 'Log splt',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'logsplt',
						) ,
					) ,
				) ,
			) ,
) ,
'service_manager' => array(
	'factories' => array(
		'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
		) ,
	) ,
	// ...

);
