<style>
    #container {
    min-width: 310px;
    height: 400px;
    margin: 0 auto;
}

.center {
  margin-left: auto;
  margin-right: auto;
}

table td, table td * {
    vertical-align: top;
}
</style>
<?php
session_start();
clearstatcache();
?>

<html>
<head> 
    <title>Select your pair</title> 
</head>
<body>
<br><br>
<table class="center" width="80%" border="0" cellspacing="0">
    <td width="20%">
        <h2><?php
        
        $link = $_SESSION["link"]; 
        $coinname=shell_exec("python getcoinname.py "  .$link);
        echo"$coinname";
        ?></h2>
        <form method="POST" action="result.php">

        <label for="open">ราคาเปิดของวัน :</label><br>
        <input type="text" id="open" name="open"><br>
        <label for="high">ราคาสูงสุดของวัน :</label><br>
        <input type="text" id="high" name="high"><br>
        <label for="low">ราคาต่ำสุดของวัน :</label><br>
        <input type="text" id="low" name="low"><br>
        <br>
        <input type="submit" name="submit" value="Submit this pair"/>
        </form>
        <a href="<?php echo $_SESSION["link"]; ?>">download raw CSV</a>
    </td>
    <td width="20%">
        <?php
            if(isset($_POST['submit']) && $_POST["open"] != NULL && $_POST["high"] != NULL && $_POST["low"] != NULL){
                $open = $_POST["open"];
                echo "ราคาเปิดของวัน : ".$open."<br>";
                $high = $_POST["high"];
                echo "ราคาสูงสุดของวัน : ".$high."<br>";
                $low = $_POST["low"];
                echo "ราคาต่ำสุดของวัน : ".$low."<br>";
                
                $output=shell_exec("python model.py $open $high $low");

                echo "ราคาปิดของวันจากการทำนาย : ".$output."<br>";
            }
        ?>
    </td>
    <td width="40%">
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/data.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <div id="container"></div>
    </td>
</table>
</body>
</html>

<script>
    Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Predicting price'
    },

    subtitle: {
        text: 'Data input from CSV file'
    },

    data: {
        csvURL: './predic_pair.csv',
        enablePolling: true
    }
});
</script>