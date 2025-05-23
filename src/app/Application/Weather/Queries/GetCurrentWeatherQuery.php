<?php

namespace App\Application\Weather\Queries;

use App\Application\Weather\DTOs\WeatherRequestDTO;
use App\Application\Weather\DTOs\WeatherResponseDTO;
use App\Domain\Weather\Services\WeatherService;
use App\Exceptions\Custom\ApiAccessException;
use App\Exceptions\Custom\CityNotFoundException;

class GetCurrentWeatherQuery
{
    public function __construct(
        private readonly WeatherService $weatherService
    ) {
    }

    /**
     * @throws CityNotFoundException|ApiAccessException
     */
    public function execute(WeatherRequestDTO $dto): WeatherResponseDTO
    {
        $weatherData = $this->weatherService->getCurrentWeather($dto);

        return new WeatherResponseDTO(
            $weatherData->getTemperature(),
            $weatherData->getHumidity(),
            $weatherData->getDescription()
        );
    }
}
