<?php
/*
 * Copyright 2015 Soeezy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ambta\DoctrineEncryptBundle\Services;

class Encryptor
{
    /** @var \Ambta\DoctrineEncryptBundle\Encryptors\EncryptorInterface */
    protected $encryptor;

    public function __construct($encryptName, $key)
    {

        $reflectionClass = new \ReflectionClass($encryptName);
        $this->encryptor = $reflectionClass->newInstanceArgs();
    }

    public function getEncryptor()
    {
        return $this->encryptor;
    }

    public function decrypt($string)
    {
        return $this->encryptor->decrypt($string);
    }

    public function encrypt($string)
    {
        return $this->encryptor->encrypt($string);
    }
}
