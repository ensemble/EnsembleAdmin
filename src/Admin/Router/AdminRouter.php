<?php
/**
 * Copyright (c) 2012 Soflomo http://soflomo.com.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package     Ensemble\Admin
 * @author      Jurian Sluiman <jurian@soflomo.com>
 * @copyright   2012 Soflomo http://soflomo.com.
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://ensemble.github.com
 */

namespace Ensemble\Admin\Router;

use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Stdlib\RequestInterface as Request;

use Ensemble\Admin\Exception\RouteNotFoundException;

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
