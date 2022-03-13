<h3>更新標題圖片</h3>
<hr>
<form action="api/edit.php?do=<?=$DB->table;?>" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td>標題圖片:</td>
            <td><input type="file" name="img"></td>
        </tr>
        
    </table>
    <div><input type="submit" value="新增"><input type="reset" value="重置"></div>
</form>