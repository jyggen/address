<?php
namespace Boo\Address\Test;

use Boo\Address\IPv4;
use Boo\Address\Subnet;

class SubnetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Boo\Address\InvalidSubnetException
     */
    public function testInvalidAddressThrowsException()
    {
        new Subnet(new IPv4('127.0.0.1'), 1000);
    }
}
