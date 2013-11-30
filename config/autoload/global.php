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
						'label' => 'Upload',
						'route' => 'ffmpeg',
						'controller' => 'ffmpeg',
						'action' => 'upload',
						) ,
					array(
						'label' => 'Fetch',
						'route' => 'ffmpeg',
						'controller' => 'ffmpeg',
						'action' => 'fetch',
						) ,
					array(
						'label' => 'Update',
						'route' => 'ffmpeg',
						'controller' => 'ffmpeg',
						'action' => 'update',
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
						'label' => 'Convert',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'convert',
						) ,
					array(
						'label' => 'Update',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'update',
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
