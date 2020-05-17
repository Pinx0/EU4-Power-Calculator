<?php
function countrySorter($a, $b)
{
    if ($a == $b) {
        return 0;
    }
    return ($a->military_potential < $b->military_potential) ? 1 : -1;
}

$data = array('key'=> ConnectionInfo::$skanderbegPrivateKey,
              'scope'=>'getCountryData',
              'format'=>'json',
              'save'=> $_GET['id'],
              'playersOnly'=> true,
			  'value' => 'was_player;tag;countryName;quality;technology_group;technology;monthly_income;treasury;max_manpower;manpower;army_size;total_army;total_navy;FL;player;discipline;expense;hex'
			  );
			  
$request_url = ConnectionInfo::$skanderbegApiUrl . '?' . http_build_query($data);
$body = file_get_contents($request_url);
$object = json_decode($body, true);
 if (!is_array($object)) 
{
  echo "No data returned from Skanderbeg";
  return;
}
$data_date = array('key'=> ConnectionInfo::$skanderbegPrivateKey,
              'scope'=>'getCountryData',
              'format'=>'json',
              'save'=> $_GET['id'],
			  'value' => 'date'
			  );
			  
$request_url_date = ConnectionInfo::$skanderbegApiUrl . '?' . http_build_query($data_date);
$body_date = file_get_contents($request_url_date);
$object_date = json_decode($body_date, true);
$date = $object_date[0];
$debug = 0;
if(isset($_GET['debug'])) $debug = 1;
if(isset($_GET['config'])) 
{
	if(isset($_GET['morale'])) { $use_morale = 1; } else { $use_morale = 0; }
	if(isset($_GET['approx_tech'])) { $approximate_tech = 1; } else { $approximate_tech = 0; }
	if(isset($_GET['at'])) { $use_at = 1; } else { $use_at = 0; }
} else
{
	$use_morale = 1;
	$approximate_tech = 1;
	$use_at = 1;
}
$db = new DBConversation(ConnectionInfo::$dbHost, ConnectionInfo::$dbUser, ConnectionInfo::$dbPassword, ConnectionInfo::$dbName);
$save = new Save($date,$db);

$countries = array();
foreach($object as $country)
{
	$country_data = $country[0];
	//if($country_data['was_player'] != 'Yes' || $country_data['monthly_income'] < 1) continue;
	$c = new Country($country_data, $save, $db);
	$c->calculate($use_morale, $approximate_tech, $use_at);
	array_push ($countries , $c);
}

usort($countries, "countrySorter");
?>
<div id="options">
	<form method="GET" action="/">
		<span>Quality configuration:</span>
		<input type="hidden" name="config" value="1"/>
		<input type="checkbox" value="1" name="morale" <?php if($use_morale==1) {?>checked="checked"<?php } ?> /><label for="morale">Use Morale</label>
		<input type="checkbox" value="1" name="approx_tech" <?php if($approximate_tech==1) {?>checked="checked"<?php } ?> /><label for="approx_tech">Approximate Tech</label>
		<input type="checkbox" value="1" name="at" <?php if($use_at==1) {?>checked="checked"<?php } ?> /><label for="at">Use Army Tradition</label>
		<span>Other options:</span>
		<input type="checkbox" value="1" name="debug" <?php if($debug==1) {?>checked="checked"<?php } ?> /><label for="debug">Debug Mode</label>
		<input type="hidden" name="id" value="<?= $_GET['id'] ?>"/>
		<input type="submit" value="Go!"/>
	</form>
</div>
<table id="table">
	<thead>
		<th data-sortable="true">Tag</th>
		<th data-sortable="true">Country</th>
		<th data-sortable="true">Player</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="The amount of enemy men killed in a battle against an standard western nation without any buff, per day, on average. Higher is better.">Off. Quality</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="The amount of own men killed in a battle against an standard western nation without any buff, per day, on average. Lower is better.">Def. Quality</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc" data-title-tooltip="The amount of men killed per each man lost">Quality</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="Force limit + 60% of max. manpower + 30% of possible manpower from professionalism">Quantity</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="Size of the army that can be destroyed by this country">Mil. Strength</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="Pure income">Income</th>
		<?php /* <th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="A military strength backed by enough income">Sustainable Military Strength</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="The surplus resulting after fielding max armies possible">Economic Surplus</th>
		<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter" data-sort-order="desc" data-title-tooltip="Trying to represent the strength of a country taking into account both max. military strength and also economic surplus">Overall Strength</th>
		*/ ?><?php if($debug == true) { ?>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Fire Dmg</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Shock Dmg</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Fire Dmg Red</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Shock Dmg Red</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Disc.</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Tactics</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Inf CA</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Cav CA</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Art CA</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Inf F Mod</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Cav F Mod</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Art F Mod</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Inf S Mod</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Cav S Mod</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Art S Mod</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Inf F Of</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Cav F Of</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Art F Of</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Inf S Of</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Cav S Of</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Art S Of</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Inf F Df</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Cav F Df</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Art F Df</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Inf S Df</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Cav S Df</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Art S Df</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Fire Gen</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Shock Gen</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Eff. Mil Tech</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Real Mil Tech</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Eff. AT</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Real AT</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Morale Factor</th>
			<th data-sortable="true" data-search-formatter="false" data-formatter="numberFormatter2D" data-sort-order="desc">Morale</th>

		<?php } ?>	
	</thead>
	<tbody>
<?php
foreach($countries as $country) {
?>
	<tr>
	<td><?= $country->tag ?></td>
	<td><?= $country->name ?></td>
	<td><?= $country->player ?><?php if($country->player=="necotheone") { ?><img width="35" height="35" src="./images/Galley.png"/><?php } ?></td>
	<td><?= $country->off_quality ?></td>
	<td><?= $country->def_quality ?></td>
	<td><?= $country->quality ?></td>
	<td><?= $country->quantity ?></td>
	<td><?= $country->military_potential ?></td>
	<td><?= $country->economic_power ?></td>
<?php /*	<td><?= $country->military_sustainable ?></td>
	<td><?= $country->free_economic_power ?></td>
<td></td> */ ?>
	<?php if($debug == true) { ?>
	<td><?= $country->fire_damage ?></td>
	<td><?= $country->shock_damage ?></td>
	<td><?= $country->fire_damage_received  ?></td>
	<td><?= $country->shock_damage_received  ?></td>
	<td><?= $country->discipline ?></td>
	<td><?= $country->effective_tactics ?></td>
	<td><?= $country->infantry_combat_ability ?></td>
	<td><?= $country->cavalry_combat_ability ?></td>
	<td><?= $country->artillery_combat_ability ?></td>
	<td><?= $country->infantry_fire_total_modifier ?></td>
	<td><?= $country->cavalry_fire_total_modifier ?></td>
	<td><?= $country->artillery_fire_total_modifier ?></td>
	<td><?= $country->infantry_shock_total_modifier ?></td>
	<td><?= $country->cavalry_shock_total_modifier ?></td>
	<td><?= $country->artillery_shock_total_modifier ?></td>
	<td><?= $country->infantry_unit->fire_off ?></td>
	<td><?= $country->cavalry_unit->fire_off ?></td>
	<td><?= $country->artillery_unit->fire_off ?></td>
	<td><?= $country->infantry_unit->shock_off ?></td>
	<td><?= $country->cavalry_unit->shock_off ?></td>
	<td><?= $country->artillery_unit->shock_off ?></td>
	<td><?= $country->infantry_unit->fire_def ?></td>
	<td><?= $country->cavalry_unit->fire_def ?></td>
	<td><?= $country->artillery_unit->fire_def ?></td>
	<td><?= $country->infantry_unit->shock_def ?></td>
	<td><?= $country->cavalry_unit->shock_def ?></td>
	<td><?= $country->artillery_unit->shock_def ?></td>
	<td><?= $country->general_average_fire ?></td>
	<td><?= $country->general_average_shock ?></td>
	<td><?= $country->effective_mil_tech->tech_level ?></td>
	<td><?= $country->real_mil_tech->tech_level ?></td>
	<td><?= $country->effective_at ?></td>
	<td><?= $country->at ?></td>
	<td><?= $country->morale_factor ?></td>
	<td><?= $country->effective_morale ?></td>
	<?php } ?>
	</tr>
	<?php 
}
?>
</tbody>
</table>
<canvas id="myChart"></canvas>
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
		foreach($countries as $country) {  echo "'".$country->hex."'," ; }
		?> ],
            data: [<?php 
		foreach($countries as $country) { echo "'".round($country->military_potential,0)."'," ; }
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