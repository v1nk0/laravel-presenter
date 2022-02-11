<?php

namespace V1nk0\LaravelPresenter\Traits;

use V1nk0\LaravelPresenter\Presenter;

trait Presentable
{
    public function present(array $with = []): array
    {
        if(!isset($this->presenter)) {
            return [];
        }

        if(!class_exists($this->presenter)) {
            return [];
        };

        $presenter = new $this->presenter;

        if(!$presenter instanceof Presenter) {
            return [];
        }

        return (new $this->presenter)
            ->with($with)
            ->present($this);
    }
}
