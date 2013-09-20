<?php
/**
 * Description of Telnet
 *
 * @author butzke
 */
class Telnet extends PHPUnit_Framework_TestCase
{
    const SERVER = '';
    const USERNAME = '';
    const PASSWORD = '';
    const PORT = 23;

    public function testConnect()
    {
        $telnet = new \RemoteServer\Telnet();
        $result = $telnet->connect(self::SERVER, self::USERNAME, self::PASSWORD, self::PORT);
        
        $this->assertTrue($result);
    }
    
    public function testExecute()
    {
        $telnet = new \RemoteServer\Telnet();
        $telnet->connect(self::SERVER, self::USERNAME, self::PASSWORD, self::PORT);

        $result = $telnet->execute('ls -la');
        
        $this->assertTrue($result);
    }
    
    public function testGetResult()
    {
        $telnet = new \RemoteServer\Telnet();
        $telnet->connect(self::SERVER, self::USERNAME, self::PASSWORD, self::PORT);
        $telnet->execute('ls -la');
        
        $this->assertInternalType('string', $telnet->getResult());
        $this->assertInternalType('array', $telnet->getResultArray());
    }
}
