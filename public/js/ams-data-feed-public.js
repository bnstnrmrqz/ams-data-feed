(function($)
{
	$(function()
	{
		if($('div#amsReadings[data-type="tthm"]').length)
		{
			if($('select#locationFilter').length)
			{
				$('select#locationFilter').on('change', function()
				{
					var selection = $(this).val();
					if(selection === 'all')
					{
						$('div#amsReadings[data-type="tthm"] div.city').each(function()
						{
							$(this).addClass('city-show').removeClass('city-hide');
						});
					}
					else
					{
						$('div#amsReadings[data-type="tthm"] div.city').each(function()
						{
							if ($(this).data('city') === selection)
							{
								$(this).addClass('city-show').removeClass('city-hide');
							}
							else
							{
								$(this).addClass('city-hide').removeClass('city-show');
							}
						});
					}
				});
			}
			$('div.city').each(function()
			{
				var $city = $(this);
				var $reading = $city.find('div.reading');
				var currentIndex = 0;

				$reading.hide();
				$reading.eq(currentIndex).show();

				function nextReading()
				{
					$reading.eq(currentIndex).slideUp(400);
					currentIndex = (currentIndex + 1) % $reading.length;
					$reading.eq(currentIndex).slideDown(400);
				}
				setInterval(nextReading, 3000);
			});
		}
	});
})(jQuery);
