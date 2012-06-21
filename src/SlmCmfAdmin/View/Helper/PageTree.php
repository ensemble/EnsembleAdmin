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
 * @copyright  Copyright (c) 2009-2012 Soflomo (http://soflomo.com)
 * @license    http://unlicense.org Unlicense
 */

namespace SlmCmfAdmin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use SlmCmfKernel\Service\PageInterface as PageService;
use SlmCmfKernel\Model\PageInterface;
use SlmCmfKernel\Model\PageCollectionInterface;

/**
 * Description of PageTree
 */
class PageTree extends AbstractHelper
{
    /**
     * @var PageService
     */
    protected $service;
    
    public function setPageService(PageService $service)
    {
        $this->service = $service;
    }
    
    public function __invoke()
    {
        $pages = $this->service->getTree();
        $html  = $this->parseCollection($pages);
        
        return $html;
    }
    
    protected function parseCollection(PageCollectionInterface $collection)
    {
        $html = '<ul>';
        foreach($collection as $page) {
            $html .= $this->parsePage($page);
        }
        $html .= '</ul>';
        
        return $html;
    }
    
    protected function parsePage(PageInterface $page)
    {
        $title = $page->getMetaData()->getTitle();
        $url   = $this->getView()->url('admin/page-open', array(
            'id' => $page->getId())
        );
        
        $children = '';
        if ($page->hasChildren()) {
            $collection = $page->getChildren();
            $children   = $this->parseCollection($collection);
        }
        
        $html = sprintf('<li><a href="%s">%s</a>%s</li>',
                        $url,
                        $title,
                        $children);
        
        return $html;
    }
}