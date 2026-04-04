<?php

namespace App\Services\Yummy;

use App\Models\Yummy\RestaurantModel;
use App\Repositories\Interfaces\Yummy\IRestaurantRepository;
use App\Services\Interfaces\Yummy\IRestaurantService;
use Exception;

class RestaurantService implements IRestaurantService {
    private IRestaurantRepository $repository;
    private string $uploadPath = __DIR__ . '/../../../public/assets/uploads/restaurants/'; 

    public function __construct(IRestaurantRepository $repository) {
        $this->repository = $repository;
    }

    public function getAllRestaurants(): array {
        return $this->repository->getAllActive();
    }

    public function getRestaurantById(int $id): ?RestaurantModel {
        return $this->repository->getById($id);
    }

    public function createRestaurant(RestaurantModel $restaurant): bool {
        return $this->repository->create($restaurant);
    }

    // public function updateRestaurant(RestaurantModel $restaurant): bool {
    //     return $this->repository->update($restaurant);
    // }

    public function deleteRestaurant(int $id): bool {
        return $this->repository->delete($id);
    }

    public function processNewRestaurant(array $data, ?string $imageFileName): bool {
        $restaurant = new RestaurantModel();
        
        $restaurant->setName(trim($data['name'] ?? ''));
        $restaurant->setDescription(trim($data['description'] ?? ''));
        $restaurant->setLocation(trim($data['location'] ?? ''));
        $restaurant->setCuisine(trim($data['cuisine'] ?? ''));
        $restaurant->setLongDescription($data['long_description'] ?? '');
        $restaurant->setSessionDuration((int)($data['session_duration'] ?? 0));
        $restaurant->setReservationFee((float)($data['reservation_fee'] ?? 0));
        $restaurant->setTotalSlots((int)($data['total_slots'] ?? 0));
        $restaurant->setChefId(!empty($data['chef_id']) ? (int)$data['chef_id'] : null);

        if ($imageFileName) {
            $restaurant->setImageUrl('/assets/uploads/restaurants/' . $imageFileName);
        }

        return $this->createRestaurant($restaurant);
    }

    public function processUpdateRestaurant(int $id, array $data, ?string $imageFileName): bool {
        $restaurant = $this->repository->getById($id);
        if (!$restaurant) throw new Exception("Restaurant not found");

        $restaurant->setName(trim($data['name'] ?? ''));
        $restaurant->setDescription(trim($data['description'] ?? ''));
        $restaurant->setLocation(trim($data['location'] ?? ''));
        $restaurant->setCuisine(trim($data['cuisine'] ?? ''));
        $restaurant->setLongDescription($data['long_description'] ?? '');
        $restaurant->setSessionDuration((int)($data['session_duration'] ?? 0));
        $restaurant->setReservationFee((float)($data['reservation_fee'] ?? 0));
        $restaurant->setTotalSlots((int)($data['total_slots'] ?? 0));
        $restaurant->setChefId(!empty($data['chef_id']) ? (int)$data['chef_id'] : null);
        
        if ($imageFileName) {
            $restaurant->setImageUrl('/assets/uploads/restaurants/' . $imageFileName);
        }

        return $this->repository->update($restaurant);
    }

    public function uploadImage(?array $file): ?string {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newFileName = uniqid('restaurant_', true) . '.' . $extension;

        if (move_uploaded_file($file['tmp_name'], $this->uploadPath . $newFileName)) {
            return $newFileName;
        }
        
        return null;
    }

}