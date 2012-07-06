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

namespace Ensemble\Admin\View\Helper;

use Zend\View\Helper\Url;
use Ensemble\Admin\Router\AdminRouter;
use Zend\Mvc\Router\RouteMatch;

use Zend\View\Exception;

/**
 * Extend url view helper to provide easy acces to subroutes of pages
 *
 * The name of all page routes start with the id of the page. As this is an
 * unknown parameter for the view, this view helper prepends the root part
 * of the route. This happpens when the route name argument starts with a /.
 *
 * Therefore common route names like "user" and "admin" will work, but for a
 * special module Foo with a subroute "view-article", the route name /view-article
 * will be transformed to $id/view-article to match the appropriate page.
 *
 * @package    Ensemble\Admin
 * @subpackage View
 * @author     Jurian Sluiman <jurian@soflomo.com>
 */
class AdminUrl extends Url
{
    /**
     * AdminRouter instance.
     *
     * @var AdminRouter
     */
    protected $adminRouter;

    /**
     * RouteInterface match returned by the router.
     *
     * @var RouteMatch.
     */
    protected $adminRouteMatch;

    /**
     * Set the router to use for assembling.
     *
     * @param AdminRouter $router
     * @return self
     */
    public function setAdminRouter(AdminRouter $router)
    {
        $this->adminRouter = $router;
        return $this;
    }

    /**
     * Set route match returned by the admin router.
     *
     * @param  RouteMatch $routeMatch
     * @return self
     */
    public function setAdminRouteMatch(RouteMatch $routeMatch)
    {
        $this->adminRouteMatch = $routeMatch;
        return $this;
    }

    /**
     * Generates an url given the name of a route.
     *
     * @see    Zend\Mvc\Router\RouteInterface::assemble()
     * @param  string  $name               Name of the route
     * @param  array   $params             Parameters for the link
     * @param  array   $options            Options for the route
     * @param  boolean $reuseMatchedParams Whether to reuse matched parameters
     * @return string Url                  For the link href attribute
     * @throws Exception\RuntimeException  If no RouteStackInterface was provided
     * @throws Exception\RuntimeException  If no RouteMatch was provided
     * @throws Exception\RuntimeException  If RouteMatch didn't contain a matched route name
     */
    public function __invoke($name = null, array $params = array(), array $options = array(), $reuseMatchedParams = false)
    {
        if (null === $this->adminRouter) {
            throw new Exception\RuntimeException('No AdminRouter instance provided');
        }

        if ($name === null) {
            if ($this->adminRouteMatch === null) {
                throw new Exception\RuntimeException('No admin RouteMatch instance provided');
            }

            $name = $this->adminRouteMatch->getMatchedRouteName();

            if ($name === null) {
                throw new Exception\RuntimeException('Admin RouteMatch does not contain a matched route name');
            }
        }

        if ($reuseMatchedParams && $this->routeMatch !== null) {
            $params = array_merge($this->routeMatch->getParams(), $params);
        }

        $options['name'] = $name;
        $params = $this->adminRouter->assemble($params, $options);

        if ('/' === $params) {
            return $this->getView()->url('admin/page/open', array(), array(), true);
        } else {
            return $this->getView()->url('admin/page/open/params', array('params' => $params), array(), true);
        }
    }
}
