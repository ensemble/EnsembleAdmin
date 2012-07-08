<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Ensemble\Admin\View;

use Zend\Mvc\View\InjectTemplateListener as BaseListener;
use Zend\EventManager\EventManagerInterface as Events;
use Zend\Filter;
use Zend\Mvc\MvcEvent;

class InjectTemplateListener extends BaseListener
{
    /**
     * {@inheritdoc}
     */
    public function attach(Events $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'injectTemplate'), -100);
    }

    /**
     * {@inheritdoc}
     */
    public function injectTemplate(MvcEvent $e)
    {
        parent::injectTemplate($e);
    }

    /**
     * {@inheritdoc}
     */
    protected function inflectName($name)
    {
        if (!$this->inflector) {
            $this->inflector = new Filter\FilterChain;
            $this->inflector->attach(new Filter\Word\CamelCaseToDash())
                            ->attach(new Filter\Word\SeparatorToDash('\\'));
        }
        $name = $this->inflector->filter($name);
        return strtolower($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function deriveModuleNamespace($controller)
    {
        if (!strstr($controller, '\\')) {
            return '';
        }

        // Get the second namespace separator
        $pos    = strpos($controller, '\\', strpos($controller, '\\') + 1);
        $module = substr($controller, 0, $pos);
        return $module;
    }
}
