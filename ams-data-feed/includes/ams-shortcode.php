<?php
function shortcode_ams_data_feed($atts)
{
	$a = shortcode_atts(array(
		'type' => 'tthm' // (string) tthm | mg
	), $atts);

	// Helper functions
	if(!function_exists('convertToSlug'))
	{
		function convertToSlug($string)
		{
			$string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
			$string = strtolower($string);
			$string = preg_replace('/[^a-z0-9]+/', '-', $string);
			return trim($string, '-');
		}
	}

	if(!function_exists('formatDate'))
	{
		function formatDate($dateString)
		{
			$date = new DateTime($dateString);
			return $date->format('F j, Y \a\t g:ia');
		}
	}

	if(!function_exists('classifyConcentration'))
	{
		function classifyConcentration($concentration, $type)
		{
			if($type == 'br13')
			{
				if($concentration < 10)
				{
					return 'great';
				}
				elseif($concentration >= 10 && $concentration < 20)
				{
					return 'good';
				}
				elseif($concentration >= 20 && $concentration < 30)
				{
					return 'bad';
				}
				else
				{
					return 'dangerous';
				}
			}
			elseif($type == 'cl3')
			{
				if($concentration < 5)
				{
					return 'great';
				}
				elseif($concentration >= 5 && $concentration < 10)
				{
					return 'good';
				}
				elseif($concentration >= 10 && $concentration < 15)
				{
					return 'bad';
				}
				else
				{
					return 'dangerous';
				}
			}
			elseif($type == 'tthm')
			{
				if($concentration < 40)
				{
					return 'great';
				}
				elseif($concentration >= 40 && $concentration < 80)
				{
					return 'good';
				}
				elseif($concentration >= 80 && $concentration < 120)
				{
					return 'bad';
				}
				else
				{
					return 'dangerous';
				}
			}
			elseif($type == 'chloroform')
			{
				if($concentration < 50)
				{
					return 'great';
				}
				elseif($concentration >= 50 && $concentration < 100)
				{
					return 'good';
				}
				elseif($concentration >= 100 && $concentration < 150)
				{
					return 'bad';
				}
				else
				{
					return 'dangerous';
				}
			}
		}
	}

	// Fetch data from API
	//$endpoint = 'https://amslivedataapi.azurewebsites.net/Thm/Readings';
	if($a['type'] === 'tthm')
	{
		$endpoint = 'https://amslivedataapi.azurewebsites.net/Thm/Readings';
	}
	elseif($a['type'] === 'mg')
	{
		$endpoint = 'https://amslivedataapi.azurewebsites.net/Mg/Readings';
	}
	$output = '';

	try
	{
		$json = file_get_contents($endpoint);
		if($json === false)
		{
			throw new Exception('Error fetching the JSON feed.');
		}

		$data = json_decode($json, true);
		if($data === null)
		{
			throw new Exception('Error decoding the JSON feed.');
		}

		if($a['type'] === 'tthm')
		{
			//echo '<pre>'; print_r($data); echo '</pre>';
			// Prepare HTML output
			$output .= '<div id="amsReadings" data-type="'.$a['type'].'">';
				$output .= '<select id="locationFilter">';
					$output .= '<option value="benicia">Benicia, California</option>';
					$output .= '<option value="sunnyvale">Sunnyvale, California</option>';
					$output .= '<option value="arizona">Arizona, State University</option>';
				$output .= '</select>';
				// Loop through regions and cities
				$rsi = 0;
				foreach($data as $region)
				{
					$rsi++;
					$output .= '<div class="region region-'.convertToSlug($region['name']).'" data-region="'.convertToSlug($region['name']).'">';
						$ci = 0;
						foreach($region['cities'] as $city)
						{
							$ci++;
							$classes = 'city city-'.convertToSlug($city['name']);
							if($rsi === 1 && $ci === 1)
							{
								$classes .= ' city-show';
							}
							else
							{
								$classes .= ' city-hide';
							}
							$output .= '<div class="'.$classes.'" data-city="'.convertToSlug($city['name']).'">';
								$ri = 0;
								foreach($city['readings'] as $reading)
								{
									$ri++;
									$output .= '<div class="reading reading-'.$ri.'" data-reading="'.$ri.'">';
										$output .= '<ul>';
											$output .= '<li class="data data-timestamp"><strong>'.formatDate($reading['timeStamp']).'</strong></li>';
											$output .= '<li class="data data-br13" data-level="'.classifyConcentration($reading['br13Conc'], 'br13').'"><strong>Br13 Concentration:</strong> '.$reading['br13Conc'].'</li>';
											$output .= '<li class="data data-cl3" data-level="'.classifyConcentration($reading['cl3Conc'], 'cl3').'"><strong>Cl3 Concentration:</strong> '.$reading['cl3Conc'].'</li>';
											$output .= '<li class="data data-tthm" data-level="'.classifyConcentration($reading['tthmConc'], 'tthm').'"><strong>TTHM Concentration:</strong> '.$reading['tthmConc'].'</li>';
											$output .= '<li class="data data-chloroform" data-level="'.classifyConcentration($reading['chloroform'], 'chloroform').'"><strong>Chloroform:</strong> '.$reading['chloroform'].'</li>';
										$output .= '</ul>';
									$output .= '</div>';
								}
							$output .= '</div>'; // Close city div
						}
					$output .= '</div>'; // Close region div
				}
			$output .= '</div>'; // Close readings div
		}
		elseif($a['type'] === 'mg')
		{
			//echo '<pre>'; print_r($data); echo '</pre>';
			// Prepare HTML output
			$output .= '<div id="amsReadings" data-type="'.$a['type'].'">';
				// Loop through regions and cities
				$rsi = 0;
				foreach($data as $region)
				{
					$rsi++;
					$output .= '<div class="region region-'.convertToSlug($region['name']).'" data-region="'.convertToSlug($region['name']).'">';
						$ci = 0;
						foreach($region['cities'] as $city)
						{
							$ci++;
							$classes = 'city city-'.convertToSlug($city['name']);
							if($rsi === 1 && $ci === 1)
							{
								$classes .= ' city-show';
							}
							else
							{
								$classes .= ' city-hide';
							}
							$output .= '<div class="'.$classes.'" data-city="'.convertToSlug($city['name']).'">';
								$ri = 0;
								foreach($city['readings'] as $reading)
								{
									$ri++;
									$output .= '<div class="reading reading-'.$ri.'" data-reading="'.$ri.'">';
										$output .= '<ul>';
											$output .= '<li class="data data-timestamp"><strong>'.formatDate($reading['timeStamp']).'</strong></li>';
											$output .= '<li class="data data-element"><strong>Element:</strong> '.$reading['element'].'</li>';
											$output .= '<li class="data data-element-name"><strong>Element Name:</strong> '.$reading['elementName'].'</li>';
											$output .= '<li class="data data-concentration"><strong>Concentration:</strong> '.$reading['concentration'].'</li>';
											$output .= '<li class="data data-units"><strong>Units:</strong> '.$reading['units'].'</li>';
											$output .= '<li class="data data-sample-type"><strong>Sample Type:</strong> '.$reading['sampleType'].'</li>';
											$output .= '<li class="data data-city"><strong>City:</strong> '.$reading['city'].'</li>';
											$output .= '<li class="data data-region"><strong>Region:</strong> '.$reading['region'].'</li>';
										$output .= '</ul>';
									$output .= '</div>';
								}
							$output .= '</div>'; // Close city div
						}
					$output .= '</div>'; // Close region div
				}
			$output .= '</div>'; // Close readings div
		}
	}
	catch(Exception $e)
	{
		$output = 'Error: '.$e->getMessage();
	}
	return $output; // Return the generated HTML for use in a shortcode
}

// Register the shortcode
function register_ams_data_feed_shortcode()
{
	add_shortcode('ams_data_feed', 'shortcode_ams_data_feed');
}
add_action('init', 'register_ams_data_feed_shortcode');