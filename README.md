# ScrapeStoreGoogleMapJob

## Introduction

`ScrapeStoreGoogleMapJob` in Yii2 is a job designed for scraping store information from Google based on a selected distance and search query. It calculates the search ranking of stores located near a specific place using grid points generated around the target coordinates (latitude and longitude). The job processes each grid point to find nearby stores and checks their ranking in the search results.

## Features

- **Grid-Based Search**: Calculates store rankings by generating grid points around a central location (latitude, longitude).
- **Customizable Search Query**: Allows you to define a search query, defaulting to "Nail Salon" if no query is provided.
- **Distance Control**: Adjust the distance over which the grid points are calculated, ensuring accurate nearby store searches.
- **Progress Tracking**: Tracks the progress of the scraping process and publishes updates via MQTT.
- **Store Ranking**: Checks and processes store rankings based on the search results, ensuring that the store's position is properly identified.
- **Average Position Calculation**: Computes the average position of each store across all grid points and sends the results via MQTT.

## How It Works

### Grid Point Generation

The job calculates a set of grid points based on the target coordinates (latitude, longitude) and the distance. The grid points cover a region around the target location, ensuring comprehensive store search coverage.

### Store Data Processing

For each grid point, the job sends a request to an external API to search for nearby stores. The API returns store information, including the store's position in the search results. If a store is found at a specific grid point, the store's data is processed and added to the results.

### Progress Updates

During the process, the job calculates the percentage of completion and sends progress updates via MQTT to provide real-time feedback.

### Average Position Calculation

After processing all grid points, the job calculates the average position of stores. The top 10 stores with the highest count of occurrences are selected, and their average position is calculated and sent to the MQTT server.

## Usage

To use `ScrapeStoreGoogleMapJob`, you need to have the following parameters set in your environment:

- `query`: The search query for the store (e.g., "Nail Salon").
- `placeId`: The ID of the store or place you want to target.
- `latitude`: The latitude of the target location.
- `longitude`: The longitude of the target location.
- `zoom`: The zoom level for the search.
- `distance`: The distance radius for generating grid points.
- `numberGridPoint`: The number of grid points to use for the search.
- `token`: A unique token used for identifying the process (used for MQTT updates).

## Authors

- @hungdev

## API Reference
```http
GET /info
```
```http
GET /store?q...&latitude=...&longitude=...&zoom=...&distance=...&placeId=...&grid_point_count=...
```
#Results
![image](https://github.com/user-attachments/assets/4435c429-5ada-4877-96aa-262aa92e3053)
![image](https://github.com/user-attachments/assets/e6366575-e184-4af4-b777-622237f34011)


