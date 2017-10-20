<?php
/**
 * Date: 18/10/17
 * Time: 10:47
 */

namespace Ambta\DoctrineEncryptBundle\Encryptors;


class EncryptorFactory
{
    /**
     * @param $encryptorClass
     * @return EncryptorInterface
     */
    public function create($encryptorClass)
    {
        if (class_exists(ucfirst($encryptorClass)) === false) {
            $encryptorClass = '\\Ambta\\DoctrineEncryptBundle\\Encryptors\\'.ucfirst($encryptorClass).'Encryptor';
        } else {
            $encryptorClass = ucfirst($encryptorClass);
        }

        $projectRoot = dirname(
                __FILE__
            ).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;;

        $encryptor = new $encryptorClass($projectRoot);
        $interfaces = class_implements($encryptor);

        if (isset($interfaces[EncryptorInterface::class])) {
            return $encryptor;
        } else {
            throw new \RuntimeException('Encryptor must implements interface EncryptorInterface');
        }
    }
}
