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
namespace RemoteServer;

/**
 * @author Tiago Butzke <tiago.butzke@gmail.com>
 */
class RemoteServer
{
    private $_driver;

    public function __construct(Protocol $protocolDriver)
    {
        $this->_driver = $protocolDriver;
    }
    
    public function connect($ip, $username, $password, $port)
    {
        return $this->_driver->connect($ip, $username, $password, $port);
    }
        
    public function execute($command)
    {
        return $this->_driver->execute($command);
    }
    
    public function disconnect()
    {
        return $this->_driver->disconnect();
    }
    
    public function getResult()
    {
        return $this->_driver->getResult();
    }
    
    public function getResultArray()
    {
        return $this->_driver->getResultArray();
    }
}
