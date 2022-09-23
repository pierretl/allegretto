<?php
    include 'include/header.php';

    $data_json = "demo/sejour.json";
?>



<link href="css/fullcalendar-5.11.3.min.css" rel="stylesheet">
<script src='js-lib/fullcalendar-5.11.3.min.js'></script>
<script src='js-lib/fullcalendar-locales-all.min.js'></script>
<script>
    let aujourdhui = new Date().toISOString().split("T")[0];

    document.addEventListener("DOMContentLoaded", function () {
    var calendarEl = document.getElementById("calendar");

    
    fetch("<?php echo $data_json; ?>")
        .then((response) => {
            if (!response.ok) {
                throw new Error("HTTP error " + response.status);
            }
            return response.json();
        })
        .then((json) => {
        
            //console.log('json',json);

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: "dayGridMonth",
                initialDate: aujourdhui,
                weekNumbers: true,
                locale: "fr",
                events: json
            });
            calendar.render();
        
        })
        .catch(function () {
            this.dataError = true;
        });
    
    });
</script>




<table border="0" align="center" cellpadding="5" cellspacing="1" class="Table" style="display:none">

<caption>Data : <a target="_blank" href="<?php echo $data_json; ?>">json</a></caption>

<thead>
        <tr>
        <td>Info</td>
        <td>Arrivée</td>
        <td>Départ</td>
        <td>Couleur</td>
        <td>Edition</td>
        </tr>
</thead>
<tbody>
            
    

    <?php 

    // decode json
    $jsonString = file_get_contents($data_json);
    $data = json_decode($jsonString, true);


    foreach ($data as $key => $entry) {
            echo "<tr>";

            echo "<td>" . $data[$key]['title'] . "</td>";
            echo "<td>" . $data[$key]['start'] . "</td>";
            echo "<td>" . $data[$key]['end'] . "</td>";
            echo "<td>" . $data[$key]['backgroundColor'] . "</td>";
            echo '<td><a href="?sejour='.$key.'">Edition</buton></td>';


            echo "</tr>";
    }

    ?>
</tbody>
</table>

<div id="calendar"></div>


<?php include 'include/footer.php';?>