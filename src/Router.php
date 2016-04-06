<?php
namespace PHPico;

class Router {

    /**
     * Get the base from any possible url
     * return string
     */
    public function base()
    {
        $b = strtr($_SERVER['SCRIPT_NAME'], ['index.php'=>'']);
        return strtr($_SERVER['REQUEST_URI'], [$b => '/']);
    }
    
    /**
     * Executes an array of routes. Should be properly formatted:
     * ['regex'=>'class@method']
     * ['regex'=> ['GET','class@method']]
     * By default index() method will be taken.
     * No need of /^...$/ inside of regex.
     * Returns false if route not found.
     * 
     * @var $routes array The routes array
     * @return string|false
     */
    public function dispatch($routes)
    {
        $result = null;
        foreach($routes as $regex => $callable){
            $result = $this->execute($regex, $callable);
            if($result !== false) return $result;
        }
        return false;
    }
    
    /**
     * Executes the request this regex and the route params. Could be a callable.
     * @var $regex string The regex
     * @var $route string|callable The route to be executed
     * return string|boolean The result
     */
    public function execute($regex, $callable)
    {
        if(is_array($callable)){
            $callable = end($callable);
            foreach($callable as $conf){
                if($conf !== $_SERVER['REQUEST_METHOD']) return false;
            }
        }
        
        if(preg_match('/^'.$regex.'$/', $this->base(), $matches)){
            
            array_shift($matches);
            
            if(is_callable($callable)){
                return call_user_func_array($callable, $matches);
            }
            
            list($class, $method) = explode('@', $callable);
            
            $method = !preg_match('/\@/', $callable) ? 'index' : $method ;
            $class = class_exists($class) ? $class : $callable;

            $c = new $class();
            return call_user_func_array(array($c, $method), $matches);
        }
        return false;
    }
}
