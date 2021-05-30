<?php

namespace Brain\Games\Engine;

use function cli\prompt;

function getNumRounds()
{
    return 3;
}

function welcome()
{
    return "Welcome to the Brain Games!";
}

function getName()
{
    return prompt("May I have your name?");
}

function helloUser($name)
{
    return "Hello, {$name}!\n" . "What is the result of the expression?";
}

function getOperand()
{
    return rand(1, 10);
}

function getOperators()
{
    return ['+', '-', '*'];
}

function getQuestion()
{
    $operators = getOperators();

    $parts = [
        getOperand(),
        $operators[array_rand($operators)],
        getOperand()
    ];

    return implode(" ", $parts);
}

function getAnswer()
{
    $result = (int) prompt("Your answer");
    return $result;
}

function getResult($question)
{
    $result = null;
    eval("\$result = $question;");

    return $result;
}

function getStatus($name, $result, $answer)
{
    return $result === $answer ? getStatuses()['right'] : getStatuses($name, $result, $answer)['wrong'];
}

function getStatuses($name = null, $result = null, $answer = null)
{
    return [
        'right' => 'Correct!',
        'wrong' => "'{$answer}' is wrong answer ;(. Correct answer was '{$result}'.\n"
            . "Let's try again, {$name}!"
    ];
}

function getCongratulations($name)
{
    return "Congratulations, {$name}!";
}
