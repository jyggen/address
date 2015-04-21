<?php
namespace Boo\Address;

interface AddressInterface
{
    const VERSION_4 = 4;
    const VERSION_6 = 6;

    /**
     * @param AddressInterface $address
     * @return boolean
     */
    public function equals(AddressInterface $address);

    /**
     * @return integer
     */
    public function getMaxPrefixLength();

    /**
     * @return string
     */
    public function getVersion();

    /**
     * @return string
     */
    public function toReadable();
}
