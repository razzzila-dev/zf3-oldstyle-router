<?php
namespace Razzzila\OldStyleRouter\Http;

use Zend\Router\Http\RouteMatch;
use Zend\Router\Http\Segment;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class OldStyle
 *
 * @package Razzzila\OldStyleRouter\Http
 */
class OldStyle extends Segment
{
    public function match(
        Request $request,
        $pathOffset = null,
        array $options = []
    ) {
        $parts = explode('/', trim(explode('?', $request->getRequestUri())[0], '/'));
        
        if (isset($parts[0])) {
            $controllerNamespace = isset($this->defaults->module)
                ? $this->defaults->module : 'Application';
            
            $upCasedName = ucfirst($parts[0]);
            $processedClassName
                = "${controllerNamespace}\\Controller\\${upCasedName}Controller";
            
            if (is_subclass_of(
                $processedClassName,
                AbstractActionController::class)
            ) {
                $actionAlias = isset($parts[1])
                    ? $parts[1] : (
                    isset($this->defaults->action)
                        ? $this->defaults->action : 'index'
                    );
                
                if (method_exists(
                    $processedClassName,
                    "${actionAlias}Action"
                )) {
                    $queryParams = $request->getQuery()->toArray();
                    
                    $queryArrayParams = $parts;
                    array_shift($queryArrayParams);
                    array_shift($queryArrayParams);
                    
                    while (
                        isset($queryArrayParams[0])
                        && isset($queryArrayParams[1])
                    ) {
                        $key = array_shift($queryArrayParams);
                        $queryParams[$key] = array_shift($queryArrayParams);
                    }
                    
                    $request->getQuery()->fromArray($queryParams);
                    
                    return new RouteMatch(
                        array_merge(
                            $this->defaults,
                            [
                                'controller_alias' => $parts[0],
                                'controller' => $processedClassName,
                                'action' => $actionAlias
                            ]
                        ),
                        strlen($request->getRequestUri())
                    );
                }
            }
        }
        
        return null;
    }
}
