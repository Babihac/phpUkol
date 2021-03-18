<?php

class EntryPoint
{
    private $route;
    private $routes;
    private $method;

    public function __construct(string $route, string $method, EmployeeRoutes $routes)
    {
        $this->route = $route;
        $this->method = $method;
        $this->routes = $routes;
    }

    private function loadTemplate(string $templateFileName, array $vars = []): string | false
    {
        extract($vars);
        ob_start();
        include __DIR__ . '/../templates/' . $templateFileName;
        return ob_get_clean();
    }

    public function run(): void
    {
        $routes = $this->routes->getRoutes();
        $controller = $routes[$this->route][$this->method]['controller'];
        $action = $routes[$this->route][$this->method]['action'];
        $page = $controller->$action();
        $title = $page["title"];

        if (isset($page['vars'])) {
            $output = $this->loadTemplate($page['template'], $page['vars']);
        } else {
            $output = $this->loadTemplate($page['template']);
        }

        echo $this->loadTemplate("layout.html.php", ["title" => $title,  "output" => $output]);
    }
}
