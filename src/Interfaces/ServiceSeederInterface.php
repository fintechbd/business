<?php

namespace Fintech\Business\Interfaces;

interface ServiceSeederInterface
{
    public function run(): void;
    public function serviceTypes(): array;
    public function service(): array;
    public function serviceStat($source_countries = [], $destination_countries = []): array;
}
