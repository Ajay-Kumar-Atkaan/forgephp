<?php
declare(strict_types=1);

function cleanInput(string $input): string
{
    return htmlspecialchars($input);
}

function basePath(): string
{
    return $_ENV['BASE_PATH'];
}