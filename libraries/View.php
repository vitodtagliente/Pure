<?php

namespace Cooco;

require_once __DIR__ .'/ViewScriptEngine.php';

/*
    HOW TO:




*/

class View
{
    // The view's parameters
    protected $vars = array();

    public function __construct( $args = array() ) {
        $this->set( $args );
    }

    public function __get ( $index ) {
        return $this->vars[$index];
    }

    public function __set( $index, $value ){
		$this->vars[$index] = $value;
	}

    public function set( $args = array() ){
        if( is_array( $args ) )
            foreach ($args as $key => $value) {
                $this->vars[$key] = $value;
            }
    }

    public function clear(){
        unset( $this->vars );
        $this->vars = array();
    }

    public function render( $filename, $direct_output = true ){
        if( file_exists( $filename ) == false ) {
            return false;
        }

        extract( $this->vars );
        ob_start();
        include( $filename );
        $content = ob_get_contents();
		ob_end_clean();

        $engine = new ViewScriptEngine();
        $content = $engine->map( $content );

		if($direct_output)
        	echo $content;
		else return $content;
    }

    // begin static section

	private static $path;

	private static $engine;

	public static function init( $folder ){
		self::path( $folder );
	}

    public static function path($path = null){
        if(isset($path))
            self::$path = $path;
        else return self::$path;
    }

    public static function make( $filename, $params = array(), $direct_output = true ){
        $view = new View( $params );
        return $view->render( self::$path . "/$filename", $direct_output );

    }

    // end
    public function __destruct(){}
}

?>
