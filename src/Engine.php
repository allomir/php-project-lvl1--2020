<?php

namespace Brain\Games\Engine;

use function cli\line;
use function cli\prompt;

#LOGIC ENGINE

function getRoundNum()
{
    return 3;
}

function linesGame($label, $args = [])
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

function linesGameKind($kind)
{
    $strings = [
        'calc' => "What is the result of the expression?",
        'gcd' => "Find the greatest common divisor of given numbers.",
        'progression' => "What number is missing in the progression?",
    ];

    line($strings[$kind]);
}

function promtsGame($kind)
{
    $strings = [
        'user' => "May I have your name? ",
        'answer' => "Your answer: ",
    ];

    echo $strings[$kind];

    return trim(fgets(STDIN));
}

function startQuestions($user, $questionKind, $roundNum = 3)
{
    for ($i = 1; $i <= $roundNum; $i++) {
        $questionAndResult = getQuestionAndResult($questionKind);
        $result = $questionAndResult['result'];
        $question = $questionAndResult['question'];

        linesGame('question', ['question' => $question]);
        $answer = promtsGame('answer');

        $correct = (string) $result === $answer;

        if ($correct === false) {
            linesGame('wrong', ['user' => $user, 'result' => $result, 'answer' => $answer]);
            break;
        }

        linesGame('right');
    }

    if ($correct !== false) {
        linesGame('congratulations', ['user' => $user]);
    }
}

#QUESTIONS

function getOperand()
{
    return rand(1, 10);
}

function getOperators()
{
    return ['+', '-', '*'];
}

function getQuestionAndResult($questionKind)
{
    $qustion = [
        'calc' => getQuestionCalc(),
        'gcd' => getQuestionGcd(),
        'progression' => getQuestionProgression(),
    ];

    return $qustion[$questionKind];
}

function getQuestionCalc()
{
    $operators = getOperators();

    $parts = [
        getOperand(),
        $operators[array_rand($operators)],
        getOperand()
    ];

    $question = implode(" ", $parts); // пример 5 + 3
    $result = 'next eval';
    eval("\$result = $question;");

    return ['question' => $question, 'result' => $result];
}

function getQuestionGcd()
{
    $parts = [
        getOperand(),
        getOperand(),
    ];

    $question = implode(" ", $parts); // пример 3 9

    #Поиск GCD
    {
    $numbers = array_map(function ($value) {
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
    }

    return ['question' => $question, 'result' => $GCD];
}

function getQuestionProgression()
{
    $parts = array_fill(0, rand(5, 10), null);
    $firstNum = rand(1, 10);
    $step = rand(1, 5);
    array_walk($parts, function (&$value, $key) use ($firstNum, $step) {
        $value = $firstNum + $key * $step;
    });

    $partsWithHint = $parts;
    $hintedKey = array_rand($parts);
    $result = $parts[$hintedKey];

    $partsWithHint[$hintedKey] = '..';
    $question = implode(" ", $partsWithHint);

    return ['question' => $question, 'result' => $result];
}
