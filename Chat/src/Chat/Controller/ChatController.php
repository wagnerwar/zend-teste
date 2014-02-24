<?php 
namespace Chat\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

use Chat\Form\UsuarioForm;
use Chat\Model\Usuario;
use Chat\Form\ChatForm;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

use Zend\Validator;

class ChatController extends AbstractActionController{
	
	function indexAction(){
		$db = $this->instanciaUsuarioTable();
		$form = new UsuarioForm("logar");
	
		$usuario = new Usuario();
		$request = $this->getRequest();
		$session = new Container();
		
		if(!empty($session->usuario)){
			// Lista usuários
			$usuarios = $db->fetchAll();
		
			return new ViewModel( array('usuarios' => $usuarios ,'session' => $session )  );
		}else{
			if($request->isPost()){
				// Cadastrando
				$id = substr(rand(1000,2000).time(),0,19);
				
				$dados = $request->getPost()->set('id_usuario',$id);

				$form->setInputFilter($usuario->getInputFilter());
				$form->setData($dados);
				
				if($form->isValid()){
					$info = $form->getData();
					$db->insere($info);
		
					$this->flashMessenger()->addMessage("Erro no cadastro");
					$session->usuario = $id;
					// Lista usuários
					$usuarios = $db->fetchAll();
					//return new ViewModel( array('usuarios' => $usuarios,'session' => $session) );
					$resposta = array();
					$resposta['status'] = "OK";
					$resposta['erros'] = array();
					$this->layout('layout/json');
					$json = json_encode($resposta);
					$view = new ViewModel(array('resposta' => $json));
					$view->setTemplate('layout/resposta_json');
					
					return $view;
				}else{
	
				/*	$this->flashMessenger()->addMessage("Erro de validação");
					$view = new ViewModel(array( 'form' => $form,'messages' => $this->flashMessenger()->getMessages() ));
					$view->setTemplate("chat/chat/login");
					return $view;	*/
					$resposta = array();
					$resposta['status'] = "NOK";
					$resposta['erros'] = $form->getMessages();
					$this->layout('layout/json');
					$json = json_encode($resposta);
					$view = new ViewModel(array('resposta' => $json));
					$view->setTemplate('layout/resposta_json');
					return $view;
				}
			}else{
				// Exibindo formulário
				$view = new ViewModel( array( 'form' => $form, 'messages' => $this->flashMessenger()->getMessages() ) );
				$view->setTemplate("chat/chat/login");
				return $view;
			}
		}
	}
	
	public function instanciaUsuarioTable(){
		$sm = $this->getServiceLocator();
		$o = $sm->get('Usuario\Model\UsuarioTable');
		return $o;
	}
	
	public function chatAction(){
		$sm = $this->getServiceLocator();
		$db = $sm->get('ChatTable');
		$db_user = $this->instanciaUsuarioTable();
		$id = $this->params()->fromRoute('id',0);
		$session = new Container();
		$m = $db->getChat($session->usuario,$id);
		$form = new ChatForm();
		$eu = $db_user->getUsuario($session->usuario);
		$outro = $db_user->getUsuario($id);
		$messages = $this->flashMessenger()->getMessages();
		return new ViewModel( array('messages' => $messages,'mensagens' => $m,'form' => $form,'eu' => $eu,'outro' => $outro ) );
	}
	public function chatJsonAction(){
		$sm = $this->getServiceLocator();
		$db = $sm->get('ChatTable');
		$db_user = $this->instanciaUsuarioTable();
		$id = $this->params()->fromRoute('id',0);
		$session = new Container();
		$m = $db->getChat($session->usuario,$id);
		$form = new ChatForm();
		$eu = $db_user->getUsuario($session->usuario);
		$outro = $db_user->getUsuario($id);
		$messages = $this->flashMessenger()->getMessages();
		$resposta = array();
		$x = 0;
		foreach($m as $ms){
			$resposta[$x] = array();
			$resposta[$x]['usuario_remetente'] = ($ms['id_usuario_remetente'] == $eu->id_usuario ? $eu->apelido : $outro->apelido);
			$resposta[$x]['usuario_destinatario'] = ($ms['id_usuario_destinatario'] == $eu->id_usuario ? $eu->apelido : $outro->apelido);
			$resposta[$x]['mensagem'] = $ms['mensagem'];
			$resposta[$x]['data_envio'] = $ms['data_envio'];
			$x++;
		}
		$this->layout('layout/json');
		$json = json_encode($resposta);
		$view = new ViewModel(array('resposta' => $json));
		$view->setTemplate('layout/resposta_json');
		#sleep(4);
		return $view;	
	}
	
	public function deslogarAction(){
		$db = $this->instanciaUsuarioTable();
		$session = new Container();
		
		$db->desloga($session->usuario);
		
		if(isset($session->usuario)){
			unset($session->usuario);
		}
		
		$this->flashMessenger()->clearMessages();
		$this->redirect()->toRoute("chat",array('action' => 'index'));
	}

	
	public function mensagemAction(){
		$sm = $this->getServiceLocator();
		$db = $sm->get('ChatTable');
		$db_user = $this->instanciaUsuarioTable();
		
		$form = new ChatForm();
		$request = $this->getRequest();
		if($request->isPost()){
			$campo_remetente = new Input('id_usuario_remetente');
			$campo_remetente->getFilterChain()->attachByName('int');
			#$campo_remetente->getValidatorChain()->addValidator(new Zend\Validator\Int(array('locale' => 'br')));
			
			$campo_destinatario = new Input('id_usuario_destinatario');
			$campo_destinatario->getFilterChain()->attachByName('int');
			#$campo_destinatario->getValidatorChain()->addValidator(new Zend\Validator\Int(array('locale' => 'br')));
			
			$campo_mensagem = new Input('mensagem');
			$campo_mensagem->getValidatorChain()->addValidator(new Validator\StringLength(array('min' => 3,'max' => 255)) );
			
			$data = $request->getPost();
			
			$input_filter = new InputFilter();
			$input_filter->add($campo_destinatario)->add($campo_remetente)->add($campo_mensagem);
			
			$form->setInputFilter($input_filter);
			$form->setData($data);
			
			if($form->isValid()){
				$this->flashMessenger()->addMessage("Sucesso no envio");
				$info = $form->getData();
				
				$db->enviaMensagem($info);
				
				//$this->redirect()->toRoute("chat",array('action' => 'chat','id' => $data['id_usuario_destinatario']));				
				$resposta = array();
				$resposta['status'] = "OK";
				$resposta['erros'] = array();
				$dados_outro = $db_user->getUsuario($form->getData()['id_usuario_destinatario']);
				$dados_eu = $db_user->getUsuario($form->getData()['id_usuario_remetente']);
				
				$resposta['mensagem'] = array();
				$resposta['mensagem']['usuario_remetente'] = $dados_eu->apelido;
				$resposta['mensagem']['usuario_destinatario'] = $dados_outro->apelido;
				$resposta['mensagem']['mensagem'] = $form->getData()['mensagem'];
				
				$this->layout('layout/json');
				$json = json_encode($resposta);
				$view = new ViewModel(array('resposta' => $json));
				$view->setTemplate('layout/resposta_json');
				return $view;
				
			}else{
				//$this->flashMessenger()->addMessage("Erro no envio");
				//return new ViewModel(array('form' => $form));
				#$this->redirect()->toRoute("chat",array('action' => 'index'));
				$resposta['status'] = "NOK";
				$resposta['erros'] = $form->getMessages();
				
				$this->layout('layout/json');
				$json = json_encode($resposta);
				$view = new ViewModel(array('resposta' => $json));
				$view->setTemplate('layout/resposta_json');
				return $view;
			}
		}
	}
	
}