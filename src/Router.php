<?php

namespace PHPico;

class Router
{
    /**
     * Get the base from any possible url.
     *
     * @return string
     */
    public function base()
    {
        $b = \strtr($_SERVER['SCRIPT_NAME'], ['index.php' => '']);

        return \strtr($_SERVER['REQUEST_URI'], [$b => '/']);
    }

    /**
     * Executes an array of routes.
     *
     * Should be properly formatted:
     *  ['regex' => 'class@method']
     *  ['regex' => ['GET','class@method']]
     *
     * By default index() method will be taken.
     * No need of /^...$/ inside of regex.
     * Returns false if route not found.
     *
     * @param  $routes array The routes array
     *
     * @return string|false
     */
    public function dispatch($routes)
    {
        $result = null;

        foreach ($routes as $regex => $callable){
            if (false !== $result = $this->execute($regex, $callable)) {
                return $result;
            }
        }

        return false;
    }

    /**
     * Executes the request this regex and the route params. Could be a callable.
     *
     * @param $regex string          The regex
     * @param $route string|callable The route to be executed
     *
     * @return string|boolean The result
     */
    public function execute($regex, $callable)
    {
        if (\is_array($callable)) {
            foreach (\end($callable) as $conf) {
                if ($conf !== $_SERVER['REQUEST_METHOD']) {
                    return false;
                }
            }
        }

        $regex = \str_replace('/', '\/', $regex);

        return $this->handle($regex, $callable);
    }

    protected function handle($regex, $callable)
    {
        if (!\preg_match('/^' . $regex . '$/', $this->base(), $matches)) {
            return false;
        }

        \array_shift($matches);

        if (\is_callable($callable)) {
            return \call_user_func_array($callable, $matches);
        }

        list($class, $method) = \explode('@', $callable, 2) + ['', 'index'];

        $class = \class_exists($class) ? $class : $callable;

        return \call_user_func_array([new $class(), $method], $matches);
    }
}
