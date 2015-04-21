<?php
namespace Boo\Address;

class IPv4 implements AddressInterface
{
    const MAX_PREFIX_LENGTH = 32;

    protected $address;

    /**
     * @param string $readable
     * @return void
     */
    public function __construct($readable)
    {
        if (filter_var($readable, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            throw new InvalidAddressException($readable.' is not a valid IPv4 address');
        }

        $this->address = inet_pton($readable);
    }

    /**
     * @param string $readable
     * @return IPv4
     */
    public static function create($readable)
    {
        return new static($readable);
    }

    /**
     * @param mixed $address
     * @return IPv4
     */
    public static function parse($address)
    {
        if (strpos($address, '.') !== false) {
            $address = strtolower($address);
            $octets  = [];
            foreach (explode('.', $address) as $octet) {
                $hexOctet = sscanf($octet, '0x%02X');
                if (empty($hexOctet) === false and $hexOctet[0] !== null) {
                    $octets[] = $hexOctet[0];
                } elseif ('0'.decoct(octdec(substr($octet, 1))) == $octet) {
                    $octets[] = sscanf($octet, '%04o')[0];
                } else {
                    $octets[] = $octet;
                }
            }

            $address = implode('.', $octets);
        } elseif (is_numeric($address) === true or substr($address, 0, 2) === '0x') {
            $address = long2ip($address);
        }

        return new static($address);
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
        return static::VERSION_4;
    }

    /**
     * @param boolean $dotted
     * @return mixed
     */
    public function toDec($asString = false)
    {
        $address = ip2long($this->toReadable());

        if ($asString === false) {
            return $address;
        }

        return sprintf('%u', $address);
    }

    /**
     * @param boolean $dotted
     * @return string
     */
    public function toHex($dotted = false)
    {
        $format = ($dotted === false) ? '0x%02X%02X%02X%02X' : '0x%02X.0x%02X.0x%02X.0x%02X';
        return vsprintf($format, sscanf($this->toReadable(), '%d.%d.%d.%d'));
    }

    /**
     * @param boolean $dotted
     * @return string
     */
    public function toOct($dotted = false)
    {
        if ($dotted === false) {
            return '0'.decoct($this->toDec(true));
        }

        return vsprintf('%04o.%04o.%04o.%04o', sscanf($this->toReadable(), '%d.%d.%d.%d'));
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
