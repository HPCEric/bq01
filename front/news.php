<div class="di"
    style="height:540px; border:#999 1px solid; width:53.2%; margin:2px 0px 0px 0px; float:left; position:relative; left:20px;">
    <?php include "marquee.php";?>
    <div style="height:32px; display:block;"></div>
    <span class="t botli">更多最新消息顯示區</span>
    <!--正中央-->
    
        <?php
            $all=$DB->math('count','*');
            $div=5;
            $pages=ceil($all/$div);
            $now=$_GET['p']??1;
            $start=($now-1)*$div;
        ?>
        <ol style="list-style-type:decimal;" start="<?=($now-1)*$div+1;?>">
        <?php
            $rows=$DB->all(" limit $start,$div");
            foreach($rows as $n){
                echo "<li class='sswww'>";
                echo mb_substr($n['text'],0,20);
                echo "<div class='all' style='display:none'>{$n['text']}</div>";
                echo "</li>";
            }

        ?>
        </ol>
    <style>
        .more-news a{
            text-decoration:none;
        }
        .more-news a:hover{
            text-decoration:underline;
        }


    </style>
    <div class="more-news" style="text-align:center;">
    <?php
            if(($now-1)>0){
                $p=$now-1;
                echo "<a href='?do={$DB->table}&p=$p'> &lt; </a>";   
            }else{
                echo "<a href='?do={$DB->table}&p=$now'> &lt; </a>";   
            }


            for($i=1;$i<=$pages;$i++){
            if($i==$now){
                $fontsize="24px";
            }else{
                $fontsize="16px";
            }
             echo "<a href='?do={$DB->table}&p=$i' style='font-size:$fontsize'> $i </a>";
            }

            if(($now+1)<=$pages){
                $p=$now+1;
                echo "<a href='?do={$DB->table}&p=$p'> &gt; </a>";   
            }else{
                echo "<a href='?do={$DB->table}&p=$now'> &gt; </a>";   

            }
        ?>
    </div>
</div>
<div id="alt"
  style="position: absolute; width: 350px; min-height: 100px; word-break:break-all; text-align:justify;  background-color: rgb(255, 255, 204); top: 50px; left: 400px; z-index: 99; display: none; padding: 5px; border: 3px double rgb(255, 153, 0); background-position: initial initial; background-repeat: initial initial;">
</div>
<script>
$(".sswww").hover(
    function() {
        $("#alt").html("<pre>" + $(this).children(".all").html() + "</pre>").css({
            "top": $(this).offset().top - 50
        })
        $("#alt").show()
    }
)
$(".sswww").mouseout(
    function() {
        $("#alt").hide()
    }
)
</script>