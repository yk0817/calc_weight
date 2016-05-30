<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>健康記録</title>
</head>
<link rel="stylesheet" href="./css/reset.css">
<link rel="stylesheet" href="./css/style2.css">
<link rel="stylesheet" type="text/css" href="./plot/jquery.jqplot.min.css" />
<body>
  <header class = "title"><h1>健康記録</h1>
    <form action="" class = "input_form" name = "form">
      <label>mail
        <input class = "mail_login" name="email" type="email">
      </label>

      <label class = "password_text">password
        <input type="password" name="password" class= "password" maxlength="10" >
      </label>
      <input type="submit" onClick = "check()" class= "submit" value = "login">
    </form>
  </header>
  <span class="weight_1" style="left:100px;position:absolute;"></span>

  <main>
    <div class = "main_inner">
        <div class = "formArea">
          <form method="post" action = "./input.php" >
            <input class = "enter_weight" name = "weight" type="text" placeholder="今日の体重を記入して下さい">
            <button type="submit" class = "button_weight">入力</button>
          </form>
        </div>
         <div class = "d3_graph">

            <form class="" action="" method="post">
              <select name="days" class="days_select">
                <option value="3">3日間</option>
                <option value="7">7日間</option>
                <option value="14">14日間</option>
                <option value="30">30日間</option>
                <option value="90">90日間</option>
              </select>
            </form>

          <div id="jqPlot-sample" style="left:5%; top:5%; height: 400px; width: 800px;"></div>
        </div>
    </div>
  </main>
  <!-- <input type="text" class="enter_weight">aa -->
</body>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="./plot/jquery.jqplot.min.js"></script>
<script language="javascript" type="text/javascript" src="./plot/plugins/jqplot.highlighter.min.js"></script>
<script language="javascript" type="text/javascript" src="./plot/plugins/jqplot.dateAxisRenderer.min.js"></script>

<?php

// ↓でやろうとしたら出来なかった。
// $json_data ;

function fetch_data($days) {
  $pdo = new PDO('mysql:dbname=lose_weight;host=localhost','root','');
  $stmt = $pdo->prepare('SELECT * FROM weight ORDER BY date ASC LIMIT :days');
  $stmt->bindValue(':days',$days,PDO::PARAM_INT);
  $flag = $stmt->execute();

  if ($flag == false) {
    echo  "SQLエラー";
  } else {
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
  }

  // あとで質問しておくこと
  global $json_data;
  $json_data = json_encode($result);
  print_r($json_data);
}

// fetch_data(3);


// fetch_data(3);



?>

<script>


$(".days_select").change(
  function(){
      if ($(this).val()==7) {
        // <?php fetch_data(7); ?>;
        $("#jqPlot-sample").empty();
        console.log(7);
        data_convert2();
      }
      else if ($(this).val()==3) {
        // <?php fetch_data(3); ?>;
        $("#jqPlot-sample").empty();

        console.log(3);
        data_convert1();
      }
      else if ($(this).val()==14) {
        // <?php fetch_data(14); ?>;
        $("#jqPlot-sample").empty();

        console.log(14);
        data_convert3();
      }
      draw_graph();
  }
);

// var weight_data;
var target = [];



function data_convert1(){
  weight_data = JSON.parse('<?php fetch_data(3); ?>');
  // console.log(weight_data);
  target = [];

  for (var i = 0; i < weight_data.length; i++) {
    weight_data[i].splice(0,1);
    weight_data[i][1] = Number(weight_data[i][1]);
    // console.log(1);
    target.push([weight_data[i][0],65]);
  }
  // console.log(weight_data);
  // console.log(target);
}

function data_convert2(){
  weight_data = JSON.parse('<?php fetch_data(7); ?>');
  // console.log(weight_data);
  target = [];

  for (var i = 0; i < weight_data.length; i++) {
    weight_data[i].splice(0,1);
    weight_data[i][1] = Number(weight_data[i][1]);
    console.log(1);
    target.push([weight_data[i][0],65]);
  }
  // console.log(weight_data);
  // console.log(target);
}

function data_convert3(){
  weight_data = JSON.parse('<?php fetch_data(14); ?>');
  console.log(weight_data);
  target = [];

  for (var i = 0; i < weight_data.length; i++) {
    weight_data[i].splice(0,1);
    weight_data[i][1] = Number(weight_data[i][1]);
    console.log(1);
    target.push([weight_data[i][0],65]);
  }
  console.log(weight_data);
  console.log(target);
}



function draw_graph(){
// データコンバート
  // data_convert();

// プロット
  $(function() {
      $. jqplot(
          'jqPlot-sample',
          // データを以下に記入
          [
              weight_data,
              target,
          ],
          {
               title: {
                text: "体重",
                fontSize: "24px",
                textAlign: "center"

            },
              highlighter: {
                  show: true,
                  showMarker: true,
                  tooltipLocation: 'n',
                  tooltipAxes: 'y',
                  formatString: '%s'
              },

              Legend: {
                show: true,
                labels: "日々の体重"
              },
              axes: {
                xaxis:{
                  label: '日付',
                  renderer: $.jqplot.DateAxisRenderer,
                  tickOptions:{
                    formatString:'%#m月%#d日',
                    textColor: "rgb(130, 128, 150)",
                    fontSize: "14px",
                  },
                  numberTicks: weight_data.length,
                },
                yaxis:{
                  label: '体重(kg)',
                  tickOptions:{
                    fontSize: "14px",
                  },

                },
              },
              animate: true,
              series:[
                  {
                    lineWidth: 5,
                    markerOptions:{ style:"filledSquare",
                                    color:"red"},
                    label: '体重',
                    color: "blue"
                  },
                  {
                    label: "理想",
                    color: "black",
                    linePattern: "dashed",
                    markerOptions:{
                      show: false,
                    },
                    // markerOptions:
                  }
            ],

              legend: {
                show: true
              },

              grid:{
                background: 'rgb(209, 175, 29)',
              }
          }
      );
  } );
}

// draw_graph();


function check() {

  if(document.form.email.value == "mi-numero@hotmail.co.jp" && document.form.password.value == "39884516") {
    alert("合ってます");
    return $('.button_weight').css('display','block');

  } else{
    alert("合致してません。");

  }

}

</script>
</html>
