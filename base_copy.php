<?php
date_default_timezone_set("Asia/Taipei");
session_start();

class DB
{
    protected $dsn = "mysql:host=localhost;charset=utf8;dbname=web01",

        $pdo;
    public $table;   //資料表名稱
    public $title;   //後台功能名稱
    public $button;  //新增功能按鈕
    public $header;  //列表第一欄標題
    public $append;  //列表第二欄標題
    public $upload;  //更新圖片彈出視窗用

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
            case "botttom":
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
        if (isset($array['id'])) {
            foreach ($array as $k => $v) {
                $tmp[] = "`$k`='$v'";
            }
            $sql = "UPDATE $this->table SET " . implode(",", $tmp) . " WHERE `id`='{$array['id']}'";
        } else {
            //insert
            $col = implode("`,`", array_keys($array));
            $values = implode("','", $array);
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

function dd($array)
{
    echo "<pre>";
    echo print_r($array);
    echo "</pre>";
}

function to($url)
{
    header("location:" . $url);
}

$Ad = new DB('ad');
$Admin = new DB('admin');
$Bottom = new DB('bottom');
$Image = new DB('image');
$Menu = new DB('menu');
$Mvim = new DB('mvim');
$News = new DB('news');
$Title = new DB('title');
$Total = new DB('total');

if (!isset($_SESSION['total'])) {
    $total = $Total->find(1);
    $total['total']++;
    $Total->save($total);
    $_SESSION['total'] = $total['total'];
}



$tt=$_GET['do']??'';  //取得網址參數do的值

switch($tt){   //利用網址參數來轉換$DB代表的資料表
    case "ad":
        $DB=$Ad;
    break;
    case "mvim":
        $DB=$Mvim;
    break;
    case "image":
        $DB=$Image;
    break;
    case "total":
        $DB=$Total;
    break;
    case "bottom":
        $DB=$Bottom;
    break;
    case "news":
        $DB=$News;
    break;
    case "admin":
        $DB=$Admin;
    break;
    case "menu":
        $DB=$Menu;
    break;
    default:
        $DB=$Title;
    break;
}
