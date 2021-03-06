<?php
date_default_timezone_set('Asia/Taipei');
session_start();

class DB
{
    protected $dsn = "mysql:host=localhost;charset=utf8;dbname=web01",
        $pdo;

    public $table;
    public $title;
    public $botton;
    public $header;
    public $append;
    public $upload;

    function __construct($table)
    {
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, $this->user = 'root', $this->pw = '');

        $this->setStr($table);
    }

    private function setStr($table)
    {
        switch ($table) {
            case "title":
                $this->title = "網站標題管理";
                $this->button = "新增網站標題圖片";
                $this->header = "網站標題";
                $this->append = "替代文字";
                $this->upload = "網站標題圖片";
                break;
            case "ad":
                $this->title = "動態文字廣告管理";
                $this->button = "新增動態文字廣告";
                $this->header = "動態文字廣告";
                break;
            case "mvim":
                $this->title = "動畫圖片管理";
                $this->button = "新增動畫圖片";
                $this->header = "動畫圖片";
                $this->upload = "動畫圖片";
                break;
            case "image":
                $this->title = "校園映像資料管理";
                $this->button = "新增校園映像圖片";
                $this->header = "校園映像資料圖片";
                $this->upload = "校園映像圖片";
                break;
            case "total":
                $this->title = "進站總人數管理";
                $this->button = "";
                $this->header = "進站總人數:";
                break;
            case "bottom":
                $this->title = "頁尾版權資料管理";
                $this->button = "";
                $this->header = "頁尾版權資料";
                break;
            case "news":
                $this->title = "最新消息資料管理";
                $this->button = "新增最新消息資料";
                $this->header = "最新消息資料內容";
                break;
            case "admin":
                $this->title = "管理者帳號管理";
                $this->button = "新增管理者帳號";
                $this->header = "帳號";
                $this->append = "密碼";
                break;
            case "menu":
                $this->title = "選單管理";
                $this->button = "新增主選單";
                $this->header = "主選單名稱";
                $this->append = "選單連結網址";
                break;
        }
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

    function math($m, $c, ...$arg)
    {
        $sql = "SELECT $m($c) FROM $this->table ";
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
        if (isset($array['id'])) {
            foreach ($array as $k => $v) {
                $tmp[] = "`$k`='$v'";
            }
            $sql = "UPDATE $this->table SET " . implode(",", $tmp) . " WHERE `id`='{$array['id']}'";
        } else {
            //insert
            $col = implode("`,`", array_keys($array));
            $values = implode("','", $array);
            $sql = "INSERT INTO $this->table (`$col`) VALUES ('$values')";
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


function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function to($url)
{
    header("location:" . $url);
}

$Title = new DB('title');
$Ad = new DB('ad');
$Mvim = new DB('mvim');
$Image = new DB('image');
$News = new DB('news');
$Admin = new DB('admin');
$Menu = new DB('menu');
$Bottom = new DB('bottom');
$Total = new DB('total');

$tt = $_GET['do'] ?? '';

switch ($tt) {
    case "ad":
        $DB = $Ad;
        break;
    case "mvim":
        $DB = $Mvim;
        break;
    case "image":
        $DB = $Image;
        break;
    case "total":
        $DB = $Total;
        break;
    case "bottom":
        $DB = $Bottom;
        break;
    case "news":
        $DB = $News;
        break;
    case "admin":
        $DB = $Admin;
        break;
    case "menu":
        $DB = $Menu;
        break;
    default:
        $DB = $Title;
        break;
}

if (!isset($_SESSION['total'])) {
    $total = $Total->find(1);
    $total['total']++;
    $Total->save($total);
    $_SESSION['total'] = $total['total'];
}
