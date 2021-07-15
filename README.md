Java Developer Code Exercise
Write a class called Luhn that implements these three methods:
public class Luhn {
/**
* Accepts a card number and determines if the card number is a valid number with
respect to the
* Luhn algorithm.
* @param cardNumber the card number
* @return true if the card number is valid according to the Luhn algorithm, false
if not
*/
public boolean isValidLuhn(String cardNumber);
/**
* Accepts a partial card number (excluding the last digit) and generates the
appropriate Luhn
* check digit for the number.
* @param cardNumber the card number (not including a check digit)
* @return the check digit
*/
public String generateCheckDigit(String cardNumber);
/**
* Accepts two card numbers representing the starting and ending numbers of a
range of card numbers
* and counts the number of valid Luhn card numbers that exist in the range,
inclusive.
* @param startRange the starting card number of the range
* @param endRange the ending card number of the range
* @return the number of valid Luhn card numbers in the range, inclusive
*/
public int countRange(String startRange, String endRange);
}

The code does not need to be limited to just this class or just these methods. As many classes or methods that seem appropriate to best solve the
problem are allowed (and even encouraged).
We will be looking at accuracy, readability, design, and several other specific criteria that we don't explicitly identify. Once we receive the
response as an attachment(s), we will evaluate and respond shortly with next steps. Please do not include the code within the text body of the
email.
