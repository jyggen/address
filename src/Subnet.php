<?php
namespace Boo\Address;

use Boo\Address\Exception\InvalidSubnetException;

class Subnet
{
    public function __construct(AddressInterface $address, $netmask = null)
    {
        if ($netmask === null) {
            $netmask = $address->getMaxPrefixLength();
        }

        if ($netmask < 1 or $netmask > $address->getMaxPrefixLength()) {
            throw new InvalidSubnetException($netmask.' is not a valid netmask for address '.$address->toReadable());
        }
    }

    public static function createFromCIDR($subnet)
    {
        if (strpos($subnet, '/') === false) {
            throw new InvalidSubnetException($subnet.' is not a valid CIDR notated subnet');
        }

        list($address, $netmask) = explode('/', $subnet, 2);
        $address = (substr_count($address, ':') > 1) ? new IPv6($address) : new IPv4($address);

        return new static($address, $netmask);
    }
}
