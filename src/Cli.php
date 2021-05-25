<?php

namespace Brain\Games\Cli;

use function cli\line;
use function cli\prompt;

function welcomeLine(): void {
    line('Welcome to the Brain Game!');
}

function whatYourName(): string {
    return prompt('May I have your name?');
}

function helloLine(string $name): void {
    line("Hello, %s!", $name);
}
