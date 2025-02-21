document.addEventListener('DOMContentLoaded', function()
{
	document.querySelectorAll('#amsDataFeed').forEach((amsDataFeed) =>
	{
		const locationFilter = amsDataFeed.querySelector('#locationFilter');
		let activeCarousel = null; // Track the currently running interval

		function startCarousel(readings)
		{
			if(readings.length === 0) return;
			let index = 0;

			function showNext()
			{
				readings.forEach((r) => (r.style.display = 'none')); // Hide all
				readings[index].style.display = 'block'; // Show current
				index = (index + 1) % readings.length; // Move to next
			}
			if(activeCarousel) clearInterval(activeCarousel); // Stop previous carousel
			showNext(); // Show first item immediately
			activeCarousel = setInterval(showNext, 3000); // Change every 3 sec
		}
		function filterAndStartCarousel()
		{
			const allCities = amsDataFeed.querySelectorAll('.city');
			const allReadings = amsDataFeed.querySelectorAll('.reading');

			if(!locationFilter)
			{
				// No filter present, cycle through all readings and show all cities
				allCities.forEach((city) =>
				{
					city.classList.remove('city-hide');
					city.classList.add('city-show');
				});
				startCarousel(Array.from(allReadings));
				return;
			}
			const selectedCity = locationFilter.value.trim().toLowerCase();

			// Stop any currently running carousel
			if (activeCarousel) clearInterval(activeCarousel);

			// Reset visibility for all readings
			allReadings.forEach((r) => (r.style.display = 'none'));
			allCities.forEach((city) =>
			{
				city.classList.remove('city-show');
				city.classList.add('city-hide');
			});

			if(selectedCity === 'all')
			{
				// Show all cities and cycle through all readings
				allCities.forEach((city) =>
				{
					city.classList.remove('city-hide');
					city.classList.add('city-show');
				});
				startCarousel(Array.from(allReadings));
			}
			else
			{
				// Show only the selected city
				const selectedCityElement = amsDataFeed.querySelector(`.city.city-${CSS.escape(selectedCity)}`);

				if(selectedCityElement)
				{
					selectedCityElement.classList.remove('city-hide');
					selectedCityElement.classList.add('city-show');
					const cityReadings = selectedCityElement.querySelectorAll('.reading');

					if(cityReadings.length > 0)
					{
						startCarousel(Array.from(cityReadings));
					}
				}
			}
		}
		if(locationFilter)
		{
			locationFilter.addEventListener('change', filterAndStartCarousel);
		}
		filterAndStartCarousel(); // Start carousel on page load
	});
});
