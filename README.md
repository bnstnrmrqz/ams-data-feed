# AMS Data Feed WordPress Plugin
This plugin allows users to display a feed of data via a shortcode on their WordPress website. The data is pulled from the Aqua Metrology Systems (AMS) API, which provides real-time readings from the THM-100, a fully automated trihalomethane (THM) monitoring unit.

The THM-100 is installed along water treatment networks, enabling water plant operators to access THM level data as frequently as every hour. Using this plugin, you can effortlessly integrate these readings into your website, offering a seamless way to share critical water quality data.

**Features:**

- Simple shortcode-based integration (e.g., `[ams_data_feed]`)
- Customizable attributes to tailor the displayed feed
- Reliable and real-time data updates from the AMS API

**Use Case:** This plugin is perfect for water plant operators, municipalities, and organizations that need to share water quality data with the public or internal stakeholders in a clear and accessible format.

## Attribute Usage

**Type (required):**

- Magnesium: `[ams_data_feed type="mg"]`
- Total Trihalomethanes: `[ams_data_feed type="tthm"]`

**Output (optional):**

- All: `[ams_data_feed type="tthm" output="all"]`
- Individual: `[ams_data_feed type="mg" output="city, concentration"]`

_**Note:** Default value is `all` even if the attribute is not defined. The attribute `theme="default"` has its own defined output of data and will therefor overwrite this attribute._

**Theme (optional):**

- Default: `[ams_data_feed type="tthm" theme="default"]`
- None: `[ams_data_feed type="tthm" theme="none"]`

_**Note:** Default value is `default` even if the attribute is not defined in the shortcode._

**Data (optional):**

- Multi: `[ams_data_feed type="tthm" data="multi"]`
- Blue: `[ams_data_feed type="tthm" data="blue"]`
- None: `[ams_data_feed type="tthm" data="none"]`

_**Note:**  Default value is `multi` even if the attribute is not defined in the shortcode._

## Changelog

- **1.3.1** — February 21, 2025
  - Updated `amsReadings` ID reference to `amsDataFeed` for consistency across plugins within same family.
- **1.3.0** — February 20, 2025
  - Rewrote `ams-data-feed-public.js` JavaScript file.
  - Updated `if($a['theme'] === 'default')` logic in `ams-shortcode.php` file.
  - Updated `[data-theme="default"]` CSS styling in `ams-data-feed-public.css` file.
  - _**Please read the updated [Attribute Usage](#attribute-usage) section.**_
- **1.2.0** — February 17, 2025
  - Fixed `if($().length)` logic in `ams-data-feed-public.js` file.
  - Updated `[data-theme="default"]` CSS styling in `ams-data-feed-public.css` file.
  - Added `[data-data=""]` CSS styling in `ams-data-feed-public.css` file.
  - Added additional "data" attribute.
- **1.1.1** — February 17, 2025
  - Global code format clean up.
- **1.1.0** — February 13, 2025
  - Added additional "theme" attribute.
  - Added additional "output" attribute.
  - Added conditional logic to `$output` variable regarding "output" attribute.
  - Added "chromium" range condition to `classifyConcentration()` function.
  - Added `if(!function_exists())` to prevent "cannot redeclare" PHP error.
  - Added additional CSS styles for "default" value for newly created "theme" attribute.
  - Fixed jQuery `div#amsReadings[data-type="tthm"]` selector for drop down menu on change function.
- **1.0.0** — February 11, 2025
  - Initial public release.
