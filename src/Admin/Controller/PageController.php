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

namespace Ensemble\Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;

use Ensemble\Kernel\Service\Page as PageService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Router\RouteMatch;

use Ensemble\Admin\Exception;

/**
 * PageController
 *
 * @package    Ensemble\Admin
 * @subpackage Controller
 * @author     Jurian Sluiman <jurian@soflomo.com>
 */
class PageController extends AbstractActionController
{
    /**
     * @var PageService
     */
    protected $service;

    public function openAction ()
    {
        $page    = $this->getPage();
        $params  = '/' . trim($this->params('params', ''), '/');
        $request = new Request;
        $request->getUri()->setPath($params);

        $router     = $this->getServiceLocator()->get('Ensemble\Admin\Router\AdminRouter');

        try {
            // If the module has not registered a route, we get a RouteNotFoundException
            // It means this page is not accessible through the admin, so render a message for that

            $routeMatch = $router->setModule($page->getModule())
                                 ->match($request);
        } catch (Exception\RouteNotFoundException $e) {
            return new ViewModel(array(
                'page' => $page
            ));
        }

        if (!$routeMatch instanceof RouteMatch) {
            throw new Exception\RouteMatchNotFoundException(sprintf(
                'No route found with url part "%s"',
                $params
            ));
        }

        /*
         * Temporarily solution, quick fix
         */
        $view = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $helper = $view->plugin('AdminUrl');
        $helper->setAdminRouter($router);
        $helper->setAdminRouteMatch($routeMatch);
        /*
         * End quick fix
         */

        $controller = $routeMatch->getParam('controller');
        $params     = $routeMatch->getParams() + array('page' => $page);

        return $this->forward()->dispatch($controller, $params);
    }

    public function createAction ()
    {
    }

    public function editAction ()
    {
    }

    public function deleteAction ()
    {
    }

    protected function getService()
    {
        if (null === $this->service) {
            $this->service = $this->getServiceLocator()->get('Ensemble\Kernel\Service\Page');
        }

        return $this->service;
    }

    protected function getPage()
    {
        $pageId     = $this->params('id');
        $page       = $this->getService()->find($pageId);

        if (null === $page) {
            throw new Exception\PageNotFoundException(sprintf(
                'Cannot open page with id %s',
                $pageId
            ));
        }

        return $page;
    }

    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager();
        $this->getEventManager()->addIdentifiers('Ensemble\Admin');
    }
}
