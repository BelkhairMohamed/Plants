<?php
/**
 * Weather Service - Integrates with weather API
 * Currently uses mock data, but can be easily connected to OpenWeatherMap API
 */

class WeatherService {
    private $apiKey = null; // Set your OpenWeatherMap API key here
    private $baseUrl = 'https://api.openweathermap.org/data/2.5/weather';
    
    /**
     * Get weather data for a city
     * @param string $city
     * @return array|null
     */
    public function getWeatherByCity($city) {
        // If no API key, return mock data for demonstration
        if (!$this->apiKey) {
            return $this->getMockWeather($city);
        }
        
        // Real API call (uncomment when you have API key)
        /*
        $url = $this->baseUrl . "?q=" . urlencode($city) . "&appid=" . $this->apiKey . "&units=metric";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        
        if ($data && isset($data['main'])) {
            return [
                'temperature' => $data['main']['temp'],
                'humidity' => $data['main']['humidity'],
                'description' => $data['weather'][0]['description'] ?? 'unknown'
            ];
        }
        */
        
        return null;
    }
    
    /**
     * Mock weather data for demonstration
     */
    private function getMockWeather($city) {
        // Simulate different weather based on city
        $mockData = [
            'Paris' => ['temperature' => 18, 'humidity' => 65],
            'London' => ['temperature' => 15, 'humidity' => 70],
            'New York' => ['temperature' => 22, 'humidity' => 55],
            'Tokyo' => ['temperature' => 25, 'humidity' => 75],
        ];
        
        $default = ['temperature' => 20, 'humidity' => 60];
        $data = $mockData[$city] ?? $default;
        
        return [
            'temperature' => $data['temperature'],
            'humidity' => $data['humidity'],
            'description' => 'partly cloudy'
        ];
    }
    
    /**
     * Get care recommendations based on weather
     * @param array $weather
     * @param array $plantRequirements
     * @return array
     */
    public function getCareRecommendations($weather, $plantRequirements) {
        $recommendations = [];
        
        $temp = $weather['temperature'] ?? 20;
        $humidity = $weather['humidity'] ?? 60;
        
        // Temperature recommendations
        if (isset($plantRequirements['temperature_min']) && $temp < $plantRequirements['temperature_min']) {
            $recommendations[] = "⚠️ Temperature is below minimum for this plant. Consider moving it to a warmer location.";
        }
        
        if (isset($plantRequirements['temperature_max']) && $temp > $plantRequirements['temperature_max']) {
            $recommendations[] = "🌡️ Temperature is high. Ensure adequate watering and consider misting.";
        }
        
        // Humidity recommendations
        $humidityNeeds = $plantRequirements['humidity_preference'] ?? 'medium';
        $needsHighHumidity = $humidityNeeds === 'high' && $humidity < 50;
        $needsMediumHumidity = $humidityNeeds === 'medium' && $humidity < 40;
        
        if ($needsHighHumidity || $needsMediumHumidity) {
            $recommendations[] = "💧 Low humidity detected. Consider using a humidifier or misting your plant more frequently.";
        }
        
        // Hot and dry conditions
        if ($temp > 25 && $humidity < 50) {
            $recommendations[] = "🔥 Hot and dry conditions. Your plants may need more frequent watering.";
        }
        
        // Cold conditions
        if ($temp < 15) {
            $recommendations[] = "❄️ Cold weather. Protect sensitive plants from drafts and cold windows.";
        }
        
        return $recommendations;
    }
    
    /**
     * Set API key for real weather data
     */
    public function setApiKey($key) {
        $this->apiKey = $key;
    }
}




