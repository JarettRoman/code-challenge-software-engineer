# Software Engineer Tech Challenge

### Introduction 

Welcome! The following scenario is fictional; its purpose is twofold: 
  1. It allows candidates to showcase their development abilities
  2. It provides a common starting point for deeper technical discussions

We're anticipating an hour or less to be spent on this exercise. While polish is certainly appreciated, completeness is the primary goal.  

### Requirements

Our product team has informed us of a need to better understand donation attempts on our public website. For the purposes of this exercise, assume you're a developer working with a small team including a product owner.

The product owner has asked for the following the metric:  
* How many users started a donation attempt in the past day?

Your task is to make modifications needed to **store the data** which will be used to answer this question for the product owner. It'll likely be easiest to reuse the existing [`data/users.json`](data/users.json) file for this purpose, but you are free to use whatever solution you feel works best. _You are not asked to create the actual reports as part of this exercise_. 
> [!IMPORTANT]  
> The goal of this exercise is only to **store** the data needed to answer the product owner's question. There is no need to implement an endpoint to return a report querying the user data. 

Assume that a user may donate more than once a day. As well, assume our frontend website has an existing donation workflow which can be modified in any way you see fit to send whatever data is needed to accomplish this goal and that authorization has already been taken into account.

### Deliverables

You'll be contributing to an existing API, written in PHP 8 and uses the [Slim v4 micro framework](https://www.slimframework.com/docs/v4/). In the directories within this challenge, you'll find a bare-bones implementation of this API, including the API routing layer ([`index.php`](index.php)), controllers, and data providers.

Please feel free to modify any of the existing code (including the API specification) and data storage in any way you see fit; there is no one true best solution here. 

We'll need a way to review the code that you've contributed to meet the objectives listed above. One way to accomplish this is to fork this repo, make any modifications needed, and then send us a link. If a Github fork is not feasible, a zip file with any contributions is certainly sufficient.  

### Local Development

If you're interested in running this API locally, you'll need PHP 8.2 and you'll likely want to install [composer](https://getcomposer.org/). If needed, there's an install script to aid with installing composer in the root of this project:
```shell
./install-composer.sh
```
This script will download a `composer.phar` file, from here you can install requirements via:
```shell
php composer.phar install
```
From this point, you can verify everything is setup correctly by running the unit tests and verifying their output via:
```shell
php composer.phar unit
```
And finally, you can start a webserver which will handle HTTP requests via:
```shell
php composer.phar start
```
At this point, provided all went well, you should be able to submit HTTP requests against your localhost in a separate terminal session:  
```shell
curl --location 'http://127.0.0.1:8080/v1/users/4'

# Status: 200 OK
# {
#    "id": "4",
#    "email": "a@b.com"
# }
```
