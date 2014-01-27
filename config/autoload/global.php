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
				'label' => 'Quiz',
				'route' => 'quiz',
				'pages' => array(
					array(
						'label' => 'Index',
						'route' => 'quiz',
						'controller' => 'quiz',
						'action' => 'index',
						) ,
					array(
						'label' => 'user',
						'route' => 'quiz',
						'controller' => 'quiz',
						'action' => 'user',
						) ,
					array(
						'label' => 'upload',
						'route' => 'quiz',
						'controller' => 'quiz',
						'action' => 'upload',
						) ,
					array(
						'label' => 'link',
						'route' => 'quiz',
						'controller' => 'quiz',
						'action' => 'link',
						) ,
					array(
						'label' => 'listlink',
						'route' => 'quiz',
						'controller' => 'quiz',
						'action' => 'listlink',
						) ,
					array(
						'label' => 'displaylinks',
						'route' => 'quiz',
						'controller' => 'quiz',
						'action' => 'displaylinks',
						) ,
					array(
						'label' => 'deletelink',
						'route' => 'quiz',
						'controller' => 'quiz',
						'action' => 'deletelink',
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
						'label' => 'upload',
						'route' => 'snoopy',
						'controller' => 'snoopy',
						'action' => 'upload',
						) ,
					array(
						'label' => 'fetchlisten',
						'route' => 'snoopy',
						'controller' => 'snoopy',
						'action' => 'fetchlisten',
						) ,
					array(
						'label' => 'fetchcontent',
						'route' => 'snoopy',
						'controller' => 'snoopy',
						'action' => 'fetchcontent',
						) ,
					array(
						'label' => 'displaylinks',
						'route' => 'snoopy',
						'controller' => 'snoopy',
						'action' => 'displaylinks',
						) ,
					array(
						'label' => 'deletelink',
						'route' => 'snoopy',
						'controller' => 'snoopy',
						'action' => 'deletelink',
						) ,
					) ,
) ,
array(
	'label' => 'Audio',
	'route' => 'audio',
	'pages' => array(
		array(
			'label' => 'Index',
			'route' => 'audio',
			'controller' => 'audio',
			'action' => 'index',
			) ,
		array(
			'label' => 'fetchlinks',
			'route' => 'audio',
			'controller' => 'audio',
			'action' => 'fetchlinks',
			) ,
		array(
			'label' => 'upload',
			'route' => 'audio',
			'controller' => 'audio',
			'action' => 'upload',
			) ,
		array(
			'label' => 'fetchlisten',
			'route' => 'audio',
			'controller' => 'audio',
			'action' => 'fetchlisten',
			) ,
		array(
			'label' => 'fetchcontent',
			'route' => 'audio',
			'controller' => 'audio',
			'action' => 'fetchcontent',
			) ,
		array(
			'label' => 'displaylinks',
			'route' => 'audio',
			'controller' => 'audio',
			'action' => 'displaylinks',
			) ,
		array(
			'label' => 'deletelink',
			'route' => 'audio',
			'controller' => 'audio',
			'action' => 'deletelink',
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
