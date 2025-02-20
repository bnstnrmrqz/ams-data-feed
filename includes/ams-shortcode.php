<?php
function shortcode_ams_data_feed($atts)
{
	$a = shortcode_atts(array(
		'type' => 'tthm', // (string) tthm | mg
		'output' => 'all', // (string|array) values depend on "type"
		'theme' => 'default', // (string) default | none
		'data' => 'multi' // (string) multi | blue | none
	), $atts);

	// Parse the output attribute
	$outputFields = [];
	if (!empty($a['output']))
	{
		$outputFields = array_map('trim', explode(',', $a['output']));
	}

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
			elseif($type == 'chromium')
			{
				if($concentration < 0.02)
				{
					return 'great';
				}
				elseif($concentration >= 0.02 && $concentration < 10)
				{
					return 'good';
				}
				elseif($concentration >= 10 && $concentration < 100)
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
			$output .= '<div id="amsReadings" data-type="'.$a['type'].'" data-theme="'.$a['theme'].'">';
				$output .= '<select id="locationFilter">';
					$output .= '<option value="all">All locations</option>';
					$output .= '<option value="benicia">Benicia, California</option>';
					$output .= '<option value="sunnyvale">Sunnyvale, California</option>';
				$output .= '</select>';
				// Loop through regions and cities
				$rsi = 0;
				foreach($data as $region)
				{
					//echo '<pre>'; print_r($region); echo '</pre>';
					if($region['name'] === 'California')
					{
						$rsi++;
						$output .= '<div class="region region-'.convertToSlug($region['name']).'" data-region="'.convertToSlug($region['name']).'">';
							$ci = 0;
							foreach($region['cities'] as $city)
							{
								$ci++;
								$output .= '<div class="city city-'.convertToSlug($city['name']).'" data-city="'.convertToSlug($city['name']).'">';
									$ri = 0;
									foreach($city['readings'] as $reading)
									{
										$ri++;
										$output .= '<div class="reading reading-'.$ri.'" data-reading="'.$ri.'">';
											$output .= '<ul data-data="'.$a['data'].'">';
												if($a['theme'] === 'default')
												{
													$output .= '<li class="data data-timestamp">'.formatDate($reading['timeStamp']).'</li>';
													$output .= '<li class="data data-concentration" data-level="'.classifyConcentration($reading['tthmConc'], 'tthm').'">'.$reading['tthmConc'].'</li>';
													if($reading['sampleType'] === 'online'): $sampleTypeLabel = 'TTHM'; elseif($reading['sampleType'] === 'thm_fp'): $sampleTypeLabel = 'THM-FP'; endif;
													$output .= '<li class="data data-element" data-level="'.classifyConcentration($reading['tthmConc'], 'tthm').'"><label>'.$sampleTypeLabel.' Concentration</label></li>';
												}
												else
												{
													$output .= '<li class="data data-timestamp"><label>'.formatDate($reading['timeStamp']).'</label></li>';
													if(!empty($outputFields) && in_array('br13Conc', $outputFields) || in_array('all', $outputFields))
													{
														$output .= '<li class="data data-br13" data-level="'.classifyConcentration($reading['br13Conc'], 'br13').'"><label>Br13 Concentration:</label>'.$reading['br13Conc'].'</li>';
													}
													if(!empty($outputFields) && in_array('cl3Conc', $outputFields) || in_array('all', $outputFields))
													{
														$output .= '<li class="data data-cl3" data-level="'.classifyConcentration($reading['cl3Conc'], 'cl3').'"><label>Cl3 Concentration:</label>'.$reading['cl3Conc'].'</li>';
														}
													if(!empty($outputFields) && in_array('tthmConc', $outputFields) || in_array('all', $outputFields))
													{
														$output .= '<li class="data data-tthm" data-level="'.classifyConcentration($reading['tthmConc'], 'tthm').'"><label>TTHM Concentration:</label>'.$reading['tthmConc'].'</li>';
													}
													if(!empty($outputFields) && in_array('chloroform', $outputFields) || in_array('all', $outputFields))
													{
														$output .= '<li class="data data-chloroform" data-level="'.classifyConcentration($reading['chloroform'], 'chloroform').'"><label>Chloroform:</label>'.$reading['chloroform'].'</li>';
													}
												}
											$output .= '</ul>';
										$output .= '</div>';
									}
								$output .= '</div>'; // Close city div
							}
						$output .= '</div>'; // Close region div
					}
				}
			$output .= '</div>'; // Close readings div
		}
		elseif($a['type'] === 'mg')
		{
			//echo '<pre>'; print_r($data); echo '</pre>';
			// Prepare HTML output
			$output .= '<div id="amsReadings" data-type="'.$a['type'].'" data-theme="'.$a['theme'].'">';
				// Loop through regions and cities
				$rsi = 0;
				foreach($data as $region)
				{
					//echo '<pre>'; print_r($region); echo '</pre>';
					if($region['name'] === 'CA')
					{
						$rsi++;
						$output .= '<div class="region region-'.convertToSlug($region['name']).'" data-region="'.convertToSlug($region['name']).'">';
							$ci = 0;
							foreach($region['cities'] as $city)
							{
								$ci++;
								$output .= '<div class="city city-'.convertToSlug($city['name']).'" data-city="'.convertToSlug($city['name']).'">';
									$ri = 0;
									foreach($city['readings'] as $reading)
									{
										$ri++;
										$output .= '<div class="reading reading-'.$ri.'" data-reading="'.$ri.'">';
											$output .= '<ul data-data="'.$a['data'].'">';
												if($a['theme'] === 'default')
												{
													$output .= '<li class="data data-city">'.$reading['city'].'</li>';
													$output .= '<li class="data data-timestamp">'.formatDate($reading['timeStamp']).'</li>';
													$output .= '<li class="data data-concentration" data-level="'.classifyConcentration($reading['concentration'], 'chromium').'">'.$reading['concentration'].'</li>';
													$output .= '<li class="data data-element" data-level="'.classifyConcentration($reading['concentration'], 'chromium').'"><label>'.$reading['element'].' Concentration</label></li>';
												}
												else
												{
													if(!empty($outputFields) && in_array('city', $outputFields) || in_array('all', $outputFields))
													{
														$output .= '<li class="data data-city"><label>City:</label>'.$reading['city'].'</li>';
													}
													$output .= '<li class="data data-timestamp"><label>'.formatDate($reading['timeStamp']).'</label></li>';
													if(!empty($outputFields) && in_array('element', $outputFields) || in_array('all', $outputFields))
													{
														$output .= '<li class="data data-element"><label>Element:</label>'.$reading['element'].'</li>';
													}
													if(!empty($outputFields) && in_array('elementName', $outputFields) || in_array('all', $outputFields))
													{
														$output .= '<li class="data data-element-name"><label>Element Name:</label>'.$reading['elementName'].'</li>';
													}
													if(!empty($outputFields) && in_array('concentration', $outputFields) || in_array('all', $outputFields))
													{
														$output .= '<li class="data data-concentration" data-level="'.classifyConcentration($reading['concentration'], 'chromium').'"><label>Concentration:</label>'.$reading['concentration'].'</li>';
													}
													if(!empty($outputFields) && in_array('units', $outputFields) || in_array('all', $outputFields))
													{
														$output .= '<li class="data data-units"><label>Units:</label>'.$reading['units'].'</li>';
													}
													if(!empty($outputFields) && in_array('sampleType', $outputFields) || in_array('all', $outputFields))
													{
														$output .= '<li class="data data-sample-type"><label>Sample Type:</label>'.$reading['sampleType'].'</li>';
													}
													if(!empty($outputFields) && in_array('region', $outputFields) || in_array('all', $outputFields))
													{
														$output .= '<li class="data data-region"><label>Region:</label>'.$reading['region'].'</li>';
													}
												}
											$output .= '</ul>';
										$output .= '</div>';
									}
								$output .= '</div>'; // Close city div
							}
						$output .= '</div>'; // Close region div
					}
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
