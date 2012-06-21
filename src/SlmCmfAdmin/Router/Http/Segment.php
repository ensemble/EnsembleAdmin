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
 * @copyright  Copyright (c) 2009-2011 Soflomo (http://www.soflomo.com)
 * @license    http://unlicense.org Unlicense
 */

namespace SlmCmfAdmin\Router\Http;

use Zend\Mvc\Router\Http\Segment as BaseRoute;


class Segment extends BaseRoute
{
    /**
     * {@inheritdoc}
     */
    protected function buildPath(array $parts, array $mergedParams, $isOptional, $hasChild, $urlencode = true)
    {
        $path      = '';
        $skip      = true;
        $skippable = false;

        foreach ($parts as $part) {
            switch ($part[0]) {
                case 'literal':
                    $path .= $part[1];
                    break;

                case 'parameter':
                    $skippable = true;

                    if (!isset($mergedParams[$part[1]])) {
                        if (!$isOptional || $hasChild) {
                            throw new Exception\InvalidArgumentException(sprintf('Missing parameter "%s"', $part[1]));
                        }

                        return '';
                    } elseif (!$isOptional || $hasChild || !isset($this->defaults[$part[1]]) || $this->defaults[$part[1]] !== $mergedParams[$part[1]]) {
                        $skip = false;
                    }

                    if($urlencode) {
                        $path .= urlencode($mergedParams[$part[1]]);
                    } else {
                        $path .= $mergedParams[$part[1]];
                    }

                    $this->assembledParams[] = $part[1];
                    break;

                case 'optional':
                    $skippable    = true;
                    $optionalPart = $this->buildPath($part[1], $mergedParams, true, $hasChild);

                    if ($optionalPart !== null) {
                        $path .= $optionalPart;
                        $skip  = false;
                    }
                    break;

                // @codeCoverageIgnoreStart
                case 'translated-literal':
                    throw new Exception\RuntimeException('Translated literals are not implemented yet');
                    break;

                case 'translated-parameter':
                    throw new Exception\RuntimeException('Translated parameters are not implemented yet');
                    break;
                // @codeCoverageIgnoreEnd
            }
        }

        if ($isOptional && $skippable && $skip) {
            return '';
        }

        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public function assemble(array $params = array(), array $options = array())
    {
        $this->assembledParams = array();

        return $this->buildPath(
            $this->parts,
            array_merge($this->defaults, $params),
            false,
            (isset($options['has_child']) ? $options['has_child'] : false),
            (!isset($options['urlencode']) || false !== $options['urlencode'])
        );
    }
}
