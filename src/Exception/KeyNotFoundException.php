<?php

/*
 * This file is part of the Collection project.
 *
 * (c) YoSymfony <http://github.com/yosymfony>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yosymfony\Collection\Exception;

class KeyNotFoundException extends \RuntimeException
{
    /** @var mixed */
    private $key;

    /**
     * Constructor.
     *
     * @param string $message The error message.
     * @param mixed $key The key.
     * @param Exception $previous The previous exception.
     */
    public function __construct(string $message, $key, \Exception $previous = null)
    {
        $this->key = $key;
        parent::__construct($message, 0, $previous);
    }

    /**
     * Returns the key.
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }
}
