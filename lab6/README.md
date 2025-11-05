For this lab, I got to work more with PHP and get more familiar with POST requests. I took some time to understand the code that was provided which helped me complete the additional functions (subtraction, multiplication, division).

1. Explain what each of your classes and methods does, the order in which methods are invoked, and the flow of execution after one of the operation buttons has been clicked.

- The Operation class is the parent class which validates that the two operands are numbers and stores them. It requires its child classes to have an operate and getEquation method. The children classes Addition, Subtraction, Multiplication, and Division provide the specific math logic for the operate method. They also specify whta should be printed from the getEquation method.

- After a operation button has been clicked, the form sends POST data, the script checks which button was pressed and instanstiates the corresponding class which calles the construct method. Lastly, the getEquation method is called on the new object which calls its own operate method to get the result and print it.

2. Also explain how the application would differ if you were to use $\_GET, and why this may or may not be preferable.

- If we were to use $\_GET then our URL would include the form data and we would need to replace all $\_POST variables to $\_GET. While this makes the result linkable and easy to bookmark, it's preferred to use POST as it is the standard method for submitting data to be processed. GET is meant for retrieving data (like a search query). For a calculator, submitting data for a calculation is the primary action, making POST the preferred choice.

3. Finally, please explain whether or not there might be another (better +/-) way to determine which button has been pressed and take the appropriate action

- A better way to do this without the ifs would be to map the operation names to their respective class names then use a loop to go through the map and check which operation was requested and instantiate the corresponding class dynamically.
