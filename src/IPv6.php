<?php
namespace Boo\Address;

class IPv6 implements AddressInterface
{
    const MAX_PREFIX_LENGTH = 128;

    protected $address;

    /**
     * @param string $readable
     */
    public function __construct($readable)
    {
        if (((extension_loaded('sockets') and defined('AF_INET6')) or @inet_pton('::1')) === false) {
            throw new \RuntimeException('Your system does not support IPv6');
        }

        if (filter_var($readable, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            throw new InvalidAddressException($readable.' is not a valid IPv6 address');
        }

        $this->address = inet_pton($readable);
    }

    /**
     * @param string $readable
     * @return IPv6
     */
    public static function create($readable)
    {
        return new static($readable);
    }

    /**
     * {@inheritdoc}
     */
    public function equals(AddressInterface $address)
    {
        return $this->toReadable() === $address->toReadable();
    }

    /**
     * {@inheritdoc}
     */
    public function getMaxPrefixLength()
    {
        return static::MAX_PREFIX_LENGTH;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return static::VERSION_6;
    }

    /**
     * {@inheritdoc}
     */
    public function toReadable()
    {
        return inet_ntop($this->address);
    }

    public function __toString()
    {
        return $this->toReadable();
    }
}
