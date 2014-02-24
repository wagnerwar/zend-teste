<?php
return array(
	'controllers' => 
		array(
			'invokables' => array(
			'Chat\Controller\Chat' => 'Chat\Controller\ChatController',	
			)
		),
		'view_manager' => array(
			'template_path_stack' => array(
            	'chat' => __DIR__ . '/../view',
        	),
		'display_not_found_reason' => true,
        	'display_exceptions'       => true,
        	'doctype'                  => 'HTML5',
        	'not_found_template'       => 'error/404',
        	'exception_template'       => 'error/index',
      		'template_map' => array(
            		'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            		'chat/chat/index' => __DIR__ . '/../view/chat/chat/index.phtml',
            		'error/404'               => __DIR__ . '/../view/error/index.phtml',
            		'error/index'             => __DIR__ . '/../view/error/index.phtml',
        	      	'chat/chat/login' => __DIR__ . '/../view/chat/chat/login.phtml',
        	      	'layout/json' => __DIR__ . '/../view/layout/json.phtml',
        	      	'layout/resposta_json' => __DIR__ . '/../view/layout/resposta_json.phtml',
        	
        	
        	),
        	
       	),
		'router' => array(
			'routes' => array(
				'chat' => array(
					'type' => 'segment',
					'options' => array(
						'route' => '/chat[/:action][/:id]',
						'constraints' => array(
							'action' => '[a-zA-Z]*',
							'id' => '[0-9a-zA-Z]+'
						),
					 	'defaults' => array(
							'controller' => 'Chat\Controller\Chat',
							'action' => 'index'
						)
					)		
				),
			),
		),
);

/*
 * Configuração de base de dados
 * CREATE TABLE `usuario_chat` (
  `id_usuario` bigint(20) NOT NULL,
  `apelido` varchar(20) NOT NULL,
  `sexo` enum('M','F') DEFAULT 'M',
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1




CREATE TABLE `chat` (
  `id_usuario_remetente` bigint(20) NOT NULL,
  `id_usuario_destinatario` bigint(20) NOT NULL,
  `mensagem` varchar(255) NOT NULL,
  `data_envio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `id_usuario_remetente` (`id_usuario_remetente`,`id_usuario_destinatario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
 * 
 * */