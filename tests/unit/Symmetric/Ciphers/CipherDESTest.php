<?php
/**
 * Sikker is a PHP 7.0+ Security package that contains security related implementations.
 *
 * @package    NorseBlue\Sikker
 * @version    0.3.8
 * @author     NorseBlue
 * @license    MIT License
 * @copyright  2016 NorseBlue
 * @link       https://github.com/NorseBlue/Sikker
 */
declare(strict_types = 1);

namespace NorseBlue\Sikker\Tests\Symmetric\Ciphers;

use Codeception\Specify;
use Codeception\Test\Unit;
use InvalidArgumentException;
use NorseBlue\Sikker\Symmetric\CipherMethod;
use NorseBlue\Sikker\Symmetric\Ciphers\CipherDES;

class CipherDESTest extends Unit
{
    use Specify;

    /**
     * @var string Helper payload to encrypt.
     */
    const PAYLOAD = 'You know nothing Jon Snow! Winter is coming!';

    /**
     * @var string Helper password to encrypt.
     */
    const PASSWORD = 'The White Wolf';

    /**
     * @var string The password as hex string.
     */
    const PASSWORD_HEX = '54686520576869746520576F6c66';

    /**
     * @var string An initialization vector.
     */
    const IV = 'Ygritte';

    /**
     * @var string The payload encrypted with DES and the password.
     */
    const ENCRYPTED_PAYLOAD_DES_BASE64 = '2IEnaFqUzKWM7bIU+wxP+gKnP5Cnlyczc8M0QdBZy4DCXqj5WLkPbvb8EX1W0DnU';

    /**
     * @var string The payload encrypted with DES, the password and the IV.
     */
    const ENCRYPTED_PAYLOAD_DES_BASE64_IV = 'eJMcJPPrlUfs4x4kU8sCY3RJhpW8+YknBL3kukoD56e4nHhfOyDoE5hSkZuV1QL4';

    /**
     * @var string The payload encrypted with DES-EDE and the password.
     */
    const ENCRYPTED_PAYLOAD_DES3_2K_BASE64 = 'xvR2NwlcraAkhKC4g+96UlNkHMNuH7XXB5ic1q6rytj8RPidMOD2f2Llrchaxlg5';

    /**
     * @var string The payload encrypted with DES-EDE, the password and the IV.
     */
    const ENCRYPTED_PAYLOAD_DES3_2K_BASE64_IV = 'KbhJv39XAzCVT3kDTcaBzrj/rdQ7YkrYe9B8quHLQ3CTzinPlkbljNCuk4w7J4Hj';

    /**
     * @var string The payload encrypted with DES-EDE3 and the password.
     */
    const ENCRYPTED_PAYLOAD_DES3_3K_BASE64 = 'D0yh2ZxV3j23pFY82CpSR8sS+ATVFkHKeCCRhGnnLXtY3hxroZm7VAPzSxeML7Jb';

    /**
     * @var string The payload encrypted with DES-EDE3, the password and the IV.
     */
    const ENCRYPTED_PAYLOAD_DES3_3K_BASE64_IV = '+TbIi6QM4VHYsnR7sFj9WAIILSnjCDwQBHPhk0JHR8Hf/x+p96wXWR5bDq6KP2Ev';

    /**
     * @var string The payload encrypted with DESX and the password.
     */
    const ENCRYPTED_PAYLOAD_DESX_BASE64 = 'CJ8TB0cGyHlpKV99YvvYRVjTKGtucDnav+ODPKsRNNe/QrFQBEyyxhQWRGYhTGcp';

    /**
     * @var string The payload encrypted with DESX, the password and the IV.
     */
    const ENCRYPTED_PAYLOAD_DESX_BASE64_IV = '/5Dn9tPg2BgzbrvfsPRziDDxItaokKQ2vkl4Rc5OE1Lnsd2osjA3Wvd/TQXrl99f';

    protected function _after()
    {
    }

    protected function _before()
    {
    }

    // tests

    /**
     * Test incorrect method
     */
    public function testIncorrectMethod()
    {
        $this->specify('Throws exception about incorrect method.', function () {
            $this->expectException(InvalidArgumentException::class);
            $cipher = new CipherDES(998);
        });
    }

    /**
     * Test incorrect mode
     */
    public function testIncorrectMode()
    {
        $this->specify('Throws exception about incorrect mode.', function () {
            $this->expectException(InvalidArgumentException::class);
            $cipher = new CipherDES(CipherDES::METHOD_SIMPLE, '', 0, 998);
        });
    }

    /**
     * Tests the type names
     */
    public function testEncryptionDecryption()
    {
        $this->specify('Encrypts and decrypts the payload with DES', function () {
            if (extension_loaded('openssl') && CipherMethod::isAvailable(CipherMethod::DES)) {
                $cipher = new CipherDES(CipherDES::METHOD_SIMPLE);
                $encrypted = $cipher->encrypt(self::PAYLOAD, self::PASSWORD);
                $this->assertInternalType('array', $encrypted);
                $this->assertEquals(5, count($encrypted));
                $this->assertEquals(self::ENCRYPTED_PAYLOAD_DES_BASE64, $encrypted[0]);
                $this->assertEquals(strtolower(self::PASSWORD_HEX), strtolower($encrypted[1]));
                $this->assertEquals(0, $encrypted[2]);
                $this->assertEquals('', $encrypted[3]);

                $decrypted = $cipher->decrypt(self::ENCRYPTED_PAYLOAD_DES_BASE64, self::PASSWORD);
                $this->assertEquals(self::PAYLOAD, $decrypted);
            }
        });

        $this->specify('Encrypts and decrypts the payload with DES and an IV.', function () {
            if (extension_loaded('openssl') && CipherMethod::isAvailable(CipherMethod::DES)) {
                $cipher = new CipherDES(CipherDES::METHOD_SIMPLE, self::IV);
                $encrypted = $cipher->encrypt(self::PAYLOAD, self::PASSWORD);
                $this->assertInternalType('array', $encrypted);
                $this->assertEquals(5, count($encrypted));
                $this->assertEquals(self::ENCRYPTED_PAYLOAD_DES_BASE64_IV, $encrypted[0]);
                $this->assertEquals(strtolower(self::PASSWORD_HEX), strtolower($encrypted[1]));
                $this->assertEquals(0, $encrypted[2]);
                $this->assertEquals(self::IV, $encrypted[3]);

                $decrypted = $cipher->decrypt(self::ENCRYPTED_PAYLOAD_DES_BASE64_IV, self::PASSWORD);
                $this->assertEquals(self::PAYLOAD, $decrypted);
            }
        });

        $this->specify('Encrypts and decrypts the payload with DES-EDE', function () {
            if (extension_loaded('openssl') && CipherMethod::isAvailable(CipherMethod::DES3_2K)) {
                $cipher = new CipherDES(CipherDES::METHOD_TRIPLE_2KEY);
                $encrypted = $cipher->encrypt(self::PAYLOAD, self::PASSWORD);
                $this->assertInternalType('array', $encrypted);
                $this->assertEquals(5, count($encrypted));
                $this->assertEquals(self::ENCRYPTED_PAYLOAD_DES3_2K_BASE64, $encrypted[0]);
                $this->assertEquals(strtolower(self::PASSWORD_HEX), strtolower($encrypted[1]));
                $this->assertEquals(0, $encrypted[2]);
                $this->assertEquals('', $encrypted[3]);

                $decrypted = $cipher->decrypt(self::ENCRYPTED_PAYLOAD_DES3_2K_BASE64, self::PASSWORD);
                $this->assertEquals(self::PAYLOAD, $decrypted);
            }
        });

        $this->specify('Encrypts and decrypts the payload with DES-EDE and an IV.', function () {
            if (extension_loaded('openssl') && CipherMethod::isAvailable(CipherMethod::DES3_2K)) {
                $cipher = new CipherDES(CipherDES::METHOD_TRIPLE_2KEY, self::IV);
                $encrypted = $cipher->encrypt(self::PAYLOAD, self::PASSWORD);
                $this->assertInternalType('array', $encrypted);
                $this->assertEquals(5, count($encrypted));
                $this->assertEquals(self::ENCRYPTED_PAYLOAD_DES3_2K_BASE64_IV, $encrypted[0]);
                $this->assertEquals(strtolower(self::PASSWORD_HEX), strtolower($encrypted[1]));
                $this->assertEquals(0, $encrypted[2]);
                $this->assertEquals(self::IV, $encrypted[3]);

                $decrypted = $cipher->decrypt(self::ENCRYPTED_PAYLOAD_DES3_2K_BASE64_IV, self::PASSWORD);
                $this->assertEquals(self::PAYLOAD, $decrypted);
            }
        });

        $this->specify('Encrypts and decrypts the payload with DES-EDE3', function () {
            if (extension_loaded('openssl') && CipherMethod::isAvailable(CipherMethod::DES3_3K)) {
                $cipher = new CipherDES(CipherDES::METHOD_TRIPLE_3KEY);
                $encrypted = $cipher->encrypt(self::PAYLOAD, self::PASSWORD);
                $this->assertInternalType('array', $encrypted);
                $this->assertEquals(5, count($encrypted));
                $this->assertEquals(self::ENCRYPTED_PAYLOAD_DES3_3K_BASE64, $encrypted[0]);
                $this->assertEquals(strtolower(self::PASSWORD_HEX), strtolower($encrypted[1]));
                $this->assertEquals(0, $encrypted[2]);
                $this->assertEquals('', $encrypted[3]);

                $decrypted = $cipher->decrypt(self::ENCRYPTED_PAYLOAD_DES3_3K_BASE64, self::PASSWORD);
                $this->assertEquals(self::PAYLOAD, $decrypted);
            }
        });

        $this->specify('Encrypts and decrypts the payload with DES-EDE3 and an IV.', function () {
            if (extension_loaded('openssl') && CipherMethod::isAvailable(CipherMethod::DES3_3K)) {
                $cipher = new CipherDES(CipherDES::METHOD_TRIPLE_3KEY, self::IV);
                $encrypted = $cipher->encrypt(self::PAYLOAD, self::PASSWORD);
                $this->assertInternalType('array', $encrypted);
                $this->assertEquals(5, count($encrypted));
                $this->assertEquals(self::ENCRYPTED_PAYLOAD_DES3_3K_BASE64_IV, $encrypted[0]);
                $this->assertEquals(strtolower(self::PASSWORD_HEX), strtolower($encrypted[1]));
                $this->assertEquals(0, $encrypted[2]);
                $this->assertEquals(self::IV, $encrypted[3]);

                $decrypted = $cipher->decrypt(self::ENCRYPTED_PAYLOAD_DES3_3K_BASE64_IV, self::PASSWORD);
                $this->assertEquals(self::PAYLOAD, $decrypted);
            }
        });

        $this->specify('Encrypts and decrypts the payload with DESX', function () {
            if (extension_loaded('openssl') && CipherMethod::isAvailable(CipherMethod::DESX)) {
                $cipher = new CipherDES(CipherDES::METHOD_X);
                $encrypted = $cipher->encrypt(self::PAYLOAD, self::PASSWORD);
                $this->assertInternalType('array', $encrypted);
                $this->assertEquals(5, count($encrypted));
                $this->assertEquals(self::ENCRYPTED_PAYLOAD_DESX_BASE64, $encrypted[0]);
                $this->assertEquals(strtolower(self::PASSWORD_HEX), strtolower($encrypted[1]));
                $this->assertEquals(0, $encrypted[2]);
                $this->assertEquals('', $encrypted[3]);

                $decrypted = $cipher->decrypt(self::ENCRYPTED_PAYLOAD_DESX_BASE64, self::PASSWORD);
                $this->assertEquals(self::PAYLOAD, $decrypted);
            }
        });

        $this->specify('Encrypts and decrypts the payload with DESX and an IV.', function () {
            if (extension_loaded('openssl') && CipherMethod::isAvailable(CipherMethod::DESX)) {
                $cipher = new CipherDES(CipherDES::METHOD_X, self::IV);
                $encrypted = $cipher->encrypt(self::PAYLOAD, self::PASSWORD);
                $this->assertInternalType('array', $encrypted);
                $this->assertEquals(5, count($encrypted));
                $this->assertEquals(self::ENCRYPTED_PAYLOAD_DESX_BASE64_IV, $encrypted[0]);
                $this->assertEquals(strtolower(self::PASSWORD_HEX), strtolower($encrypted[1]));
                $this->assertEquals(0, $encrypted[2]);
                $this->assertEquals(self::IV, $encrypted[3]);

                $decrypted = $cipher->decrypt(self::ENCRYPTED_PAYLOAD_DESX_BASE64_IV, self::PASSWORD);
                $this->assertEquals(self::PAYLOAD, $decrypted);
            }
        });
    }
}
