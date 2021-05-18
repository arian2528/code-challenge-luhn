<?php


class Luhn
{
    /**
     * Gets rid of any non alphanumeric characters in the param string
     * @param string $cardNumber The raw card number
     * @return string
     * @access private
     *
     * CodeChallengeComment: Normally this will go into a helper class
     */
    private function cleanCardNumber(string $cardNumber): string {

        // Get rid of any dashes or spaces
        return preg_replace('/[^a-z0-9]/', '', strtolower($cardNumber));
    }

    /**
     * Validates that the card number only contains digits
     * @param string $cardNumber
     * @throws Exception
     *
     * CodeChallengeComment: This could have be handle in some other previous step
     */
    private function validateCardNumber(string $cardNumber)
    {
        if (!ctype_digit($cardNumber)) {
            throw new Exception("cardNumber param contains an invalid card number");
        }
    }

    /**
     * Calculate the Luhn algorithm source https://en.wikipedia.org/wiki/Luhn_algorithm
     * @param string $cardNumber
     * @return int
     * @access private
     *
     * This could be in his own class and Luhn class will extend it
     */
    private function calculateLuhn(string $cardNumber): int
    {
        $sum = 0;
        for($i = 1; $i <= strlen($cardNumber); $i++) {
            // double every other number
            $digitVal = (int) ((int)($i - 1) % 2 === strlen($cardNumber) % 2)
                ? $cardNumber[$i - 1] * 2
                : $cardNumber[$i - 1];
            // if double number > 9 replace it with the sum of it own digits
            // and add them to get teh final number
            $sum += ($digitVal > 9) ? $digitVal - 9 : $digitVal;
        }

        // returns the module of 10, the reminder of $sum divided by 10
        return $sum % 10;
    }

    /**
     * Accepts a card number and determines if teh card number is a valid number with the respect to the Luhn algorithm
     * @param string $cardNumber
     * @return bool true if the card number is valid, false if not
     * @throws Exception
     */
    public function isValidLuhn(string $cardNumber): bool
    {
        $cardNumber = $this->cleanCardNumber($cardNumber);

        $this->validateCardNumber($cardNumber);

        return ($this->calculateLuhn($cardNumber) == 0);
    }

    /**
     * Accepts a partial card number (excluding the last digit)
     * and generates the appropriate Luhn check digit for the number
     * @param string $cardNumber the card number (not including a check digit)
     * @return int the check digit
     * @throws Exception
     */
    public function generateCheckDigit(string $cardNumber): int
    {
        $cardNumber = $this->cleanCardNumber($cardNumber);

        $this->validateCardNumber($cardNumber);

        $luhnResult = $this->calculateLuhn($cardNumber . '0');

        return ($luhnResult == 0) ? 0 : 10 - $luhnResult;
    }


    /**
     * Accepts two card numbers representing the starting and ending numbers of a range of card numbers
     * and counts the number of valid Luhn card numbers that exist in the range, inclusive.
     * @param string $startRange the starting card number of the range
     * @param string $endRange the ending card number of the range
     * @return int the number of valid Luhn card numbers in the range, inclusive
     * @throws Exception
     */
    public function countRange(string $startRange, string $endRange): int
    {
        $startRange = $this->cleanCardNumber($startRange);
        $endRange = $this->cleanCardNumber($endRange);

        if (!ctype_digit($startRange) || !ctype_digit($endRange)) {
            throw new Exception("startRange and/or endRange param contains an invalid card number");
        }

        if((int)$startRange >= (int)$endRange) {
            throw new Exception("startRange must be card number less than endRange card number");
        }

        $validCards = 1;
        $newStartRange = $startRange;
        // check if $startRange isValid
        if(!$this->isValidLuhn($startRange)){
            $newStartRange = $this->getNextValidCard($startRange);
            if((int)$newStartRange >= (int)$endRange) {
                return (int)$this->isValidLuhn($endRange);
            }
        }

        // get the difference between endRange and startRange
        $possibleNumberOfValidCards = (int) ceil((float)(((int) $endRange - (int) $newStartRange) / 10));
        $x = 0;
        while ($x < $possibleNumberOfValidCards) {
            $newStartRange = $this->getNextValidCard($newStartRange);
            if((int)$newStartRange > (int)$endRange) break;
            $validCards++;
            $x++;
        }

        return $validCards;
    }

    /**
     * Generates the next valid card based on a valid or invalid card number as a start range value
     * @param string $startRange the starting card number as a start range value
     * @return string the next valid card number based on the startRange card number
     * @throws Exception
     */
    private function getNextValidCard(string $startRange): string
    {
        // First, remove the last digit from the startRange
        $partialStartRangeCardNumber = substr($startRange, 0, -1);

        $newStartRange = $partialStartRangeCardNumber . $this->generateCheckDigit($partialStartRangeCardNumber);

        // check if new startRange it's after current startRange
        if((int)$startRange >= (int)$newStartRange){
            // increment by one the new partial start range card number
            $newPartialStartRangeCardNumber = (int)($partialStartRangeCardNumber) + 1;

            $newStartRange = $newPartialStartRangeCardNumber
                . $this->generateCheckDigit((string)$newPartialStartRangeCardNumber);

        }

        return $newStartRange;
    }
}