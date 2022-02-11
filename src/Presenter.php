<?php

namespace V1nk0\LaravelPresenter;

use Illuminate\Support\Collection;

abstract class Presenter
{
    public $with = [];

    /**
     * Presenter constructor.
     * @param array $with
     */
    public function __construct($with = [])
    {
        $this->with = $with;
    }

    /**
     * @param $input
     * @return Collection|array|null
     */
    public function present($input)
    {
        if(($input instanceof Collection)) {
            return $this->collection($input);
        }
        else {
            return ($input) ? $this->single($input) : null;
        }
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function collection(Collection $collection): Collection
    {
        $out = collect();

        foreach($collection as $item) {
            $out->push($this->present($item));
        }

        return $out;
    }

    /**
     * @param null $keys
     * @return $this
     */
    public function with($keys = null): Presenter
    {
        if(!$keys) {
            return $this;
        }

        foreach (is_array($keys) ? $keys : func_get_args() as $key) {
            if(!in_array($key, $this->with)) {
                $this->with[] = $key;
            }
        }

        return $this;
    }

    /**
     * @param $needle
     * @return array
     */
    public function withFor($needle): array
    {
        $out = [];
        foreach($this->with as $key) {
            if(substr($key, 0, strlen($needle) + 1) === $needle.'.') {
                $out[] = substr($key, strlen($needle) + 1);
            }
        }

        return $out;
    }

    /**
     * @param $key
     * @return bool
     */
    protected function shouldInclude($key): bool
    {
        return (in_array($key, $this->with) || in_array('*', $this->with));
    }
}
