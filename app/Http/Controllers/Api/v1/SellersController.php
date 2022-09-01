<?php

namespace App\Http\Controllers\Api\v1;


use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSellerFormRequest;
use App\Http\Requests\UpdateSellerFormRequest;
use App\Repositories\Interfaces\SellersRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class SellersController extends Controller
{
    const DATA_PATH = 'sellers.json';

    public function __construct(
        public SellersRepositoryInterface $sellersRepository
    ){
    }

    public function index(): string
    {
        return $this->sellersRepository->getAll()->sortBy('id')->values()->toJson();
    }

    public function show($id): string
    {
        return $this->sellersRepository->getItem((int)$id)->toJson();
    }

    public function store(CreateSellerFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $seller = $request->validated();
        $seller['id'] = $this->sellersRepository->getLastId() + 1;
        $this->updateData($this->sellersRepository->getAll()->merge([$seller])->toJson());

        return response()->json($seller, 200);
    }

    public function update(UpdateSellerFormRequest $request): bool
    {
        $data = $request->validated();
        $seller = $this->sellersRepository->getItem($data['id'])->merge($data);

        return $this->updateData($this->sellersRepository->getAllWithout([$data['id']])->merge([$seller])->toJson());
    }

    /**
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $this->updateData($this->sellersRepository->getAllWithout([$id])->toJson());
        return response('', 204);
    }

    protected function updateData(string $data): bool
    {
        return Storage::disk('data')->put(self::DATA_PATH, $data);
    }

    public function fakeData(): void
    {
        $data = json_encode([]);
        $response = Http::get('https://jsonplaceholder.typicode.com/users');

        if ($response->successful()) {
            $data = json_encode($response->json());
        }
        $this->updateData($data);
    }

}
