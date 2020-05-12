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
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Phase</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">CA</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Tech</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Discipline</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Pips</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Generals</th>

		<?php } ?>	
	</thead>
	<tbody>
<?php
foreach($countries as $country) {
?>
	<tr>
	<td><?= $country->tag ?></td>
	<td><?= $country->name ?></td>
	<td><?= $country->player ?><?php if($country->player=="necotheone") { ?><img width="35" height="35" src="./Galley.png"/><?php } ?></td>
	<td><?= $country->quality ?></td>
	<td><?= $country->quantity ?></td>
	<td><?= $country->military_potential ?></td>
	<td><?= $country->economic_power ?></td>
	<td><?= $country->military_sustainable ?></td>
	<td><?= $country->free_economic_power ?></td>
	<td></td>
	<?php if($debug == true) { ?>
	<td><?= $country->phase_modifier ?></td>
	<td><?= $country->combat_ability_factor ?></td>
	<td><?= $country->tech_factor ?></td>
	<td><?= $country->discipline_factor ?></td>
	<td><?= $country->pips_factor ?></td>
	<td><?= $country->generals_factor ?></td>

	<?php } ?>
	</tr>
	<?php 
}
?>
</tbody>
</table>
<canvas id="myChart" style="max-width:800px;  margin: auto; width: 50%;"></canvas>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [
		
		<?php 
		foreach($countries as $country) { echo "'".$country->name."'," ; }
		?>],
        datasets: [{
            label: 'Military potential',
			backgroundColor: [<?php 
		foreach($countries as $country) {  echo "'rgba(".mt_rand(0, 255).", ".mt_rand(0, 255).", ".mt_rand(0, 255).", 0.6)'," ; }
		?> ],
            data: [<?php 
		foreach($countries as $country) { echo "'".$country->military_potential."'," ; }
		?>],
            borderWidth: 1
        }]
    }
   
});

  function numberFormatter(x) {
    return new Intl.NumberFormat('es-ES', { maximumFractionDigits: 0 }).format(x);
  }
    function numberFormatter2D(x) {
    return new Intl.NumberFormat('es-ES', { maximumFractionDigits: 2 }).format(x);
  }
  $(function() {
	var $table = $('#table')
    $table.bootstrapTable({
      classes : "table table-bordered table-hover table-striped",
	  theadClasses: "thead-dark"
		})
	})
</script>