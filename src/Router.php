<?php
namespace PHPico;

class Router {

    /**
     * Get the base from any possible base
     * return string
     */
    private function base()
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
    public function execute($routes)
    {
        $result = null;
        foreach($routes as $regex => $route){
            if(is_string($route)){
                $result = $this->request($regex, $route);
            } else if(is_array($route)){
                foreach($route as $conf){
                    if($conf === $_SERVER['REQUEST_METHOD']){
                        $result = $this->request($regex, end($route));
                    }
                }
            }
            if($result !== false) return $result;
        }
        return false;
    }
    
    /**
     * Forge the request for this regex and the route params. Could be a callable.
     * @var $regex string The regex
     * @var $route string|callable The route to be executed
     * return string|boolean The result
     */
    private function request($regex, $route){
        if(preg_match('/^'.$regex.'$/', $this->base(), $matches)){
            if(is_callable($route)){
                return call_user_func_array($route, $matches);
            }
            
            array_shift($matches);
            
            list($class, $method) = explode('@', $route);
            
            $method = !preg_match('/\@/', $route) ? 'index' : $method ;
            $class = class_exists($class) ? $class : $route;

            $c = new $class();
            return call_user_func_array(array($c, $method), $matches);
        }
        return false;
    }
}
