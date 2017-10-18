<?php

namespace Ambta\DoctrineEncryptBundle\Encryptors;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Class for encrypting and decrypting with the defuse library
 *
 * @author Michael de Groot <specamps@gmail.com>
 */
class DefuseEncryptor implements EncryptorInterface
{
    protected $storeInDir;
    protected $fileName;
    protected $encryptionKey;
    protected $fs;
    protected $fullStorePath;

    /**
     * {@inheritdoc}
     */
    public function __construct($projectRoot)
    {
        $this->encryptionKey = null;
        $this->storeInDir = $projectRoot;
        $this->fileName = '.'.(new \ReflectionClass($this))->getShortName().'.key';
        $this->fullStorePath = $this->storeInDir.$this->fileName;
        $this->fs = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($data)
    {
        return \Defuse\Crypto\Crypto::encryptWithPassword($data, $this->getKey()).'<ENC>';
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($data)
    {
        return \Defuse\Crypto\Crypto::decryptWithPassword($data, $this->getKey());
    }

    private function getKey()
    {
        if ($this->encryptionKey === null) {
            if ($this->fs->exists($this->fullStorePath)) {
                $this->encryptionKey = file_get_contents($this->fullStorePath);
            } else {
                $this->encryptionKey = $this->generateRandomString();
                $this->fs->dumpFile($this->fullStorePath, $this->encryptionKey);
            }
        }

        return $this->encryptionKey;
    }

    /**
     * Creates a CSPRNG from paragonie/random_compat
     *
     * @param int $length length of bytes
     *
     * @return string
     */
    public function generateRandomString($length = 256)
    {
        try {
            $string = random_bytes($length);
        } catch (TypeError $e) {
            die('An unexpected error has occurred with random_bytes');
        } catch (Error $e) {
            die('An unexpected error has occurredwith random_bytes');
        } catch (Exception $e) {
            // If you get this message, the CSPRNG failed hard.
            die('Could not generate a random string. Is our OS secure?');
        }

        return bin2hex($string);
    }
}
