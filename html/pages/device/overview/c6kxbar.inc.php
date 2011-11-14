<?php

  echo("<div style='background-color: #eeeeee; margin: 5px; padding: 5px;'>");
  echo("<p style='padding: 0px 5px 5px;' class=sectionhead>");
  echo('<a class="sectionhead" href="device/device='.$device['device_id'].'/tab=health/metric=mempool/">');
  echo("<img align='absmiddle' src='images/16/arrow_switch.png'> Catalyst 6k Crossbar</a></p>");
  echo("<table width=100% cellspacing=0 cellpadding=5>");
  $c6kxbar_rows = '0';

foreach($entity_state['group']['c6kxbar'] as $index => $entry)
{
  if (is_integer($c6kxbar_rows/2)) { $row_colour = $list_colour_a; } else { $row_colour = $list_colour_b; }

  $entity = dbFetchRow("SELECT * FROM entPhysical WHERE device_id = ? AND entPhysicalIndex = ?", array($device['device_id'], $index));

  echo("<tr bgcolor=$row_colour>
        <td colspan=5 width=200><strong>".$entity['entPhysicalName']."</strong></td>
        <td colspan=2>".$entry['']['cc6kxbarModuleModeSwitchingMode']."</td>
        </tr>");

  foreach($entity_state['group']['c6kxbar'][$index] as $subindex => $fabric)
  {
    if(is_numeric($subindex)) {

    if($fabric['cc6kxbarModuleChannelFabStatus'] == "ok")
    {
      $fabric['mode_class'] = "green";
    } else {
      $fabric['mode_class'] = "red";
    }

    $percent_in = $fabric['cc6kxbarStatisticsInUtil'];
    $background_in = get_percentage_colours($percent_in);

    $percent_out = $fabric['cc6kxbarStatisticsOutUtil'];
    $background_out = get_percentage_colours($percent_out);

    $graph_array           = array();
    $graph_array['height'] = "100";
    $graph_array['width']  = "210";
    $graph_array['to']     = $now;
    $graph_array['id']     = $device['device_id'];
    $graph_array['mod']    = $index;
    $graph_array['chan']   = $subindex;
    $graph_array['type']   = "c6kxbar_util";
    $graph_array['from']   = $day;
    $graph_array['legend'] = "no";

    $link_array = $graph_array;
    $link_array['page'] = "graphs";
    unset($link_array['height'], $link_array['width'], $link_array['legend']);
    $link = generate_url($link_array);

    $overlib_content = generate_overlib_content($graph_array, $device['hostname'] . " - " . $text_descr);

    $graph_array['width'] = 80; $graph_array['height'] = 20; $graph_array['bg'] = $graph_colour;

    $minigraph =  generate_graph_tag($graph_array);

    echo("<tr bgcolor=$row_colour>
          <td width=10></td>
          <td width=200><strong>Fabric ".$subindex."</strong></td>
          <td><span style='font-weight: bold;' class=".$fabric['mode_class'].">".$fabric['cc6kxbarModuleChannelFabStatus']."</span></td>
          <td>".formatRates($fabric['cc6kxbarModuleChannelSpeed']*1000000)."</td>
          <td>".overlib_link($link, $minigraph, $overlib_content)."</td>
          <td>".print_percentage_bar (125, 20, $percent_in, "Ingress", "ffffff", $background['left'], $percent_in . "%", "ffffff", $background['right'])."</td>
          <td>".print_percentage_bar (125, 20, $percent_out, "Egress", "ffffff", $background['left'], $percent_out . "%", "ffffff", $background['right'])."</td>
          </tr>");

    }
  }

  $c6kxbar_rows++;

}

  echo("</table>");
  echo("</div>");

?>