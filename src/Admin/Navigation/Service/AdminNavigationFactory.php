<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Navigation
 */

namespace Ensemble\Admin\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

/**
 * Default navigation factory.
 *
 * @category  Zend
 * @package   Zend_Navigation
 */
class AdminNavigationFactory extends DefaultNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin';
    }
}
