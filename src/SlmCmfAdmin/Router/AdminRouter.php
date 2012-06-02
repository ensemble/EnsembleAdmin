<?php

/*
 * This is free and unencumbered software released into the public domain.
 * 
 * Anyone is free to copy, modify, publish, use, compile, sell, or
 * distribute this software, either in source code form or as a compiled
 * binary, for any purpose, commercial or non-commercial, and by any
 * means.
 * 
 * In jurisdictions that recognize copyright laws, the author or authors
 * of this software dedicate any and all copyright interest in the
 * software to the public domain. We make this dedication for the benefit
 * of the public at large and to the detriment of our heirs and
 * successors. We intend this dedication to be an overt act of
 * relinquishment in perpetuity of all present and future rights to this
 * software under copyright law.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 * 
 * For more information, please refer to <http://unlicense.org/>
 * 
 * @category
 * @package
 * @copyright  Copyright (c) 2009-2011 Soflomo (http://www.soflomo.com)
 * @license    http://unlicense.org Unlicense
 */

namespace SlmCmfAdmin\Router;

use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Stdlib\RequestInterface as Request;

use SlmCmfAdmin\Exception\RouteNotFoundException;

class AdminRouter extends TreeRouteStack
{
    /**
     * Routes for all modules
     * 
     * This property can't be called routes as this will overwrite the 
     * routes property from the Zend\Mvc\Router\SimpleRouteStack
     * 
     * @var array
     */
    protected $moduleRoutes;
    
    /**
     * @var string
     */
    protected $module;
    
    public function __construct(array $routes)
    {
        $this->moduleRoutes = $routes;
        parent::__construct();
    }
    
    public function setModule($module)
    {
        $this->module = $module;
        
        return $this;
    }
    
    public function match(Request $request)
    {
        if (null === $this->module) {
            return null;
        }
        
        $this->addRoutesForModule($this->module);
        return parent::match($request);
    }
    
    protected function addRoutesForModule($module)
    {
        if (!isset($this->moduleRoutes[$module])) {
            throw new RouteNotFoundException(sprintf(
                'Cannot find routes in list for module %s',
                $module
            ));
        }
        
        $route = $this->moduleRoutes[$module];
        $this->addRoute($module, $route);
    }
}
