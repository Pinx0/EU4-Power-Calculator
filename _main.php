<?php
$data = array('key'=> $private_key,
              'scope'=>'getCountryData',
              'format'=>'json',
              'save'=> $_GET['id'],
			  'value' => 'was_player;tag;countryName;quality;technology_group;technology;monthly_income;treasury;army_tradition;max_manpower;manpower;army_size;total_army;total_navy;FL;player;discipline;expense'
			  );
			  
$request_url = $sk_url . '?' . http_build_query($data);
$body = file_get_contents($request_url);
$object = json_decode($body, true);
 
if (!is_array($object)) 
{
  echo "No data returned from Skanderbeg";
  return;
}
$data_date = array('key'=> $private_key,
              'scope'=>'getCountryData',
              'format'=>'json',
              'save'=> $_GET['id'],
			  'value' => 'date'
			  );
			  
$request_url_date = $sk_url . '?' . http_build_query($data_date);
$body_date = file_get_contents($request_url_date);
$object_date = json_decode($body_date, true);
$date = $object_date[0];
$debug = 0;
if(isset($_GET['debug'])) $debug = 1;

$save = new Save($date);
$countries = array();

foreach($object as $country)
{
	$country_data = $country[0];
	if($country_data['was_player'] != 'Yes') continue;
	$c = new Country($country_data, $save);
	$c->Calculate();
	array_push ( $countries , $c );
}


?>
<table id="table">
	<thead>
		<th data-sortable="true">Tag</th>
		<th data-sortable="true">Country</th>
		<th data-sortable="true">Player</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="Takes into account discipline, tech, morale, fire & shock modifiers, tech group, etc. (except from temporary discipline bonuses like advisors or strict leaders)">Quality</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="Force limit + 60% of max. manpower + 30% of possible manpower from professionalism">Quantity</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="Quantity * Quality">Potential Military Strength</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="Pure income">Income</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="A military strength backed by enough income">Sustainable Military Strength</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="The surplus resulting after fielding max armies possible">Economic Surplus</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="Trying to represent the strength of a country taking into account both max. military strength and also economic surplus">Overall Strength</th>
		<?php if($debug == true) { ?>
			<th>Data</th>
		<?php } ?>	
	</thead>
	<tbody>
<?php
foreach($countries as $country) {
?>
	<tr>
	<td><?= $country->data['tag'] ?></td>
	<td><?= $country->data['countryName'] ?></td>
	<td><?= $country->data['player'] ?></td>
	<td><?= $country->quality ?></td>
	<td><?= $country->quantity ?></td>
	<td><?= $country->military_potential ?></td>
	<td><?= $country->economic_power ?></td>
	<td><?= $country->military_sustainable ?></td>
	<td><?= $country->free_economic_power ?></td>
	<td></td>
	<?php if($debug == true) { ?>
	<td><?= print_r($country->data); ?></td>
	<?php } ?>
	</tr>
	<?php 
}
?>
</tbody>
</table>
<canvas id="myChart" style="max-height:400px; max-width:800px; margin:20px;"></canvas>
<img width="400" height="400" src="./Galley.png"/>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    }
   
});

  function numberFormatter(x) {
    return new Intl.NumberFormat('es-ES', { maximumFractionDigits: 0 }).format(x);
  }
  $(function() {
	var $table = $('#table')
    $table.bootstrapTable({
      classes : "table table-bordered table-hover table-striped",
	  theadClasses: "thead-dark"
		})
	})
</script>