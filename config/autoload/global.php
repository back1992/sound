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
					array(
						'label' => 'hosts',
						'route' => 'system',
						'controller' => 'system',
						'action' => 'hosts',
						) ,
					array(
						'label' => 'urls',
						'route' => 'system',
						'controller' => 'system',
						'action' => 'urls',
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
						'label' => 'readdoc',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'readdoc',
						) ,
					array(
						'label' => 'readpdf',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'readpdf',
						) ,
					array(
						'label' => 'readquestion',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'readquestion',
						) ,
					array(
						'label' => 'savequestion',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'savequestion',
						) ,
					array(
						'label' => 'total',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'total',
						) ,
					array(
						'label' => 'scantotal',
						'route' => 'tools',
						'controller' => 'tools',
						'action' => 'scantotal',
						) ,
					) ,
				) ,
			array(
				'label' => 'Snoopy',
				'route' => 'snoopy',
				'pages' => array(
					array(
						'label' => 'Index',
						'route' => 'snoopy',
						'controller' => 'snoopy',
						'action' => 'index',
						) ,
					array(
						'label' => 'fetchlinks',
						'route' => 'snoopy',
						'controller' => 'snoopy',
						'action' => 'fetchlinks',
						) ,
					array(
						'label' => 'fetchtext',
						'route' => 'snoopy',
						'controller' => 'snoopy',
						'action' => 'fetchtext',
						) ,
					array(
						'label' => 'fetchlisten',
						'route' => 'snoopy',
						'controller' => 'snoopy',
						'action' => 'fetchlisten',
						) ,
					array(
						'label' => 'curl',
						'route' => 'snoopy',
						'controller' => 'snoopy',
						'action' => 'curl',
						) ,
					array(
						'label' => 'total',
						'route' => 'snoopy',
						'controller' => 'snoopy',
						'action' => 'total',
						) ,
					array(
						'label' => 'scantotal',
						'route' => 'snoopy',
						'controller' => 'snoopy',
						'action' => 'scantotal',
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
