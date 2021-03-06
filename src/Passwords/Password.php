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

namespace NorseBlue\Sikker\Passwords;

use NorseBlue\Sikker\SaltShakers\SaltShaker;
use NorseBlue\Sikker\SaltShakers\SaltShakerSHA256;

/**
 * Class Password
 *
 * @package NorseBlue\Sikker\Passwords
 * @since 0.2
 */
class Password
{
    /**
     * @var string The plain password value.
     */
    protected $plain;

    /**
     * @var SaltShaker The salt shaker to use for hashing the passwords.
     */
    protected $saltShaker;

    /**
     * Password constructor.
     *
     * @param string $plain The plain password value.
     * @param SaltShaker $saltShaker The salt shaker to use for hashing the passwords.
     * @since 0.2
     */
    public function __construct(string $plain = null, SaltShaker $saltShaker = null)
    {
        $this->setPlain($plain);
        $this->setSaltShaker($saltShaker);
    }

    /**
     * Verifies that the given hash matches the given password.
     *
     * @param string $password The original password to verify.
     * @param string $hashedPwd The hashed password to verify against.
     * @return bool Returns true if the password and hash match, false otherwise.
     * @since 0.2
     */
    public static function verify(string $password, string $hashedPwd) : bool
    {
        return password_verify($password, $hashedPwd);
    }

    /**
     * Gets the plain password value.
     *
     * @return string
     * @since 0.3.8
     */
    public function getPlain() : string
    {
        return $this->plain;
    }

    /**
     * Sets the plain password value.
     *
     * @param string $plain The plain password value.
     * @since 0.3.8
     */
    public function setPlain(string $plain = null)
    {
        $this->plain = $plain ?? '';
    }

    /**
     * Gets the hashed password with the given salt using the current SaltShaker.
     *
     * @param string|null $salt The salt to use for hashing the password.
     * @return string Returns the hashed password.
     * @since 0.3.8
     */
    public function getHashed(string $salt = null) : string
    {
        return $this->hash($this->plain, $salt);
    }

    /**
     * Gets the Password SaltShaker.
     *
     * @return SaltShaker Returns the salt shaker being used for hashing the passwords.
     * @since 0.2
     */
    public function getSaltShaker() : SaltShaker
    {
        return $this->saltShaker;
    }

    /**
     * Sets the Password SaltShaker.
     *
     * @param SaltShaker|null $saltShaker The salt shaker to use for hashing the passwords.
     * @return Password Returns this instance for fluent interface.
     * @since 0.2
     */
    public function setSaltShaker(SaltShaker $saltShaker = null) : Password
    {
        $this->saltShaker = $saltShaker ?? new SaltShakerSHA256();

        return $this;
    }

    /**
     * Hashes the given password with the given salt using the current SaltShaker.
     *
     * @param string $password The password to hash.
     * @param string|null $salt The salt to use for hashing the password.
     * @return string Returns the hashed password.
     * @since 0.2
     */
    public function hash(string $password, string $salt = null) : string
    {
        $encodedSalt = $this->saltShaker->encode($salt);

        return crypt($password, $encodedSalt);
    }
}