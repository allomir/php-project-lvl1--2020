<?php

namespace Brain\Games\Engine;

use function cli\line;
use function cli\prompt;

#ENGINE

function gameTitle()
{
    line("Welcome to the Brain Games!");
}

function enterName()
{
    # побочный эффект STDIN
    $name = getName();
    # побочный эффект STDOUT
    line("Hello, {$name}!\n");

    return $name;
}

function choosingGame($kindOfGame = 'calc')
{
    $gameBreaf = [
        'calc' => "What is the result of the expression?",
        'gcd' => "Find the greatest common divisor of given numbers.",
    ];

    # побочный эффект STDOUT
    line($gameBreaf[$kindOfGame]);
}

function questions($name, $kindOfGame = 'calc', $RoundNum = 3)
{
    choosingGame($kindOfGame);

    for ($i = 1; $i <= $RoundNum; $i++) {
        $question = getQuestion($kindOfGame);
        # побочный эффект STDOUT
        line("Question: ". $question);
    
        $result = getResult($question, $kindOfGame);
        # побочный эффект STDIN
        $answer = getAnswer();

        $correct = $result === $answer;

        if ($correct === false) {
            # побочный эффект STDOUT
            line(getCorrectStatus($name, $result, $answer)['wrong']);
            break;
        }

        # побочный эффект STDOUT
        line(getCorrectStatus()['right']);
    }

    if ($correct !== false) {
        getCongratulations($name);
    }
}

#ENGINE FUNCTIONS

function getRoundNum()
{
    return 3;
}

function getName()
{
    echo "May I have your name? ";
    $name = trim(fgets(STDIN));

    return $name;
    // return prompt("May I have your name?");
}

function getOperand()
{
    return rand(1, 10);
}

function getOperators()
{
    return ['+', '-', '*'];
}

function getQuestion($questionType = 'calc')
{
    $qustion = [
        'calc' => getQuestionCalc(),
        'gcd' => getQuestionGcd(),
    ];

    return $qustion[$questionType];
}

function getQuestionCalc()
{
    $operators = getOperators();

    $parts = [
        getOperand(),
        $operators[array_rand($operators)],
        getOperand()
    ];

    return implode(" ", $parts);
}

function getQuestionGcd()
{
    $parts = [
        getOperand(),
        getOperand(),
    ];

    return implode(" ", $parts);
}

function getAnswer()
{
    $result = (int) prompt("Your answer");
    return $result;
}

function getResult($question, $questionType = 'calc')
{
    $result = null;
    if ($questionType === 'calc') {
        eval("\$result = $question;");
    } elseif ($questionType === 'gcd') {
        $numbers = explode(" ", $question);
        $numbers = array_map(function ($value) {
            return abs((int) $value);
        }, $numbers);
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

        return $divisors[0] ?? 1;
    }

    return $result;
}

function getCorrectStatus($name = null, $result = null, $answer = null)
{
    return [
        'right' => 'Correct!',
        'wrong' => "'{$answer}' is wrong answer ;(. Correct answer was '{$result}'.\n"
            . "Let's try again, {$name}!"
    ];
}

function getCongratulations($name)
{
    line("Congratulations, {$name}!");
}
