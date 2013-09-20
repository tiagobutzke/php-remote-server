<?php
/**
 * The MIT License (MIT)
 * 
 * Copyright (c) 2013 Stoodos http://www.stoodos.com
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Description of SshTest
 *
 * @author Tiago Butzke <tiago.butzke@gmail.com>
 */
class SshTest extends PHPUnit_Framework_TestCase
{
    const SERVER = '';
    const USERNAME = '';
    const PASSWORD = '';
    const PORT = 22;
    
    public function testConnect()
    {
        $ssh = new \RemoteServer\Ssh();
        
        $this->assertTrue($ssh->connect(self::SERVER, self::USERNAME, self::PASSWORD, self::PORT));
    }
    
    public function testExecute()
    {
        $ssh = new \RemoteServer\Ssh();
        $ssh->connect(self::SERVER, self::USERNAME, self::PASSWORD, self::PORT);
        
        $this->assertTrue($ssh->execute('ls -la'));
    }
    
    public function testGetResult()
    {
        $ssh = new \RemoteServer\Ssh();
        $ssh->connect(self::SERVER, self::USERNAME, self::PASSWORD, self::PORT);
        $ssh->execute('ls -la');
        
        $result = $ssh->getResultArray();
        
        $this->assertInternalType('string', $ssh->getResult());
        $this->assertInternalType('array', $result);
    }
    
    public function testDisconnect()
    {
        $ssh = new \RemoteServer\Ssh();
        $ssh->connect(self::SERVER, self::USERNAME, self::PASSWORD, self::PORT);
        
        $this->assertTrue($ssh->disconnect());        
    }
}
