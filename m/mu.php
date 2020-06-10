<?php

class mu extends model
{
    public function createDb()
    {
        $sql = "creaet database shorturl;";

        $ret = $this->_db->query($sql);

        return $ret;
    }

    public function createTable()
    {
        $sql = "
        CREATE TABLE `urls` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `md5` char(32) NOT NULL,
          `url` varchar(500) NOT NULL,
          `note` text NULL,
          `count` int(11) NOT NULL,
          `isStopped` tinyint(1) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`),
          UNIQUE KEY `md5` (`md5`)
        ) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8;
        ";

        $ret = $this->_db->query($sql);
        return $ret;
    }

    public function checkSame($url)
    {
        $md5 = md5($url);
        $sql = sprintf("select * from urls where `md5`='$md5'");

        $ret = $this->_db->fetch_one_assoc($sql);

        return $ret;
    }

    public function add($url)
    {
        $md5 = md5($url);
        $u = addslashes($url);
        $sql = sprintf("insert into urls(`md5`,`url`,`note`,`count`,`isStopped`) values(
                '$md5','$u', '', 0, 0
        )");

        $ret = $this->_db->query($sql);
        if($ret)
            $id = $this->_db->insert_id();
        else
            $id = 0;
        
        return $id ;
    }

    public function query($id)
    {
        $sql = sprintf("select * from urls where `id`='$id'");

        $ret = $this->_db->fetch_one_assoc($sql);

        return $ret;
    }

}
