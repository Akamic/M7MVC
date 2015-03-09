<?php
require_once('constants.php');
require_once('model.php');
require_once('view.php');


class UsuarioController{
	private $event; //contindrà l’event que el browser ha generat.
	private $peticiones;//contindrà el conjunt de possibles peticions a realitzar
	
	function handler() {
		$this->get_event();
		$user_data = $this->helper_user_data();
		$usuario = new Usuario();
		$viewuser = new UsuarioVista();
		
		switch ($this->event) {
			case SET_USER:
				$usuario->set($user_data);
				$data = array('mensaje'=>$usuario->mensaje);
				$viewuser->retornar_vista(VIEW_SET_USER, $data);
				break;
			case GET_USER:
				$usuario->get($user_data);
				if($usuario->email != null) {
					$data = array(
						'nombre'=>$usuario->nombre,
						'apellido'=>$usuario->apellido,
						'email'=>$usuario->email
					);
					$viewuser->retornar_vista(VIEW_EDIT_USER, $data);
				} else {
					$data = array('mensaje'=>$usuario->mensaje);
					$viewuser->retornar_vista(VIEW_GET_USER, $data);
				}
				break;
			case DELETE_USER:
				$usuario->delete($user_data['email']);
				$data = array('mensaje'=>$usuario->mensaje);
				$viewuser->retornar_vista(VIEW_DELETE_USER, $data);
				break;
			case EDIT_USER:
				$usuario->edit($user_data);
				$data = array('mensaje'=>$usuario->mensaje);
				$viewuser->retornar_vista(VIEW_GET_USER, $data);
				break;
			default:
				$viewuser->retornar_vista($this->event);
		}
		$viewuser->mostrar_vista();
	}

	function helper_user_data() {
		$user_data = array();
		if($_POST) {
			if(array_key_exists('nombre', $_POST)) { 
				$user_data['nombre'] = $_POST['nombre']; 
			}
			if(array_key_exists('apellido', $_POST)) { 
				$user_data['apellido'] = $_POST['apellido']; 
			}
			if(array_key_exists('email', $_POST)) { 
				$user_data['email'] = $_POST['email']; 
			}
			if(array_key_exists('clave', $_POST)) { 
				$user_data['clave'] = $_POST['clave']; 
			}
		} else if($_GET) {
			if(array_key_exists('email', $_GET)) {
				$user_data = $_GET['email'];
			}
		}
		return $user_data;
	}
	/*Metode que retorna l'event seleccionat */
	private function get_event(){
		$this->event = VIEW_GET_USER;
		$uri = $_SERVER['REQUEST_URI']; // Agafa la url
		foreach ($this->peticiones as $peticion) {
			$uri_peticion = MODULO.$peticion.'/';
			if( strpos($uri, $uri_peticion) == true ) { // compara
				$this->event = $peticion;
			}
		}
	}
	
	# Método constructor
    function __construct() {
        $this->peticiones = array(SET_USER, GET_USER, DELETE_USER, EDIT_USER,
							VIEW_SET_USER, VIEW_GET_USER, VIEW_DELETE_USER, 
							VIEW_EDIT_USER);
    }

    # Método destructor del objeto
    function __destruct() {
        unset($this);
    }
}
$usercontroller = new UsuarioController();
$usercontroller->handler();

?>
