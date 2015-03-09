<?php

/*contindrà el diccionari de traducció */
class UsuarioVista{
	private $html_vista; //contindrà el codi html que es generi, per una vista determinada
	private $url_aplicacion = '/uf2/mvc/'; //contindrà la ruta relativa on son els codis de l’aplicació MVC 
	private	$diccionario;
	
	public function __construct(){
	 $this->diccionario = array(
			'subtitle'=>array(VIEW_SET_USER=>'Crear un nuevo usuario',
							  VIEW_GET_USER=>'Buscar usuario',
							  VIEW_DELETE_USER=>'Eliminar un usuario',
							  VIEW_EDIT_USER=>'Modificar usuario'
							 ),
			'links_menu'=>array(
				'VIEW_SET_USER'=>MODULO.VIEW_SET_USER.'/',
				'VIEW_GET_USER'=>MODULO.VIEW_GET_USER.'/',
				'VIEW_EDIT_USER'=>MODULO.VIEW_EDIT_USER.'/',
				'VIEW_DELETE_USER'=>MODULO.VIEW_DELETE_USER.'/'
			),
			'form_actions'=>array(
				'SET'=>$this->url_aplicacion.MODULO.SET_USER.'/',
				'GET'=>$this->url_aplicacion.MODULO.GET_USER.'/',
				'DELETE'=>$this->url_aplicacion.MODULO.DELETE_USER.'/',
				'EDIT'=>$this->url_aplicacion.MODULO.EDIT_USER.'/'
			)
		);
	}
	function get_template($form='get') {
		$file = '../site_media/html/user_'.$form.'.html';
		$template = file_get_contents($file); // Para traer la plantilla HTML y almacenar el contenido de ella, en la variable $template
		return $template;
	}

	function render_dinamic_data($html, $data) {
		foreach ($data as $clave=>$valor) {
			$html = str_replace('{'.$clave.'}', $valor, $html);
		}
		return $html;
	}
	
	function retornar_vista($vista, $data=array()){
		// $this->diccionario;
		$this->html_vista = $this->get_template('template');
		$this->html_vista = str_replace('{subtitulo}', $this->diccionario['subtitle'][$vista], $this->html_vista);
		$this->html_vista = str_replace('{formulario}', $this->get_template($vista), $this->html_vista);
		$this->html_vista = str_replace('{url_aplicacion}', $this->url_aplicacion, $this->html_vista); //Retorna la propietat url_aplicacion en els llocs de la ruta de user_template
		$this->html_vista =  $this->render_dinamic_data($this->html_vista, $this->diccionario['form_actions']);
		$this->html_vista =  $this->render_dinamic_data($this->html_vista, $this->diccionario['links_menu']);
		$this->html_vista =  $this->render_dinamic_data($this->html_vista, $data);

		// render {mensaje}
		if(array_key_exists('nombre', $data)&&
		   array_key_exists('apellido', $data)&&
		   $vista==VIEW_EDIT_USER) {
			$mensaje = 'Editar usuario '.$data['nombre'].' '.$data['apellido'];
		} else {
			if(array_key_exists('mensaje', $data)) {
				$mensaje = $data['mensaje'];
			} else {
				$mensaje = 'Datos del usuario:';
			}
		}
		$this->html_vista = str_replace('{mensaje}', $mensaje, $this->html_vista);
	}
	
	function mostrar_vista(){
		print $this->html_vista;
	}
	
	function set_url_aplicacion($url_aplicacion){
		$this->url_aplicacion = $url_aplicacion;			
	}

	# Método destructor del objeto
	function __destruct() {
		unset($this);
	}

}
?>
