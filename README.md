# boxalino Client SDK in PHP

The Boxalino Client SDK provides a simple and efficient usage of Boxalino Search & Recommendations services (based on the p13n Thrift client libraries as separately provided in respective git-hub repositories) and to Boxalino Data Intelligence especially for Data Synchronization (define the structure of your data, push and publish your data structure configuration, push data in dev/prod and full/delta to Boxalino).

The Boxalino Client SDK are particularly interesting for integrators of Boxalino technologies and provides the following advantages compare to using the underlying API directly.

## Key advantages

1. Very easy examples (such as frontend search basic) to start from (step by step)
2. Many examples to understand each functionality individually
3. Very little amount of code to write and to maintain (all the hard stuff is done in the client)
4. Very well adapted for most MVC environment where the full requests / responses are not created all at the same place (easy to do if keeping the instance of BxClient as static)
5. Many embedded logics not to worry about anymore (e.g.: search corrections directly active and integrated, no need to worry about it, a simple method returns if the query was corrected or not as indication)
6. No need to create your XML data specification file by yourself anymore (provide the links to your data CSV and simple indications of which csv columsn to put in which fields)
7. Easy to test data correctness automatically before sending your data to Boxalino Data Intelligence (client can check if there is any mis-match with your column to field specfications)
8. Easy to interact with all the APIs (all calls are embedded in the client and you simply need to call simple to use methods of the client instance)
9. Very easy to understand error messages and to know what to do (error messages often even indicates where it is most likely you should solve the problem, like in the case of issues with SSL certificates to get the connection)
10. Easy to print the request Thrift object and send it to Boxalino for any support request

## Installation

1. Before you start to test, make sure you download all the dependencies via composer (first download and install it form getcomposer.org, then run composer install in your project directory).
2. Copy the lib folder (with the Thrift library) wherever you want (it doesn't need to be called "lib")
3. Take any of the examples in the "examples" folder to test (if $libPath as per the examples is set to the path to your lib folder, the rest should work out of the box)
4. Make sure you have received your credentials (account and password) from Boxalino to access your account (if you don't have them, please contact support@boxalino.com)

## Examples
For sample examples on how to integrate the PHP SDK (from data exports to recommendations, search, autocomplete and others),
please check out the QA repo 

https://github.com/boxalino/boxalino-client-QA-php

## Contact us!

If you have any question, just contact us at support@boxalino.com