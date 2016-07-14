<?php


namespace Dbdg\Models;


class ConnectionConfig
{

    private $host;
    private $port;
    private $db;
    private $user;
    private $pass;

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }


    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getPassword()
    {
        return $this->pass;
    }

    public function setPassword($pass)
    {
        $this->pass = $pass;
    }

}
