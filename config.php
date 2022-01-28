<?php
    function cookie($username,$password){
        setcookie("Username",$username,time()+7*(24*60*60*1000),"/");
        setcookie("Password",$password,time()+7*(24*60*60*1000),"/");
    }

    function connect_db(){
        $dbcon=new mysqli("localhost","root","","portofolio");
        return $dbcon;
    }

    function salting(){
        return rand();
    }


?>