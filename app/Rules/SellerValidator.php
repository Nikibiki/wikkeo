<?php

namespace App\Rules;

use App\Repositories\Interfaces\SellersRepositoryInterface;
use App\Repositories\SellersRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;

use Illuminate\Contracts\Validation\ValidatorAwareRule;

use Illuminate\Contracts\Validation\DataAwareRule;

class SellerValidator implements Rule, DataAwareRule
{
    protected $attr = '';
    protected $sellersRepository;
    protected $doValidation = false;
    protected $data = [];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sellersRepository = App::make(SellersRepositoryInterface::class);
        $this->doValidation = $this->sellersRepository instanceof SellersRepository;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $this->attr = $attribute;

        if (!$this->doValidation) return true;

        return match ($attribute) {
            'id' => !is_null($this->sellersRepository->getItem((int)$value)),
            'email' => is_null($this->sellersRepository->getItemByEmail($value)),
            'username' => is_null($this->sellersRepository->getItemByUsername($value)) || $this->isCurrentUserData($attribute),
            default => true,
        };
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        $messages = [
            'id' => 'User not found!',
            'email' => 'A user with such a similar email exists.',
            'username' => 'A user with such a similar username exists.',
        ];
        return $messages[$this->attr] ?? '';
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function isCurrentUserData(string $attr): bool
    {
        if (isset($this->data['id'], $this->data[$attr])) {
            $user = $this->sellersRepository->getItem(trim(htmlspecialchars((int)$this->data['id'])));
            return !is_null($user) && isset($user->toArray()[$attr]) && $user->toArray()[$attr] == $this->data[$attr];
        }

    }
}
