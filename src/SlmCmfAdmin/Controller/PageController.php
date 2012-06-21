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
 * @package    SlmCmfAdmin
 * @copyright  Copyright (c) 2009-2012 Soflomo (http://www.soflomo.com)
 * @license    http://unlicense.org Unlicense
 */

namespace SlmCmfAdmin\Controller;

use Zend\Mvc\Controller\ActionController;
use Zend\Mvc\MvcEvent;

use SlmCmfKernel\Service\Page as PageService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Router\RouteMatch;

use SlmCmfAdmin\Exception;

/**
 * PageController
 *
 * @package    SlmCmfAdmin
 * @subpackage Controller
 * @author     Jurian Sluiman <jurian@soflomo.com>
 */
class PageController extends ActionController
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
        $request->uri()->setPath($params);
        
        $router     = $this->getServiceLocator()->get('slmCmfAdminRouter');
        $routeMatch = $router->setModule($page->getModule())
                             ->match($request);
        
        if (!$routeMatch instanceof RouteMatch) {
            throw new Exception\RouteMatchNotFoundException(sprintf(
                'No route found with url part "%s"',
                $params
            ));
        }
        
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
            $this->service = $this->getServiceLocator()->get('SlmCmfKernel\Page\Service');
        }
        
        return $this->service;
    }
    
    protected function getPage()
    {
        $routeMatch = $this->event->getRouteMatch();
        $pageId     = $routeMatch->getParam('id');
        $page       = $this->getService()->getPage($pageId);
        
        if (null === $page) {
            throw new Exception\PageNotFoundException(sprintf(
                'Cannot open page with id %s',
                $pageId
            ));
        }
        
        return $page;
    }
}
