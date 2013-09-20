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
 * Based on PHPTelnet class http://www.geckotribe.com/php-telnet/ by Antone Roundy
 * 
 * @author Tiago Butzke <tiago.butzke@gmail.com>
 */
class Telnet implements Protocol
{
    private $_fp = NULL;
    private $_con1;
    private $_con2;
    private $_loginPrompt;
    private $_result;

    public function __construct()
    {
        $this->_con1 = chr(0xFF).chr(0xFB).chr(0x1F).chr(0xFF).chr(0xFB).
                chr(0x20).chr(0xFF).chr(0xFB).chr(0x18).chr(0xFF).chr(0xFB).
                chr(0x27).chr(0xFF).chr(0xFD).chr(0x01).chr(0xFF).chr(0xFB).
                chr(0x03).chr(0xFF).chr(0xFD).chr(0x03).chr(0xFF).chr(0xFC).
                chr(0x23).chr(0xFF).chr(0xFC).chr(0x24).chr(0xFF).chr(0xFA).
                chr(0x1F).chr(0x00).chr(0x50).chr(0x00).chr(0x18).chr(0xFF).
                chr(0xF0).chr(0xFF).chr(0xFA).chr(0x20).chr(0x00).chr(0x33).
                chr(0x38).chr(0x34).chr(0x30).chr(0x30).chr(0x2C).chr(0x33).
                chr(0x38).chr(0x34).chr(0x30).chr(0x30).chr(0xFF).chr(0xF0).
                chr(0xFF).chr(0xFA).chr(0x27).chr(0x00).chr(0xFF).chr(0xF0).
                chr(0xFF).chr(0xFA).chr(0x18).chr(0x00).chr(0x58).chr(0x54).
                chr(0x45).chr(0x52).chr(0x4D).chr(0xFF).chr(0xF0);
        $this->_con2 = chr(0xFF).chr(0xFC).chr(0x01).chr(0xFF).chr(0xFC).
                chr(0x22).chr(0xFF).chr(0xFE).chr(0x05).chr(0xFF).chr(0xFC).chr(0x21);;
    }
    
    private function _getResponse(&$r)
    {
        $r = '';
        
        do { 
            $r .= fread($this->_fp,1000);
            $s = socket_get_status($this->_fp);
            
        } while ($s['unread_bytes']);
    }
    
    public function connect($ip, $username, $password, $port)
    {
        if (preg_match('/[^0-9.]/', $ip))
            throw new \Exception('Invalid ip');
        
        $this->_fp = fsockopen($ip, $port);

        if (!$this->_fp)
            throw new \Exception('Could not connect to server');
        
        fputs($this->_fp, $this->_con1);
        sleep(1);
        
        fputs($this->_fp, $this->_con2);
        sleep(1);
        $this->_getResponse($r);
        $r = explode('\n', $r);
        $this->_loginPrompt = $r[count($r)-1];
        
        fputs($this->_fp, "{$username}\r");
        sleep(1);
        fputs($this->_fp, "{$password}\r");
        sleep(1);
        
        $this->_getResponse($r);
        $r = explode('\n', $r);
        
        if (($r[count($r)-1] == '') || ($this->_loginPrompt == $r[count($r)-1]))
            throw new \Exception('Login failed');
        
        return true;
    }
        
    public function execute($command)
    {
        if (!$this->_fp)
            throw new \Exception('Not connected yet');
        
        fputs($this->_fp, "{$command}\r");
        sleep(1);
        $this->_getResponse($this->_result);
        $this->_result = preg_replace("/^.*?\n(.*)\n[^\n]*$/", "$1", $this->_result);
        
        return true;
    }
    
    public function disconnect()
    {
        if (!$this->_fp)
            throw new \Exception('Not connected yet');
        
        $this->execute('exit');
        fclose($this->_fp);
        $this->_fp = NULL;
        
        return true;
    }
    
    public function getResult()
    {
        return $this->_result;
    }
    
    public function getResultArray()
    {
        return explode("\r\n", $this->_result);
    }
}
