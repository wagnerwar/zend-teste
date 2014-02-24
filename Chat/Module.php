<?php
namespace Chat;


use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Chat\Model\Usuario;
use Chat\Model\UsuarioTable;
use Chat\Model\ChatTable;
use Chat\Form\UsuarioForm;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Usuario\Model\UsuarioTable' =>  function($sm) {
                    $tableGateway = $sm->get('UsuarioTableGateway');
                    $table = new UsuarioTable($tableGateway);
                    return $table;
                },
                'UsuarioTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Usuario());
                    return new TableGateway('usuario_chat', $dbAdapter, null, $resultSetPrototype);
                },
				'ChatTable' => function($sm){
                	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                	return new ChatTable($dbAdapter);
				}
            ),
        );
    }
}
