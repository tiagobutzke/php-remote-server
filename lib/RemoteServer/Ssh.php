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
 * Description of Ssh
 *
 * @author Tiago Butzke <tiago.butzke@gmail.com>
 */
class Ssh implements Protocol
{
    private $_connection;
    private $_result;
    private $_resultParsed = false;
    private $_resultBuffer = '';
    
    private function _loadBuffer()
    {
        if ($this->_resultParsed == false) {
            stream_set_blocking($this->_result, true);

            $this->_resultBuffer = '';
            while ($buf = fread($this->_result, 4096))
                $this->_resultBuffer .= $buf;

            fclose($this->_result);

            $this->_resultParsed = true;            
        }
    }
    
    /**
     * Connect to server
     * 
     * @param string $ip
     * @param string $username
     * @param string $password
     * @param int $port
     * 
     * @return boolean
     */
    public function connect($ip, $username, $password, $port)
    {
        if (preg_match('/[^0-9.]/', $ip))
            throw new \Exception('Invalid ip');
        
        $this->_connection = ssh2_connect($ip, $port);
        
        if (!$this->_connection)
            throw new \Exception('Could not connect to server');
        
        if (!ssh2_auth_password($this->_connection, $username, $password))
            throw new \Exception('Could not connect to server');
                
        return true;
    }
        
    /**
     * Command execution
     * 
     * @param string $command
     * 
     * @return string
     */
    public function execute($command)
    {
        $this->_result = ssh2_exec($this->_connection, $command);
        $this->_resultParsed = false;

        return true;
    }
    
    /**
     * Disconnect to the server
     */
    public function disconnect()
    {
        $this->execute('exit');
        
        return true;
    }
    
    public function getResult()
    {
        $this->_loadBuffer();
        
        return $this->_resultBuffer;
    }
    
    public function getResultArray()
    {
        $this->_loadBuffer();
        return explode("\n", $this->_resultBuffer);
    }
}
