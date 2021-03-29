<?php
    class  Validacion{

        /**
         * Valida si el número es flotante y devuelve un mensaje correspondiente al caso.
         * valor: es el argumento numérico.
         * nombre_argumento: es el nombre del argumento que aparece en el mensaje.
         * return array()
         */
        public function es_decimal($valor,$nommbre_argumento){
            if (strpos($valor, ',') !== false) {
                $mensaje = "El $nommbre_argumento no lleva coma y se debe escribir con punto para los decimales. Ej: 1.23\n";        
                return array('exito'=>false,'mensaje'=>$mensaje);
            }else{
                if(substr_count($valor, '.')>1){
                    $mensaje = "El $nommbre_argumento no debe tener mas de dos puntos\n";   
                    return array('exito'=>false,'mensaje'=>$mensaje);        
                }else
                {
                    $valor = floatval($valor);
                    if($valor == 0){
                        $mensaje = "El $nommbre_argumento debe ser numérico. Ej: 20 o 1.23\n";     
                        return array('exito'=>false,'mensaje'=>$mensaje);          
                    }
                }
                
            }
            $mensaje = "Éxito\n";     
            return array('exito'=>true,'mensaje'=>$mensaje); 
        }

        /**
         * Valida si el número es entero y devuelve un mensaje correspondiente al caso.
         * valor: es el argumento numérico.
         * nombre_argumento: es el nombre del argumento que aparece en el mensaje.
         * return array()
         */
        public function es_entero($valor,$nommbre_argumento){
            if (strpos($valor, ',') !== false) {
                $mensaje = "El $nommbre_argumento no lleva coma. Ej: 1 o 2\n";        
                return array('exito'=>false,'mensaje'=>$mensaje);
            }else{
                if(substr_count($valor, '.')>0){
                    $mensaje = "El $nommbre_argumento no debe tener puntos\n";   
                    return array('exito'=>false,'mensaje'=>$mensaje);        
                }else
                {
                    $valor = intval($valor);
                    if($valor == 0){
                        $mensaje = "El $nommbre_argumento debe ser numérico. Ej: 1 o 2\n";     
                        return array('exito'=>false,'mensaje'=>$mensaje);          
                    }
                }
                
            }
            $mensaje = "Éxito\n";     
            return array('exito'=>true,'mensaje'=>$mensaje); 
        }
    }
?>