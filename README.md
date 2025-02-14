# AMS Data Feed WordPress Plugin
This plugin allows users to display a feed of data via a shortcode on their WordPress website. The data is pulled from the Aqua Metrology Systems (AMS) API, which provides real-time readings from the THM-100, a fully automated trihalomethane (THM) monitoring unit.

The THM-100 is installed along water treatment networks, enabling water plant operators to access THM level data as frequently as every hour. Using this plugin, you can effortlessly integrate these readings into your website, offering a seamless way to share critical water quality data.

**Features:**

- Simple shortcode-based integration (e.g., `[ams_data_feed]`)
- Customizable attributes to tailor the displayed feed
- Reliable and real-time data updates from the AMS API

**Use Case:** This plugin is perfect for water plant operators, municipalities, and organizations that need to share water quality data with the public or internal stakeholders in a clear and accessible format.

## Basic Usage

**Feed types:**

- Magnesium: `[ams_data_feed type="mg"]`
- Total Trihalomethanes: `[ams_data_feed type="tthm"]`

## Changelog

- **1.1** — February 13, 2025
  - Added additional "theme" attribute.
  - Added additional "output" attribute.
  - Added conditional logic to `$output` variable regarding "output" attribute. 
  - Added "chromium" range condition to `classifyConcentration()` function.
  - Added `if(!function_exists())` to prevent "cannot redeclare" PHP error.
  - Added additional CSS styles for "default" value for newly created "theme" attribute.
- **1.0** — February 11, 2025
  - Initial public release.
