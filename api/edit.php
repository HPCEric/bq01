<?php
include_once "../base.php";
foreach($_POST['id'] as $key => $id){
    if(isset($_POST['del']) && in_array($id,$_POST['del'])){
        //刪除
        $DB->del($id);

    }else{
        //更新
        //先取出該筆資料
        $data=$DB->find($id);

        //針對不同的資料表進行相應的資料內容處理
        switch($DB->table){
            case "title":
                $data['text']=$_POST['text'][$key];

                //針對單選的項目只要判斷是否相符即可
                $data['sh']=($_POST['sh']==$id)?1:0;
            break;
            case "admin":
                $data['acc']=$_POST['acc'][$key];
                $data['pw']=$_POST['pw'][$key];
            break;
            case "menu":
                $data['name']=$_POST['name'][$key];
                $data['href']=$_POST['href'][$key];

                //針對可以多選的項目要同時判斷陣列是否存在及id是否在該陣列中
                $data['sh']=(isset($_POST['sh']) && in_array($id,$_POST['sh']))?1:0;
            break;
            default:
            //ad,,news,image,mvim
                //針對需要寫入字串的項目要先判斷是否有text這個陣列，
                //再依照key值取得對應的資料寫入資料表
                $data['text']=isset($_POST['text'])?$_POST['text'][$key]:'';

                //針對可以多選的項目要同時判斷陣列是否存在及id是否在該陣列中
                $data['sh']=(isset($_POST['sh']) && in_array($id,$_POST['sh']))?1:0;
            break;
        }
        //dd($data);
        $DB->save($data);
    }
}

to("../back.php?do=".$DB->table);

?>