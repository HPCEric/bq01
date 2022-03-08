<?php
date_default_timezone_set("Asia/Taipei");
session_start();

class DB
{
    protected $dsn = "mysql:host=localhost;charset=utf8;dbname=web01",
        $table,
        $pdo;

    function __construct($table)
    {
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, $this->user = 'root', $this->pw = '');
    }

    function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE ";
        if (is_array($id)) {
            foreach ($id as $k => $v) {
                $tmp[] = "`$k`='$v'";
            }
            $sql .= implode(" AND ", $tmp);
        } else {
            $sql .= "`id`='$id'";
        }
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    function all(...$arg)
    {
        $sql = "SELECT * FROM $this->table ";

        switch (count($arg)) {
            case 1:
                if (is_array($arg[0])) {
                    foreach ($arg[0] as $k => $v) {
                        $tmp[] = "`$k`='$v'";
                    }
                    $sql .= " WHERE " . implode(" AND ", $tmp);
                } else {
                    $sql .= $arg[0];
                }
                break;
            case 2:
                foreach ($arg[0] as $k => $v) {
                    $tmp[] = "`$k`='$v'";
                }
                $sql .= " WHERE " . implode(" AND ", $tmp) . " " . $arg[1];
                break;
        }

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    function math($method, $col, ...$arg)
    {
        $sql = "SELECT $method($col) FROM $this->table ";

        switch (count($arg)) {
            case 1:
                if (is_array($arg[0])) {
                    foreach ($arg[0] as $k => $v) {
                        $tmp[] = "`$k`='$v'";
                    }
                    $sql .= " WHERE " . implode(" AND ", $tmp);
                } else {
                    $sql .= $arg[0];
                }
                break;
            case 2:
                foreach ($arg[0] as $k => $v) {
                    $tmp[] = "`$k`='$v'";
                }
                $sql .= " WHERE " . implode(" AND ", $tmp) . " " . $arg[1];
                break;
        }
        return $this->pdo->query($sql)->fetchColumn();
    }
    function save($array)
    {
        //update
        if(isset($array['id'])){
            foreach($array as $k=>$v){
                $tmp[]="`$k`='$v'";
            }
            $sql="UPDATE $this->table SET " . implode(",",$tmp) . " WHERE `id`='{$array['id']}'";
        }else{
            //insert
            $col=implode("`,`",array_keys($array));
            $values=implode("','",$array);
            $sql = "INSERT INTO $this->table (`$col`) VALUES ('$values') ";
        }
        return $this->pdo->exec($sql);
    }
    function del($id)
    {
        $sql = "DELETE FROM $this->table WHERE ";
        if (is_array($id)) {
            foreach ($id as $k => $v) {
                $tmp[] = "`$k`='$v'";
            }
            $sql .= implode(" AND ", $tmp);
        } else {
            $sql .= "`id`='$id'";
        }
        return $this->pdo->exec($sql);
    }
}

function dd($array){
    echo "<pre>";
    echo print_r($array);
    echo "</pre>";
}

function to($url){
    header("location:" .$url);
}

$Ad=new DB('ad');
$Admin=new DB('admin');
$Bottom=new DB('bottom');
$Image=new DB('image');
$Menu=new DB('menu');
$Mvim=new DB('mvim');
$News=new DB('news');
$Title=new DB('title');
$Total=new DB('total');

if(!isset($_SESSION['total'])){
    $total=$Total->find(1);
    $total['total']++;
    $Total->save($total);
    $_SESSION['total']=$total['total'];
}