<?php
namespace Boo\Address\Test;

use Boo\Address\AddressInterface;
use Boo\Address\IPv4;

class IPv4Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Boo\Address\InvalidAddressException
     */
    public function testInvalidAddressThrowsException()
    {
        new IPv4('A.B.C.D');
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressCanBeInstantiated($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = new IPv4($readable);
        $this->assertInstanceOf('Boo\Address\IPv4', $address);
        $this->assertInstanceOf('Boo\Address\AddressInterface', $address);
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressCanBeCreated($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = IPv4::create($readable);
        $this->assertInstanceOf('Boo\Address\IPv4', $address);
        $this->assertInstanceOf('Boo\Address\AddressInterface', $address);
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressCanBeParsedFromReadable($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = IPv4::parse($readable);
        $this->assertSame($readable, $address->toReadable());
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressCanBeParsedFromDottedHexadecimal($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = IPv4::parse($dotHex);
        $this->assertSame($readable, $address->toReadable());
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressCanBeParsedFromDottedOctal($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = IPv4::parse($dotOct);
        $this->assertSame($readable, $address->toReadable());
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressCanBeParsedFromHexadecimal($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = IPv4::parse($hex);
        $this->assertSame($readable, $address->toReadable());
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressCanBeParsedFromDecimal($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = IPv4::parse($dec);
        $this->assertSame($readable, $address->toReadable());
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressCanBeParsedFromOctal($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = IPv4::parse($oct);
        $this->assertSame($readable, $address->toReadable());
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressHasValidReadable($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = new IPv4($readable);
        $this->assertSame($readable, $address->toReadable());
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressHasValidDottedHexadecimal($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = new IPv4($readable);
        $this->assertSame($dotHex, $address->toHex(true));
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressHasValidDottedOctal($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = new IPv4($readable);
        $this->assertSame($dotOct, $address->toOct(true));
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressHasValidDecimal($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = new IPv4($readable);
        $this->assertSame($dec, $address->toDec());
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressHasValidHexadecimal($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = new IPv4($readable);
        $this->assertSame($hex, $address->toHex());
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressHasValidOctal($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = new IPv4($readable);
        $this->assertSame($oct, $address->toOct());
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressCanBeCastToString($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address = new IPv4($readable);
        $this->assertSame($readable, (string) $address);
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressIsEqualToSelf($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address  = new IPv4($readable);
        $address2 = new IPv4($readable);
        $this->assertTrue($address->equals($address2));
    }

    /**
     * @dataProvider fullTestAddressProvider
     */
    public function testAddressIsCorrectVersion($readable, $dotHex, $dotOct, $hex, $dec, $oct)
    {
        $address  = new IPv4($readable);
        $this->assertSame(AddressInterface::VERSION_4, $address->getVersion());
    }

    /**
     * @dataProvider simpleTestAddressProvider
     */
    public function testAddressIsValidAndParsable($readable)
    {
        $address = new IPv4($readable);

        $this->assertSame($readable, $address->toReadable());

        $this->assertSame($readable, IPv4::parse($address->toReadable())->toReadable());
        $this->assertSame($readable, IPv4::parse($address->toDec())->toReadable());

        $this->assertSame($readable, IPv4::parse($address->toHex())->toReadable());
        $this->assertSame($readable, IPv4::parse($address->toHex(true))->toReadable());

        $this->assertSame($readable, IPv4::parse($address->toOct())->toReadable());
        $this->assertSame($readable, IPv4::parse($address->toOct(true))->toReadable());
    }

    /**
     * @dataProvider simpleTestAddressProvider
     */
    public function testAddressIsParsableWithMixedOctets($readable)
    {
        $address  = new IPv4($readable);
        $decParts = explode('.', $address->toReadable());
        $hexParts = explode('.', $address->toHex(true));
        $octParts = explode('.', $address->toOct(true));

        $this->assertSame($readable, IPv4::parse(implode('.', [$decParts[0], $hexParts[1], $octParts[2], $decParts[3]]))->toReadable());
        $this->assertSame($readable, IPv4::parse(implode('.', [$hexParts[0], $octParts[1], $decParts[2], $hexParts[3]]))->toReadable());
        $this->assertSame($readable, IPv4::parse(implode('.', [$octParts[0], $decParts[1], $hexParts[2], $octParts[3]]))->toReadable());
    }

    public function fullTestAddressProvider()
    {
        return [
            ['0.0.0.0', '0x00.0x00.0x00.0x00', '0000.0000.0000.0000', '0x00000000', 0, '00'],
            ['127.0.0.1', '0x7F.0x00.0x00.0x01', '0177.0000.0000.0001', '0x7F000001', 2130706433, '017700000001'],
            ['255.255.255.255', '0xFF.0xFF.0xFF.0xFF', '0377.0377.0377.0377', '0xFFFFFFFF', 4294967295, '037777777777'],
            ['192.168.1.1', '0xC0.0xA8.0x01.0x01', '0300.0250.0001.0001', '0xC0A80101', 3232235777, '030052000401'],
            ['192.0.2.235', '0xC0.0x00.0x02.0xEB', '0300.0000.0002.0353', '0xC00002EB', 3221226219, '030000001353'],
        ];
    }

    public function simpleTestAddressProvider()
    {
        return [
            ['0.0.0.0'],
            ['127.0.0.1'],
            ['255.255.255.255'],
            ['192.168.1.1'],
            ['192.0.2.235'],
            ['163.163.13.244'],
            ['12.132.69.191'],
            ['169.24.115.134'],
            ['210.199.165.19'],
            ['72.10.68.138'],
            ['124.195.25.126'],
            ['243.44.173.203'],
            ['171.87.91.79'],
            ['249.103.69.7'],
            ['235.137.197.149'],
            ['161.58.28.116'],
            ['2.193.135.74'],
            ['202.202.212.72'],
            ['143.236.197.131'],
            ['26.115.80.196'],
            ['202.170.21.197'],
            ['18.90.203.252'],
            ['226.145.147.133'],
            ['203.175.248.205'],
            ['113.128.24.61'],
            ['76.236.132.218'],
            ['217.74.95.242'],
            ['188.174.184.135'],
            ['89.205.77.107'],
            ['40.26.105.12'],
            ['171.251.144.119'],
            ['172.137.69.30'],
            ['11.93.90.87'],
            ['74.221.50.36'],
            ['41.145.24.229'],
        ];
    }
}
