<?php
function sanitize_output($buffer)
{
   
   /*
   $search = array(
        '/\>[^\S ]+/s', //strip whitespaces after tags, except space
        '/[^\S ]+\</s', //strip whitespaces before tags, except space
        '/(\s)+/s'  // shorten multiple whitespace sequences
        );
    $replace = array(
        '>',
        '<',
        '\\1'
        );
  $buffer = preg_replace($search, $replace, $buffer);
  */
   $buffer = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $buffer);
        /* remove tabs, spaces, newlines, etc. */
        $buffer = str_replace(array("\r\n","\r","\t","\n",'  ','    ','     '), '', $buffer);
        /* remove other spaces before/after ) */
        $buffer = preg_replace(array('(( )+\))','(\)( )+)'), ')', $buffer);
    return $buffer;
}
//ob_start("sanitize_output");
function kompres() {
	// Check if ob_gzhandler already loaded
	//if (ini_get('output_handler') == 'ob_gzhandler')
	//	return false;
		
	// Load HTTP Compression if correct extension is loaded
	//if (extension_loaded('zlib')) 
	//   if(!ob_start("ob_gzhandler")) ob_start();
	
	ob_start( 'sanitize_output' );
}
kompres();
?>