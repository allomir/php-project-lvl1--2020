<?php

namespace Brain\Games\Engine;

use function cli\line;
use function cli\prompt;

#LOGIC ENGINE

function getRoundNum(): int
{
    return 3;
}

function linesGame(string $label, array $args = []): void
{
    $user = $args['user'] ?? '';
    $question = $args['question'] ?? '';
    $answer = $args['answer'] ?? '';
    $result = $args['result'] ?? '';

    $strings = [
        "welcome" => "Welcome to the Brain Games!",
        "hello" => "Hello, {$user}!\n",
        'question' => "Question: {$question}",
        'right' => 'Correct!',
        'wrong' => "'{$answer}' is wrong answer ;(. Correct answer was '{$result}'.\n"
            . "Let's try again, {$user}!",
        'congratulations' => "Congratulations, {$user}!"
    ];

    line($strings[$label]);
}

function linesGameTitle(string $kind): void
{
    $strings = [
        'calc' => "What is the result of the expression?",
        'even' => "Answer \"yes\" if the number is even, otherwise answer \"no\".",
        'gcd' => "Find the greatest common divisor of given numbers.",
        'progression' => "What number is missing in the progression?",
        'prime' => "Answer \"yes\" if given number is prime. Otherwise answer \"no\".",
    ];

    line($strings[$kind]);
}

function promtsGame(string $label): string
{
    $strings = [
        'user' => "May I have your name? ",
        'answer' => "Your answer: ",
    ];

    echo $strings[$label];
    $string = fgets(STDIN);

    return $string !== false ? trim($string) : '';
}

function startQuestions(string $user, string $questionKind, int $roundNum = 3): bool
{
    $correctly = false;

    for ($i = 1; $i <= $roundNum; $i++) {
        $questionAndResult = getQuestionAndResult($questionKind);
        $result = $questionAndResult['result'];
        $question = $questionAndResult['question'];

        linesGame('question', ['question' => $question]);
        $answer = promtsGame('answer');

        $correctly = (string) $result === $answer;

        if ($correctly === false) {
            linesGame('wrong', ['user' => $user, 'result' => $result, 'answer' => $answer]);
            break;
        }

        linesGame('right');
    }

    if ($correctly !== false) {
        linesGame('congratulations', ['user' => $user]);
    }

    return $correctly;
}

#QUESTIONS

function getNumber(): int
{
    return rand(1, 10);
}

function getOperators(): array
{
    return ['+', '-', '*'];
}

function getQuestionAndResult(string $questionKind): array
{
    $qustion = [
        'calc' => getQuestionCalc(),
        'even' => getQuestionEven(),
        'gcd' => getQuestionGcd(),
        'progression' => getQuestionProgression(),
        'prime' => getQuestionPrime(),
    ];

    return $qustion[$questionKind];
}

function getQuestionCalc(): array
{
    $operators = getOperators();

    $parts = [
        getNumber(),
        $operators[array_rand($operators)],
        getNumber()
    ];

    $question = implode(" ", $parts); // ???????????? 5 + 3
    $result = 'follow eval';
    eval("\$result = $question;");

    return ['question' => $question, 'result' => $result];
}

function getQuestionEven(): array
{
    $question = getNumber();
    $result = $question % 2 === 0 ? 'yes' : 'no';

    return ['question' => $question, 'result' => $result];
}

function getQuestionGcd(): array
{
    $parts = [
        getNumber(),
        getNumber(),
    ];

    $question = implode(" ", $parts); // ???????????? 3 9

    #?????????? GCD
    $numbers = array_map(function ($value): int {
        return abs($value);
    }, $parts);
    sort($numbers, SORT_NUMERIC);

    $divisors = [];
    for ($i = 1; $i <= $numbers[0]; $i++) {
        $result1 = $numbers[1] % $i === 0;
        $result2 = $numbers[0] % $i === 0;

        if ($result1 && $result2) {
            $divisors[] = $i;
        }
    }
    rsort($divisors);
    $GCD = $divisors[0] ?? 1;

    return ['question' => $question, 'result' => $GCD];
}

function getQuestionProgression(): array
{
    $parts = array_fill(0, rand(5, 10), null);
    $firstNum = rand(1, 10);
    $step = rand(1, 5);
    array_walk($parts, function (&$value, $key) use ($firstNum, $step): void {
        $value = $firstNum + $key * $step;
    });

    $partsWithHint = $parts;
    $hintedKey = array_rand($parts);
    $result = $parts[$hintedKey];

    $partsWithHint[$hintedKey] = '..';
    $question = implode(" ", $partsWithHint);

    return ['question' => $question, 'result' => $result];
}

function getQuestionPrime(): array
{
    $primes = [
        2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47,
        53, 59, 61, 67, 71, 73, 79, 83, 89, 97, 101, 103, 107, 109,
        113, 127, 131, 137, 139, 149, 151, 157, 163, 167, 173, 179,
        181, 191, 193, 197, 199
    ];

    $question = getNumber();
    $result = in_array($question, $primes, true) ? 'yes' : 'no';

    return ['question' => $question, 'result' => $result];
}
